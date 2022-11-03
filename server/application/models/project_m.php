<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project_m extends MY_Model {

	protected $table = "projects";
	protected $order = "DESC";
	protected $orderBy = "p.ID";

	public function get( $column = "p.*" , $where = false, $limit = false, $order = false, $count = false ){

		$this->db->select( $column, false );
        $this->db->join( "estimations e", "e.ID = p.estimation_id", "INNER" );
        $this->db->join( "project_user pu", "pu.project_id = p.ID", "LEFT");
        $this->db->group_by( 'p.ID' );
        $this->db->from( $this->table . " p" );

        if( $where ){
            if( isset( $where['p.started_date']) ){
                $this->db->like( $where );
            }else{
               $this->db->where( $where );      
               
           }
       }

       if ( $limit != false ){
         $temp = explode(',',$limit); 
         $this->db->limit($temp[0],$temp[1]);
     }

     if ($order){
        $temp = explode(',',$order); 
        $this->db->order_by($temp[0],$temp[1]);
    } else{ 
        $this->db->order_by($this->orderBy, $this->order);
    }

    $query = $this->db->get();

    if( $count != false ){
     if($query)	
      return $query->num_rows();
  else
      return 0;
}

return $query;
}

public function search( $key, $count=false ){

    $neededColumn = "p.ID,p.status, p.started_date, p.completed_date, p.name, p.description,e.ID eID, e.name estimation,e.description edescription";

    $select = "p.ID";
    if($count == false){
        $select = $neededColumn;
    }

    $this->db->select($neededColumn,false);
    $this->db->order_by('p.ID', $this->order);
    $this->db->join( "estimations e", "e.ID = p.estimation_id", "left" );


    $fields = $this->db->list_fields($this->table);

    foreach ($fields as $field){
       $this->db->or_like( 'p.'.$field, $key);
   }



   if( $count == false ){
    if( empty($key) ){
        $this->db->limit( $this->settings->items_per_page, 0 );
    } 
}

$this->db->from( $this->table . " p" );

$query = $this->db->get();

if($count){
    return $query->num_rows();
}
return $query;

}

public function getPartialByStatus( $column, $condition, $limit ){

    $where = '';
    if($condition){

        foreach ($condition as $key => $value) {
                # 
            $where .= " AND ".$key." = ".$value;

        }
    }else{
        $where = '';
    }

    $sql = "SELECT tmp.* FROM ((SELECT  {$column} 
        FROM projects p 
        INNER JOIN estimations e ON e.ID = p.estimation_id
        LEFT JOIN project_user pu ON pu.project_id = p.ID
        WHERE p.status = 'postponed' {$where}
        GROUP BY p.ID
        ORDER BY p.ID DESC LIMIT 0,{$limit}
        )
UNION
(SELECT  {$column} 
    FROM projects p 
    INNER JOIN estimations e ON e.ID = p.estimation_id
    LEFT JOIN project_user pu ON pu.project_id = p.ID
    WHERE p.status = 'ongoing' {$where}
    GROUP BY p.ID
    ORDER BY p.ID DESC LIMIT 0,{$limit}
    )
UNION
(SELECT  {$column}  
    FROM projects p 
    INNER JOIN estimations e ON e.ID = p.estimation_id
    LEFT JOIN project_user pu ON pu.project_id = p.ID
    WHERE p.status = 'completed' {$where}
    GROUP BY p.ID
    ORDER BY p.ID DESC LIMIT 0,{$limit}
    )
UNION
(SELECT {$column}
    FROM projects p 
    INNER JOIN estimations e ON e.ID = p.estimation_id
    LEFT JOIN project_user pu ON pu.project_id = p.ID
    WHERE p.status = 'rejected' {$where}
    GROUP BY p.ID
    ORDER BY p.ID DESC LIMIT 0,{$limit}
    )) tmp ORDER BY ID DESC
";

$query = $this->db->query($sql);
return $query;
}

public function getDistinctYears(){

    $this->db->select( "DISTINCT YEAR(started_date) year", false );
    $this->db->from( $this->table );
    return $this->db->get();
}

public function getBetweenYears( $column, $from, $to ){

    $sql = "SELECT {$column} FROM {$this->table} p 
    INNER JOIN estimations e ON e.ID = p.estimation_id
    WHERE YEAR(p.started_date) >= ? AND YEAR(p.started_date) <= ?";

    return $this->db->query( $sql, array( $from, $to ) );

}

}