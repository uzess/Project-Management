<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	protected $neededColumn     = 'u.username,u.password,u.first_name,u.last_name,u.user_role_id,u.created_on,u.ID,ur.name as role';
	
	protected $data;

	protected $accessibleMenu = array();

	public function __construct(){
		parent::__construct();
		$this->load->model( 'user_skill_m' );

		$this->data[ 'action' ]  = null;
		$this->data[ 'status' ]  = false;
		$this->data[ 'message' ] = null;
		$this->data[ 'data' ]    = null;

		if( $this->isLoggedIn() ){
			$role_id = $this->session->userdata('role_id');
			$query = $this->menu_m->getByRoleId( 'm.*',$role_id );
			if($query){
				foreach( $query->result() as $row ){
					$this->accessibleMenu[] = $row->permit_code;
				}
			}
		}
	}

	public function login(){

		$data = array( 'action'=>'login' );
		if( $this->session->userdata( 'user_id' ) > 0 ){

			$id = $this->session->userdata( 'user_id' );
			$user = $this->user_m->get( $this->neededColumn,array('u.ID'=>$id),1);
			$data['data'] = $user;
			$data['message'] = 'Already Logged In.';
			$data['status'] = true;

		}else{

			$_POST = json_decode(file_get_contents('php://input'), true);

			$username = $this->input->post( 'username' );
			$password = $this->input->post( 'password' );
			$user = $this->user_m->get( $this->neededColumn, array( 'u.username'=>$username,'u.password'=>$password ),1 );
			
			if( $user ){
				$data['data'] = $user;
				$data['message'] = 'Logged In Successfully.';
				$data['status'] = true;

				$sess_data = array( 
					'user_id'=>$user->ID,
					'username'=>$user->username,
					'first_name'=>$user->first_name,
					'last_name'=>$user->last_name,
					'role' => $user->role,
					'role_id'=>$user->user_role_id 
					);

				$this->session->set_userdata($sess_data);

			}else{
				$data['data'] = null;
				$data['message'] = 'Incorrect Username or password.';
				$data['status'] = false;
			}
		}

		echo json_encode($data);
	}

	public function logout(){

		$this->session->unset_userdata( 'user_id' );
		$this->session->sess_destroy();
	}

	public function get( $id = FALSE)
	{
		
		$this->data[ 'action' ] = "get"; 
		
		if( $this->isLoggedIn() ){

			if( $this->hasAccess( "user" ) ){

				$this->data['status'] = true;

				if( $id == FALSE ){

					$this->data['action'] = 'getAll';
					$users = $this->user_m->get( $this->neededColumn );

					if($users){

						$this->data['message'] = "Success";

						$cur_user_id = $this->session->userdata('user_id');

						foreach ( $users->result() as $key => $user) {

							if( $user->role !== "Super" && $user->ID !== $cur_user_id ){

								$query = $this->user_skill_m->get( '*',array( "user_id"=>$user->ID ) );

								if( $query ){

									$user->user_skill = $query->result(); 

								}else{
									$user->user_skill = null;
								}

								$this->data[ 'data' ][] = $user;
							}

						}

					}else{

						$this->data['data'] = null;
						$this->data['message'] = "User not found.";
					}

				}else{

					$this->data['action'] = 'getById';

					$this->setUserAndSkillById( $id );

				}
			}else{
				$this->deny();
			}
		}else{
			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode($this->data);
	}

	protected function setUserAndSkillById( $id ){

		$user = $this->user_m->get( $this->neededColumn,array('u.ID'=>$id), 1 );

		if( $user ){

			$this->data['message'] = "Success";
			$query = $this->user_skill_m->get( '*', array( "user_id" => $user->ID ) );

			if( $query ){
				$user->user_skill = $query->result();
			}else{
				$user->user_skill = null;
			}

			$this->data['data'] = $user;

		}else{

			$this->data['message'] = "User not found.";
			$this->data['data'] = null;
		}
	}

	protected function deny(){
		$this->data['status'] = false;
		$this->data['message'] = "Permission Denied.";
	}
	
	public function save()
	{
		$this->data['action'] = "save";
		if($this->isLoggedIn()){

			$_POST = json_decode(file_get_contents('php://input'), true);

			if($_POST){

				$this->data['status'] = true;

				$username =  $this->input->post('username');
				$first_name =  $this->input->post('first_name');
				$last_name = $this->input->post('last_name');
				$password  = $this->input->post('password');
				$role_id   = $this->input->post('user_role_id');

				$skill_id = $this->input->post( 'skillId' );

				$id = $this->input->post('ID');
				$data = array( "username"=>$username,"first_name"=>$first_name, "last_name"=>$last_name,"password"=>$password,'user_role_id'=>$role_id );

				if(empty($id)){
					#save
					$this->data['action'] = "Save";
					if( $this->hasAccess( "user-create" ) ){
						
						$id = $this->user_m->save($data);

						$this->saveSkill( $skill_id, $id );

						$this->setUserAndSkillById( $id );

						$this->data['message'] = "Saved Successfully.";

					}else{
						$this->deny();
					} 
				}
				else{
					#update
					$this->data['action'] = "Update";
					if( $this->hasAccess( 'user-update' )){

						$this->user_m->save($data,array("ID"=>$id));
						$this->user_skill_m->delete( array( "user_id"=>$id ));

						$this->saveSkill( $skill_id, $id );

						$this->setUserAndSkillById( $id );

						$this->data['message'] = "Updated Successfully.";

					}else{
						$this->deny();
					}
				}				
			}
			
		}else{

			$this->data['status'] = false;
			$this->data['message'] = "User not logged In.";
		}

		echo json_encode($this->data);

	}

	public function delete( $id )
	{
		$this->data['action'] = "delete";
		if( $this->isLoggedIn() ){
			if( $this->hasAccess( 'user-delete' ) ){

				$this->user_skill_m->delete( array( "user_id" => $id ) );
				$affected = $this->user_m->delete( array('ID'=>$id) );

				if($affected)
				{
					$this->data['status'] = true;
					$this->data['message'] = "Deleted Successfully.";
					$this->data['data'] = array("deletedId"=>array($id));
				}
				else
				{
					$this->data['message'] = "User not Deleted.";

				}

			}else{
				$this->deny();
			}
		}else{

			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode($this->data);	

	}

	public function checkLoginStatus(){
		
		$data = array(
			'action' => 'loginCheck',
			'data' => null,
			'message'=>null
		);
		
		if( $this->session->userdata('user_id') > 0 ){
			$data['status'] = true;
			$data['data'] = $this->session->userdata();
			$data['message'] = $this->session->userdata('user_id');
		}else{
			$data['status'] = false;
		}

		echo json_encode($data);

	}

	public function isLoggedIn(){

		if( $this->session->userdata('user_id') > 0 ){
			return true;
		}

		return false;
	}

	protected function saveSkill( $skill_id, $user_id ){

		if( $skill_id ){
			foreach ($skill_id as $key => $value) {
				# 
				$ins = array( "skill_id"=>$value,"user_id"=>$user_id );
				$this->user_skill_m->save( $ins );
			}
		}
	}

	public function hasAccess( $handler ){

		if( $this->session->userdata('role') == "Super" ){
			return true;
		}elseif( in_array($handler, $this->accessibleMenu) ){

			return true;
		}

		return false;
	}

	public function getSkills( $user_id = false ){
		$this->data['action'] = "getSkills";

		if( $this->isLoggedIn() ){

			if( $this->hasAccess( 'skill') ){

				$this->load->model('skill_m');
				$query = $this->skill_m->getByUserId( $user_id );
				if( $query->num_rows() > 0 ){
					$this->data['status'] = true;
					$this->data['data'] = $query->result();
					$this->data['message'] = "Fetched Successfully.";
				}
			}else{
				$this->data['message'] = "Permission Denied";
			}

		}else{
			$this->data['message'] = "Permission Denied";
		}

		echo json_encode($this->data);
	}

	public function updateProfile(){

		$this->data['action'] = 'updateProfile';

		if( $this->isLoggedIn() ){

			$sess_user_id = $this->session->userdata( "user_id" );
			$_POST = json_decode(file_get_contents('php://input'), true);
			$first_name = $this->input->post( 'first_name' );
			$last_name = $this->input->post( 'last_name' );

			$id = $this->input->post( 'ID' );

			$skill_id = $this->input->post( 'skillId' );

			$old_password = $this->input->post( 'old_password' );
			$new_password = $this->input->post( 'new_password' );
			if( $id == $sess_user_id ){

				$user_query = $this->user_m->get( '*', array( 'u.ID' => $id ) );

				$user_row = $user_query->row();

				$ins = array( 'first_name'=>$first_name, 'last_name'=>$last_name );

				if( $old_password == $user_row->password && !empty( $new_password )){
					$ins['password'] = $new_password;
				}

				$this->user_m->save( $ins, array( 'ID' => $id ) );

				$this->session->set_userdata( 'first_name',$first_name );
				$this->session->set_userdata( 'last_name',$last_name );

				$this->user_skill_m->delete( array( "user_id"=>$id ));
				$this->saveSkill( $skill_id, $id );

				$this->setUserAndSkillById( $id );



				$this->data['message'] = "Updated Successfully.";
			}
		}else{
			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode($this->data);
	}
	
}
