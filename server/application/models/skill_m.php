<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skill_m extends MY_Model {

	protected $table = "skills";
	protected $order = "DESC";
	protected $orderBy = "ID";

	public function getByUserId( $user_id ){

		$this->db->select( 's.*', false );
		$this->db->join( 'user_skill us', 'us.skill_id = s.ID', 'INNER' );
		$this->db->where( array( 'us.user_id'=>$user_id ));
		$this->db->from( $this->table.' s' );

		$query = $this->db->get();
		return $query;
	}

}