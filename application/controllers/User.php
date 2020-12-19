<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use \Firebase\JWT\JWT;
class User extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 user api
	 */
	 public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'user_model',
		));
	}
	public function register()
	{
		$this->form_validation->set_rules('first_name', "first name",'required|max_length[255]');
		$this->form_validation->set_rules('last_name', "last name",'required|max_length[255]');
		$this->form_validation->set_rules('email', "email",'required|valid_email|max_length[32]|callback_email_check');
		$this->form_validation->set_rules('phone', "phone",'required|numeric|max_length[11]');
		$this->form_validation->set_rules('password', "password",'required|max_length[255]');

		

            /*-----------CREATE A NEW RECORD-----------*/
        if ($this->form_validation->run() === true) {
        	$postData = array(
			'first_name' 			=> $this->input->post('first_name',true),
			'last_name' 		  	=> $this->input->post('last_name',true),
			'email' 	   			=> $this->input->post('email',true),
			'phone' 	   			=> $this->input->post('phone',true),
			'password' 	   			=> sha1($this->input->post('password',true)),
			); 
           
           if($this->user_model->create($postData)){
           	$insert_id = $this->db->insert_id();
           	echo json_encode($this->user_model->getuserById($insert_id));
           }else {
           	echo "Please try again!!";
           }
            
        }else {
        	print_r(validation_errors());
        } 
	}

	public function email_check($email)
    { 
    	//dd($email)

        if (!empty($this->user_model->getuserByEmail($email))) {
            $this->form_validation->set_message('email_check', 'The {field} field must contain a unique value.');
            return false;
        } else {
            return true;
        }
    }
}
