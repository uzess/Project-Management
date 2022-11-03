<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  public $accessibleMenu = array();
  public $data = array();
  public $setting = null;

  public function __construct()
  {
    parent::__construct();

    # Inititalizing default values for data to be returned;

    $this->data['status'] = false;
    $this->data['action'] = null;
    $this->data['data']   = null;
    $this->data['message'] = null;
    
    if( $this->isLoggedIn() ){
      $this->set_settings();
      $role_id = $this->session->userdata('role_id');
      $query = $this->menu_m->getByRoleId( 'm.*',$role_id );
      if($query){
        foreach( $query->result() as $row ){
          $this->accessibleMenu[] = trim($row->permit_code);
        }
      }

      return;
    }else{
      $this->data['message'] = "Not Logged in.";
      echo json_encode($this->data);
      exit();
    }
  }

  public function getMenu( $filter_role = false ){

    $menu = array();
    $this->makeMenu( 0, $menu, $filter_role );

    if( count($menu) > 0 ){
      $data = array(
        'status' => true,
        'data' => $menu,
        'action' => 'get_menu',
        'message' => 'Menu fetched successfully'
        );
    }else{
      $data = array(
        'status' => false,
        'data' => null,
        'action' => 'get_menu',
        'message' => 'Menus not found.'
        );
    }
    
    echo json_encode($data);
  }

  public function makeMenu( $parent_id, &$store, $filter_role  ){

    $query = $this->menu_m->get( '*',array('parent_id'=>$parent_id),false,'weight ASC' );

    if( $query ){
      $result = $query->result_array();
      foreach( $result as $key=>$row ){

        if( $filter_role == 'true' ){
          if( $row['weight'] != -1 ){
            $store[$key] = $row;
            $this->makeMenu( $row['ID'],$store[$key]['children'], $filter_role );
          }
        }else{
          $store[$key] = $row;
          $this->makeMenu( $row['ID'],$store[$key]['children'], $filter_role );
        }
        

      }
    }

  }

  public function isLoggedIn(){

    if( $this->session->userdata('user_id') > 0 ){
      return true;
    }

    return false;
  }

  public function hasAccess( $handler ){

    if( $this->session->userdata('role') == "Super" ){
      return true;
    }elseif( in_array(trim($handler), $this->accessibleMenu ) ){

      return true;
    }

    return false;
  }

  public function setDenyMessage(){
    $this->data['message'] = "Permission Denied.";
  }

  public function set_settings(){

    $this->load->model( "settings_m" );
    $this->settings = new stdClass();

    $query = $this->settings_m->get();

    if( $query->num_rows() > 0 ){
      foreach( $query->result() as $row ){
        $this->settings->{$row->key} = $row->value;
      }
    }

  }

  public function search($key=''){

    $model =  $this->router->class.'_m';
    $this->data['action'] = "search";

    if($this->isLoggedIn()){
      if( $this->hasAccess($this->router->class)){
        $query = $this->{$model}->search( $key );
        if( $query->num_rows() > 0 ){
          $this->data['status'] = true;
          $this->data['message'] = "Searched successfully.";
          $this->data['data'] = $query->result();
          $this->data['total_rows'] = $this->{$model}->search( $key, true );
        }
      }else{
        $this->setDenyMessage();
      }
    }else{
      $this->setDenyMessage();
    }

    echo json_encode($this->data);
    
  }

  public function getLimit( $page ){

    if( $page != false ){

      $limit = $this->settings->items_per_page.','.($this->settings->items_per_page*$page - $this->settings->items_per_page);
      
      if($page == 1){
        $limit = $this->settings->items_per_page.',0';
      }

      if( $page == 0 ){
        $limit = false;
      }

    }else{
      $limit = false;
    }

    return $limit;
    
  }
}
