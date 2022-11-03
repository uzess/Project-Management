<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model( "settings_m" );
	}

	public function get(){

		$this->data['action'] = "get";

		if( $this->isLoggedIn() ){
			if($this->hasAccess("settings")){
				$this->data['status'] = true;
				$this->data['data'] = $this->settings;
				$this->data['message'] = "Settings fetched successfully.";
			}else{
				$this->data['message'] = 'You dont have access to get Settings.';
			}
			
		}else{
			$this->setDenyMessage();
		}	

		echo json_encode($this->data);
	}

	public function save(){

		$this->data['action'] = "save";
		if( $this->isLoggedIn() ){

			if($this->hasAccess( 'settings-update' )){
				$_POST = json_decode(file_get_contents('php://input'), true);

				foreach( $_POST as $key => $val ){
					$ins = array( "value" => $val );
					$where = array( "key" => $key );
					$this->settings_m->save( $ins, $where );
				}

				$this->data['status'] = true;
				$this->set_settings();
				$this->data['data'] = $this->settings;
				$this->data['message'] = "Settings Updated successfully.";
				

			}else{
				$this->setDenyMessage();
			}
		}else{
			$this->setDenyMessage();
		}
		
		echo json_encode($this->data);
	}
}