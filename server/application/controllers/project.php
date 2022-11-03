<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Controller {

	protected $neededColumn = "p.ID,p.status, p.started_date, p.completed_date, p.name, p.description,e.ID eID, e.name estimation,e.description edescription";
	
	public function __construct(){
		parent::__construct();
		$this->load->model( "project_m" );
		$this->load->model( "project_user_m" );
	}

	public function get( $id = false, $page = false, $year = false ){
		
		if( $id == 'all' ){
			$id = false;
		}

		$limit = $this->getLimit($page);

		$this->data['action'] = "get";

		if( $this->hasAccess( "project") ){

			if( $id ){
					# get by ID
				$this->data['action'] = "getById";
				$query = $this->project_m->get( $this->neededColumn,array( "p.ID"=>$id ) );

				if( $query->num_rows() > 0 ){

					$this->setData( $query );
					$this->data['message'] = "Fetched Successfully.";
					$this->data['status'] = true;

				}else{
					$this->data['message'] = "Project not found.";
				}

			}else{
					# get All

				$this->data['action']  = "getAll";
				$where = false;

				if( $year ){
					$where = array( 'p.started_date' => $year );
				}

				$query = $this->project_m->get( $this->neededColumn, $where, $limit );

				if( $query->num_rows() > 0 ){

					$this->setData($query);
					$this->data['message'] = "Fetched Successfully.";
					$this->data['status'] = true;

				}else{
					$this->data['message'] = "Project not found.";
				}
			}

		}else{

			$this->setDenyMessage();
		}

		echo json_encode($this->data);
	}

	protected function setData( $query ){

		$data =  null;
		foreach( $query->result() as $row ){

			$user_query = $this->project_user_m->getUserByProjectId( $row->ID );

			if( $user_query->num_rows() > 0 ){
				$row->users = $user_query->result();
			}else{
				$row->users = null;
			}

			$data[] = $row;

		}
		$this->data['data'] = $data;

	}

	// protected function setProject( $id ){
	// 	$row = $this->project_m->get( $this->neededColumn,array( "p.ID"=>$id ) );

	// 	if( $row ){

	// 		$query = $this->project_user_m->getUserByProjectId( $id );

	// 		if( $query->num_rows() > 0 ){
	// 			$row->users = $query->result();
	// 		}else{
	// 			$row->users = null;
	// 		}
	// 		$total_rows = $this->project_m->get( 'p.ID',false,false,false,true );
	// 		$this->data['total_rows'] = $total_rows;
	// 		$this->data['status'] = true;
	// 		$this->data['data'] = $row;

	// 	}else{

	// 		$this->data['message'] = "Project not found.";
	// 	}
	// }

	public function save(){

		$this->data['action'] = "save";
		
		$_POST = json_decode(file_get_contents('php://input'), true);
		if( $_POST ){
			$name = $this->input->post( 'name' );
			$description = $this->input->post( "description" );
			$ID = $this->input->post( "ID" );
			$eID = $this->input->post( "eID" );
			$uID = $this->input->post( "uID" );
			$status = $this->input->post( "status" );

			$started_date = $this->input->post('started_date');
			$completed_date = $this->input->post('completed_date');

			if( empty($eID) || empty( $name ) ){
				$this->data['message'] = "Required Field Missing. ";
			}else{

				$ins = array( 
					"name" => $name,
					"description" => $description,
					"started_date" => $started_date,
					"completed_date" => $completed_date,
					"estimation_id" => $eID,
					"status" => $status
					);

				if( $ID ){
						# update
					$this->data['action'] = "update";

					if( $this->hasAccess("project-update")){
						$this->project_m->save( $ins, array( "ID"=>$ID ) );

						$this->project_user_m->delete( array( "project_id"=>$ID ) );
						$this->data['message'] = "Updated Successfully.";
					}
					

				}else{
						# save
					$this->data['action'] = "save";
					if( $this->hasAccess("project-create")){
						$ID = $this->project_m->save( $ins );
						if($ID){
							$this->data['message'] = "Saved Successfully.";
						}
					}
					
				}

				$this->saveUser( $uID, $ID );

				$query = $this->project_m->get( $this->neededColumn, array( 'p.ID' => $ID ) );

				if( $query->num_rows() > 0 ){
					$this->data['status'] = true;
					$this->setData( $query );
				}

			}

		}else{
			$this->data['message'] = "Invalid Data.";
		}
		

		echo json_encode($this->data);
	}

	protected function saveUser( $uID, $project_id ){
		if( $uID ){
			foreach ($uID as $user_id) {
				$this->project_user_m->save( array( "project_id"=>$project_id, "user_id" => $user_id) );
			}
		}
	}

	public function delete( $id ){

		$this->data['action'] = "delete";
		
		if( $this->hasAccess( "project-delete" ) ){

			$this->project_user_m->delete( array( "project_id"=>$id ) );
			$this->project_m->delete( array( "ID"=>$id ) );

			$this->data['data']["deletedId"] = array( $id );
			$this->data['message'] = "Deleted Successfully."; 
			$this->data['status'] = true;

		}else{
			$this->setDenyMessage();
		}
		
		echo json_encode($this->data);
	}

	public function search($key=''){

		$this->data['action'] = "search";

		
		if( $this->hasAccess('project')){
			$query = $this->project_m->search( $key );
			if( $query->num_rows() > 0 ){
				$this->data['status'] = true;
				$this->data['message'] = "Searched successfully.";
				$this->setData( $query );
				$this->data['total_rows'] = $this->project_m->search( $key, true );
			}
		}else{
			$this->setDenyMessage();
		}
		

		echo json_encode($this->data);

	}

	protected function setByWhere( $how_many = "all", $where, $filter = false ){

		if( $how_many == "all" ){
			$limit = '0,0';
		}else{
			$limit = $how_many.',0';
		}

		$role = $this->session->userdata( 'role' );
		$role_id = $this->session->userdata( 'role_id' );


		

		$temp_where = $where;
		if( $filter ){
			$where['pu.user_id'] = $this->session->userdata( 'user_id' );
		}

		if( $this->hasAccess( 'project' )){

			$query = $this->project_m->get( $this->neededColumn, $where, $limit ,'p.ID,DESC' );
			if( $query->num_rows() > 0 ){

				$this->setData( $query );
				$this->data['status'] = true;
				$this->data['message'] = "Fetched Successfully";

				$query2 = $this->project_m->get( 'p.ID', $temp_where );
				$this->data['total_rows'] = $query2->num_rows();
			}

		}else{

			$this->setDenyMessage();
		}
		
	}

	public function getCompleted( $how_many = "all", $filter = false ){

		$this->setByWhere( $how_many, array( 'p.status' => 'completed' ), $filter);
		$this->data['action'] = "getCompletedProject";

		echo json_encode($this->data);
	}

	public function getLatest( $how_many = false, $filter = false ){

		$this->data['action'] = 'getLatest';
		$this->setByWhere( $how_many, false, $filter );

		echo json_encode($this->data);
	}

	public function getOnGoing( $how_many = "all", $filter = false ){

		$this->setByWhere( $how_many, array('p.status'=>'ongoing'), $filter );
		$this->data['action'] = "getOnGoingProject";

		echo json_encode($this->data);
	}

	public function getRejected( $how_many = "all", $filter = false ){

		$this->setByWhere( $how_many, array('p.status'=>'rejected'), $filter );
		$this->data['action'] = "getRejectedProject";

		echo json_encode($this->data);         
	}

	public function getPostponed( $how_many = "all", $filter =  false ){

		$this->setByWhere( $how_many, array('p.status'=>'postponed'), $filter );
		$this->data['action'] = "getPostponedProject";

		echo json_encode($this->data);
	}

	public function getPartialByStatus( $how_many = false, $filter = false ){

		$this->data['action'] = "getPartialByStatus";

		

		if( $this->hasAccess( 'project' )){

			$role = $this->session->userdata( 'role' );
			$role_id = $this->session->userdata( 'role_id' );
			$where = false;

			if( $filter ){
				$where['pu.user_id'] = $this->session->userdata( 'user_id' );
			}

			if(!$how_many){
				$how_many = 0;
			}

			$query = $this->project_m->getPartialByStatus( $this->neededColumn, $where, $how_many );

			if( $query->num_rows() > 0 ){

				$this->setData( $query );
				$this->data['status'] = true;
				$this->data['message'] = "Fetched Successfully";
			}

		}else{

			$this->setDenyMessage();
		}
		

		echo json_encode($this->data);
	}

	public function getDistinctYears(){

		if( $this->hasAccess( 'project' ) ){
			$query = $this->project_m->getDistinctYears();
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

		if( $this->hasAccess( 'project') ){

			$query = $this->project_m->getBetweenYears( $this->neededColumn, $from, $to );

			if( $query->num_rows() > 0 ){
				$this->data['status'] = true;
				$this->data['message'] = "Fetched Successfully.";
				$this->setData( $query );
			}else{

				$this->data['message'] = "Not Found.";
			}

		}else{
			$this->setDenyMessage();
		}

		echo json_encode($this->data);
	}
	
}