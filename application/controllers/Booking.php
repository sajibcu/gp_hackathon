<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Booking extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 user api
	 */
	 public function __construct()
	{
		parent::__construct();
		
		$this->load->model(array(
			'booking_model',
			'room_model',
		));
		isAuthonticated();

		
	}
	public function add()
	{
		$this->form_validation->set_rules('room_number', "Room Number",'required|callback_room_availability');
		//$this->form_validation->set_rules('arrival', "checkout",'required');
		//$this->form_validation->set_rules('checkout', "checkout",'required');
		$this->form_validation->set_rules('customer_id', "Customer Id",'required');
		$this->form_validation->set_rules('book_type', "Max persons",'required|max_length[32]');
		//$this->form_validation->set_rules('book_time', "book time",'required');
		

            /*-----------CREATE A NEW RECORD-----------*/
        if ($this->form_validation->run() === true) {
        	$room = $this->room_model->getByRoomNumber($this->input->post('room_number',true));
        	$postData = array(
			'room_id' 					=> $room['id'],
			'arrival' 		  			=> $this->input->post('arrival',true),
			// 'checkout' 	   				=> $this->input->post('checkout',true),
			'customer_id' 	   			=> $this->input->post('customer_id',true),
			'book_type' 	   			=> $this->input->post('book_type',true),
			'book_time' 	   			=> date('Y-m-d h:m:s'),
			); 
           
           if($this->booking_model->create($postData)){
           	$insert_id = $this->db->insert_id();
           	echo json_encode($this->booking_model->getById($insert_id));
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

	public function room_availability($room_numbr)
    { 
    	//dd($email)
    	$room = $this->room_model->getByRoomNumber($room_numbr);

        if (empty($room)) {
            $this->form_validation->set_message('room_check', 'The {field} is not found.');
            return false;
        }else if($room['locked']==1) {
        	$this->form_validation->set_message('room_check', 'Room is already booked.');
            return false;
        }else {
        	return true;
        }
    }

}
