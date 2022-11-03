<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skill extends MY_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->model( 'skill_m' );
	}

	public function get( $id = FALSE)
	{
		$this->data['action'] = "get";

		if( $this->isLoggedIn() ){
			if( $this->hasAccess( "skill" ) ){
				if( $id == FALSE ){
					#get all

					$this->data['action'] = "getAll";
					$query = $this->skill_m->get();
					if( $query->num_rows() > 0 )
					{
						$this->data['message'] = "Fetched Successfully.";
						$this->data['data'] = $query->result();
						$this->data['status'] = true;
					}else{
						$this->data['message'] = "Skill not found.";
					}
				
				}else{

					#getById
					$this->data['action'] = "getById";
					$row = $this->skill_m->get( '*',array( 'ID'=>$id ) );

					if( $row ){
						$this->data['message'] = "Fetched Successfully.";
						$this->data['status'] = true;
						$this->data['data'] = $row;
					}else{
						$this->data['message'] = "Skill not found.";
					}
				}
			}else{
				$this->setDenyMessage();
			}
		}else{
			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode( $this->data );

	}

	public function save()
	{
		$this->data['action'] = "save";

		if( $this->isLoggedIn() ){

			$_POST = json_decode(file_get_contents('php://input'), true);
			$id = $this->input->post('ID');
			$name =  $this->input->post('name');

			if( $_POST || !empty( $name )){

				$data = array( "name"=>$name );

				if(empty($id)){
					#save
					if($this->hasAccess( "skill-create" )){
			
						$id = $this->skill_m->save($data);

						if( $id > 0 ){
							$this->data['status'] = true;
							$this->data['message'] = "Saved Successfully.";
							$this->data['data'] = $this->skill_m->get( '*', array( 'ID'=>$id ) );
						}else{
							$this->data['message'] = "Save Failed.";
						}
						
					}else{
						$this->setDenyMessage();
					}
				}
				else{
				#update
					$this->data['action'] = "update";

					if($this->hasAccess( "skill-update" )){

						$this->skill_m->save($data,array("ID"=>$id));
						$this->data['status'] = true;
						$this->data['message'] = "Updated Successfully.";
						$this->data['data'] = $this->skill_m->get( '*', array( 'ID'=>$id ) );

					}else{
						$this->setDenyMessage();
					}

				}

			}else{

				$this->data['message'] = "Invalid Argument.";
			}
		}else{

			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode($this->data);
		
	}

		public function delete( $id = false )
		{
			$this->data['action'] = "delete";

			if( $this->isLoggedIn() ){

				if($this->hasAccess( "skill-delete" )){

					$affected = $this->skill_m->delete( array('ID'=>$id) );

					if($affected)
					{
						$this->data['message'] = "Deleted Successfully.";
						$this->data['status'] = true;
						$this->data['data']['deletedId'] = array( $id );
					}
					else
					{
						$this->data['message'] = "Delete Failed.";
					}
					
				}else{
					$this->setDenyMessage();
				}

			}else{

				$this->data['message'] = "User Not Logged In.";
			}

			echo json_encode( $this->data );
			
		}
	}
