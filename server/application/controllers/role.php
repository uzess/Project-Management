<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends MY_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model( 'role_m' );
	}

	public function get( $id = FALSE)
	{
		$this->data['action'] = "get";
		if( $this->isLoggedIn() ){

			if($this->hasAccess("role")){
				if( $id == FALSE ){
					$this->data['action'] = "getAll";
					$this->setRole();
				}else{

					$this->data['action'] = "getByID";
					$this->setRole( $id );
				}
		}
		else{
			$this->setDenyMessage();
		}

		}else{
			$this->data['message'] = "User not Logged In.";
		}
		
		echo json_encode($this->data);

	}

	protected function setRole( $id = false ){

		if( $id ){
			$users = $this->role_m->get( '*', array( "ID" => $id) );
			$result = array($users);
		}else{
			$users = $this->role_m->get();
			$result = $users->result();
		}

		if($users)
		{
			$this->data['status'] = true;
			$this->data['message'] = "Role fetched Successfully.";

			foreach( $result as  $u ){
				if($u->name !== "Super" ){
					$query = $this->role_menu_m->get( '*', array( "role_id"=>$u->ID ) );
					if($query){
						$role_menu = $query->result();
						$u->role_menu = $role_menu;
					}else{
						$u->role_menu = null;
					}
					$this->data['data'][] = $u;
				}

			}

		}else{
			$this->data['message'] = "Role not found.";
		}

	}

	public function save(){
		$this->data['action'] = "save";

		if( $this->isLoggedIn() ){

			$_POST = json_decode(file_get_contents('php://input'), true);
			if( $_POST ){

				$name =  $this->input->post('name');	
				$id = $this->input->post('ID');
				$ins = array( "name"=>$name );
				$menuId = $this->input->post('menuId');
				//var_dump( $menuId ); die();

				if( empty($name) ){
					$this->data['message'] = "Required Field Missing.";
				}else{

					if(empty($id)){
						#save
						if($this->hasAccess("role-create")){

							$id = $this->role_m->save($ins);

							if( $id > 0 ){

								foreach( $menuId as $menu ){
									$role_menu_data = array( "menu_id"=>$menu, "role_id"=>$id );
									$this->role_menu_m->save( $role_menu_data );
								}
								$this->setRole( $id );
								$this->data['message'] = "Saved Successfully.";
							}else{
								$this->data['message'] = "Save Failed.";
							}

						}else{
							$this->setDenyMessage();
						}

					}else{
						#update
						$this->data['action'] = "update";
						if($this->hasAccess("role-update")){

							$this->role_m->save($ins,array("ID"=>$id));
							$this->role_menu_m->delete( array( 'role_id'=>$id ) );

							foreach( $menuId as $menu ){
								$role_menu_data = array( "menu_id"=>$menu, "role_id"=>$id );
								$this->role_menu_m->save( $role_menu_data );
							}

							$this->setRole( $id );
							$this->data['message'] = "Updated Successfully.";
							
						}else{
							$this->setdenyMessage();
						}
					}
				}

			}else{
				$this->data['message'] = "Invalid Data.";
			}
			
		}else{
			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode($this->data);	
	}		
		

	public function delete( $id )
	{
		$this->data['action'] = "delete";
		if($this->isLoggedIn()){

			if($this->hasAccess("role-delete")){

				$this->role_menu_m->delete( array('role_id'=>$id) );
				$affected = $this->role_m->delete( array('ID'=>$id) );

				if($affected)
				{
					$this->data['status'] = true;
					$this->data['message'] = "Deleted Successfully.";
					$this->data['data'] = array( "deletedId" => array($id) );
				}
				else
				{
					$this->data['message'] = "Delete failed.";
				}
				
			}else{
				$this->setDenyMessage();
			}

		}else{
			$this->data['message'] = "User not logged In.";
		}

		echo json_encode($this->data);
		
	}
}
