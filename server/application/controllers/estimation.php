<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimation extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model( "estimation_m" );
		$this->load->model( "estimation_user_m" );
	}

	public function get( $id = false, $pageno = false, $year = false ){

		if( $id == "all" ){
			$id = false;
		}

		$limit = $this->getLimit( $pageno );

		if( $this->isLoggedIn() ){

			if( $this->hasAccess("estimation") ){
				if($id){
				# Get By Id
					$this->data['action'] = "getById";
					$row = $this->estimation_m->get( "*", array( 'ID'=>$id ) );
					if( $row ){

						$this->data['status'] = true;
						$this->data['message'] = "Fetched Successfully";
						$this->data['data'] = $row;

					}else{

						$this->data['status'] = false;
						$this->data['message'] = "Estimation not found.";

					}
				}else{
				# Get All estimations

					$where = false;

					if( $year ){
						$where = array( 'created_date' => $year );
					}

					$this->data['action'] = "getAll";
					$query = $this->estimation_m->get( '*', $where, $limit );
					if( $query->num_rows() > 0 ){

						$this->data['status'] =  true;
						$this->data['message'] = "Fetched Successfully.";
						$this->data['data'] = $query->result();
						

					}else{

						$this->data['message'] = "Estimation not found.";

					}
				}
			}else{
				$this->setDenyMessage();
			}
			
		}else{
			$this->data['message'] = "User Not Logged In.";
		}

		echo json_encode($this->data);
	}

	public function save(){

		$this->data['action'] = "save";
		if( $this->isLoggedIn() ){

			$_POST = json_decode(file_get_contents('php://input'), true);

			if( isset($_POST) ){
				$name = $this->input->post( "name" );
				$description = $this->input->post( "description" );
				$status = $this->input->post( "status" );
				$id = $this->input->post( "ID" );
				$uID = $this->input->post( "uID" );

				$user_id = $this->input->post( 'user_id' );

				if( empty($name) || ( $status == "" ) ){
					$this->data['message'] = "Required Field Missing.";
				}else{

					$ins = array( 
							"name"=>$name,
							"description" => $description,
							"status" => $status
							);

					if( $id ){
						#Update
						if( $this->hasAccess( "estimation-update" )){
							$this->data['action'] = "update"; 
							$this->estimation_m->save( $ins, array( "ID" => $id ));
							$this->data['status'] = true;
							$this->data['message'] = "Updated Successfully.";
							$this->data['data'] = $this->estimation_m->get( '*', array( "ID" => $id ) );
						}else{
							$this->setDenyMessage();
						}
					}else{
						#Save
						if( $this->hasAccess( "estimation-create" ) ){
							$ins[ 'user_id' ] = $this->session->userdata( 'user_id' );
							$ins[ 'created_date' ] = date( 'Y-m-d' );

							$id = $this->estimation_m->save( $ins );
							if( $id > 0 ){
								$this->data['status'] = true;
								$this->data['message'] = "Saved Successfully.";
								$this->data['data'] = $this->estimation_m->get( '*', array( "ID" => $id ) );
							}else{
								$this->data['message'] = "Save Failed.";
							}
						}else{
							$this->setDenyMessage();
						}
						
					}

					$this->saveUser( $uID, $id );
				}

			}else{
				$this->data['message'] = "Invalid Data.";
			}
		}else{
			$this->data['message'] = "User not Logged In.";
		}

		echo json_encode( $this->data );

	}

	protected function saveUser( $uID, $estimation_id ){
		if( $uID ){
			foreach ($uID as $user_id) {
				$this->estimation_user_m->save( array( "estimation_id"=>$estimation_id, "user_id" => $user_id) );
			}
		}
	}

	public function delete( $id ){

		$this->data['action'] = "delete";
		if( $this->isLoggedIn() ){
			if( $this->hasAccess( "estimation-delete" ) ){
				$this->estimation_user_m->delete( array( "estimation_id"=>$id ) );
				$this->estimation_m->delete( array( "ID"=>$id ) );
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

	public function getDistinctYears(){

		if( $this->hasAccess( 'estimation' ) ){
			$query = $this->estimation_m->getDistinctYears();
			if( $query->num_rows() > 0 ){
				$this->data['status'] = true;
				$this->data['data'] = $query->result();
				$this->data['message'] = "Fetched Successfully.";
			}else{
				$this->data['message'] = "Not Found.";
			}
		}else{
			$this->setDenyMessage();
		}

		

		$this->data['action'] = "getDistinctYears";


		echo json_encode($this->data);
	}

		public function getBetweenYears( $from, $to ){
		$this->data['action'] = 'getBetweenYears';

		if( $this->hasAccess( 'estimation') ){

			$query = $this->estimation_m->getBetweenYears( '*', $from, $to );

			if( $query->num_rows() > 0 ){
				$this->data['status'] = true;
				$this->data['message'] = "Fetched Successfully.";
				$this->data['data'] = $query->result();
			}else{

				$this->data['message'] = "Not Found.";
			}

		}else{
			$this->setDenyMessage();
		}

		echo json_encode($this->data);
	}

}