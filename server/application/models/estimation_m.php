<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estimation_m extends MY_Model {

	protected $table = "estimations";
	protected $order = "DESC";
	protected $orderBy = "ID";

	public function getDistinctYears(){

		$this->db->select( "DISTINCT YEAR(created_date) year", false );
		$this->db->from( $this->table );
		return $this->db->get();
	}

	public function getBetweenYears( $column, $from, $to ){

	    $sql = "SELECT {$column} FROM {$this->table} 
	    WHERE YEAR(created_date) >= ? AND YEAR(created_date) <= ?";

	    return $this->db->query( $sql, array( $from, $to ) );

	}


}