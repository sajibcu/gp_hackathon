<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Room extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 user api
	 */
	 public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'room_model',
			'user_model',
		));
		isAuthonticated();

		
	}
	public function add()
	{
		$this->form_validation->set_rules('room_number', "Room Number",'required');
		$this->form_validation->set_rules('price', "Price",'required|numeric');
		$this->form_validation->set_rules('max_persons', "Max persons",'required|numeric|max_length[32]');
		$this->form_validation->set_rules('room_type', "room type",'required');
		

            /*-----------CREATE A NEW RECORD-----------*/
        if ($this->form_validation->run() === true) {
        	$postData = array(
			'room_number' 				=> $this->input->post('room_number',true),
			'price' 		  			=> $this->input->post('price',true),
			'max_persons' 	   			=> $this->input->post('max_persons',true),
			'room_type' 	   			=> $this->input->post('room_type',true),
			'locked' 	   				=> 0,
			); 
           
           if($this->room_model->create($postData)){
           	$insert_id = $this->db->insert_id();
           	echo json_encode($this->room_model->getById($insert_id));
           }else {
           	echo "Please try again!!";
           }
            
        }else {
        	print_r(validation_errors());
        } 
	}

	public function getAllRoom() {
		echo json_encode($this->room_model->getAllRoom());
	}
}
