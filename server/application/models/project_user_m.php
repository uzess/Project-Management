<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_user_m extends MY_Model {

	protected $table = "project_user";
	protected $order = "DESC";
	protected $orderBy = "ID";

	public function getUserByProjectId( $user_id ){
		
		$this->db->select( 'u.*', false );

		$this->db->join( 'projects p', 'p.ID = pu.project_id', 'inner' );
		$this->db->join( 'users u', 'u.ID = pu.user_id', 'inner' );
		$this->db->from( $this->table . ' pu' );
		$this->db->where( array( "pu.project_id" => $user_id ) );
		$query = $this->db->get();
		return $query;
	}
}