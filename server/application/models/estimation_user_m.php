<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimation_user_m extends MY_Model {

	protected $table = "estimation_user";
	protected $order = "DESC";
	protected $orderBy = "ID";

	public function getUserByEstimationId( $user_id ){
		
		$this->db->select( 'u.*', false );
		$this->db->join( 'users u', 'u.ID = eu.user_id', 'inner' );
		$this->db->from( $this->table . ' eu' );
		$this->db->where( array( "eu.estimation_id" => $user_id ) );
		$query = $this->db->get();
		return $query;
	}
}