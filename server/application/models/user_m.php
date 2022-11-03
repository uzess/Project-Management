<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends MY_Model {

	protected $table = "users";
	protected $order = "DESC";
	protected $orderBy = "ID";

	public function get( $column = '*', $where = FALSE, $limit = FALSE, $order = FALSE, $count = false ){

		$this->db->select($column,false);
        if ($where != FALSE)
        {
            $this->db->where($where);
        }
        if ($limit != FALSE)
        {
            $this->db->limit($limit);
        }
        if ($order != FALSE)
        {
            $this->db->order_by($order);
        } else
        {
            $this->db->order_by('u.ID', $this->order);
        }

        $this->db->join( 'roles ur','ur.ID = u.user_role_id','left' );

        $this->db->from($this->table.' u', false);
        
        $query = $this->db->get();
        if ($limit == 1)
        {
            $query = $query->row();
        }

        return $query;
	}
}