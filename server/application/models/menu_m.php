<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_m extends MY_Model {

	protected $table = "menu";
	protected $order = "DESC";
	protected $orderBy = "ID";

	public function getByRoleId( $column = 'm.*', $role_id ){

		$this->db->select($column,false);
		$this->db->join("role_menu rm","m.ID = rm.menu_id","INNER");
		$this->db->from("menu m");
		$this->db->where(array("rm.role_id"=>$role_id));
		$query = $this->db->get();
		return $query;
	}
}