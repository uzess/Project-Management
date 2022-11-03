<?php

class MY_Model extends CI_Model
{

    public function save($data, $where=FALSE)
    {
        $now = now(); 
        if ($where == FALSE)
        { //Insert
            $this->db->set("updated_on", $now, FALSE);
            $this->db->set("created_on", $now, FALSE);
            $this->db->insert($this->table, $data);
             
            return $this->db->insert_id();
        } else
        { //Update
            $this->db->where($where);
            $this->db->set("updated_on", $now, FALSE);
            $this->db->update($this->table, $data);
            return $this->db->affected_rows();
        }

    }

    public function get($column = '*', $where = FALSE, $limit = FALSE, $order = FALSE, $count = false )
    {
        $this->db->select($column,false);

        if ($where != FALSE){
            if( isset( $where['created_date']) ){
                $this->db->like($where);
            }else{

                $this->db->where($where);
            }
        }

        if ($limit != FALSE){
            $temp = explode(',',$limit); 
            $this->db->limit($temp[0],$temp[1]);
        }
        if ($order != FALSE){
            $this->db->order_by($order);
        } else{
            $this->db->order_by('id', $this->order);
        }

        $this->db->from($this->table, false);
        $query = $this->db->get();
        if ($where){
            if(isset($where['ID'])){
                $query = $query->row();
            } 
        }

        if( $count != false ){
            if($query)  
                return $query->num_rows();
            else
                return 0;
        }

        return $query;
    }

    public function delete($where){
        $this->db->where($where);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function search( $key, $count=false ){
        
        $select = "ID";
        if($count == false){
            $select = "*";
        }

        $this->db->select('*',false);
        $this->db->order_by('id', $this->order);

        $fields = $this->db->list_fields($this->table);

        foreach ($fields as $field){
           $this->db->or_like($field, $key);
        }

        if( $count == false ){
            if( empty($key) ){
                $this->db->limit( $this->settings->items_per_page, 0 );
            } 
        }
         
        $this->db->from($this->table, false);

        $query = $this->db->get();

        if($count){
            return $query->num_rows();
        }
        return $query;

    }

}