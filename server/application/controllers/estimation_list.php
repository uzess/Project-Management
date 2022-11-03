<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimation_List extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model( "estimation_list_m" );
	}

	public function get( $id ){

		if( $this->isLoggedIn() ){

				if( $this->hasAccess("estimation") ){
					if($id){
					
						$where = array( 'estimation_id' => $id );
						$this->data['action'] = "getAll";
						$query = $this->estimation_list_m->get( '*', $where );
						if( $query->num_rows() > 0 ){
							$this->data['status'] =  true;
							$this->data['message'] = "Fetched Successfully.";
							$this->data['data'] = $query->result();
						}else{
							$this->data['message'] = "Estimation not found.";
						}
					
				}else{
					$this->setDenyMessage();
				}
				
			}else{
				$this->data['message'] = "User Not Logged In.";
			}

			echo json_encode($this->data);
		}
	}

	public function save(){

		$this->data['action'] = "save";
		if( $this->isLoggedIn() ){

			$_POST = json_decode(file_get_contents('php://input'), true);

			if( isset($_POST) ){
				$name = $this->input->post( "name" );
				$description = $this->input->post( "description" );
				$hour = $this->input->post( "hour" );
				$minute = $this->input->post( "minute" );
				$id = $this->input->post( "ID" );

				$estimation_id = $this->input->post( 'estimation_id' );

				if( empty($name) ){
					$this->data['message'] = "Required Field Missing.";
				}else{
					$user_id = $this->session->userdata( 'user_id' );
					$ins = array( 
						"name"=>$name,
						"description" => $description,
						"estimation_id" => $estimation_id,
						"hour" => $hour,
						"minute" => $minute
					);

					if( $id ){
						#Update
						if( $this->hasAccess( "estimation-update" )){
							$this->data['action'] = "update"; 
							$this->estimation_list_m->save( $ins, array( "ID" => $id ));
							$this->data['status'] = true;
							$this->data['message'] = "Updated Successfully.";
							$this->data['data'] = $this->estimation_list_m->get( '*', array( "ID" => $id ) );
						}else{
							$this->setDenyMessage();
						}
					}else{
						#Save
						if( $this->hasAccess( "estimation-create" ) ){
							$ins[ 'created_date' ] = date( 'Y-m-d' );
							$ins[ 'user_id' ] = $user_id;
							$id = $this->estimation_list_m->save( $ins );
							if( $id > 0 ){
								$this->data['status'] = true;
								$this->data['message'] = "Saved Successfully.";
								$this->data['data'] = $this->estimation_list_m->get( '*', array( "ID" => $id ) );
							}else{
								$this->data['message'] = "Save Failed.";
							}
						}else{
							$this->setDenyMessage();
						}
						
					}
				}

			}else{
				$this->data['message'] = "Invalid Data.";
			}
		}else{
			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode( $this->data );

	}

	public function delete( $id ){

		$this->data['action'] = "delete";
		if( $this->isLoggedIn() ){
			if( $this->hasAccess( "estimation-delete" ) ){

				$this->estimation_list_m->delete( array( "ID"=>$id ) );
				$this->data['data']["deletedId"] = array( $id );
				$this->data['message'] = "Deleted Successfully."; 
				$this->data['status'] = true;

			}else{
				$this->setDenyMessage();
			}
		}else{
			$this->data['message'] = "User Not Logged In.";
		}
		echo json_encode($this->data);
	}

}