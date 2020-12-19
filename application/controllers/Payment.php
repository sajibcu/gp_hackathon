<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Payment extends CI_Controller {

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
			'payment_model',
		));
		isAuthonticated();

		
	}
	public function add()
	{
		$booking_id 	= $this->input->post('booking_id',true);
		$customer_id 	= $this->input->post('customer_id',true);
		$amount 		= $this->input->post('amount',true);

		$this->form_validation->set_rules('booking_id', "booking id",'required|callback_check_booking_details[$amount]');
		$this->form_validation->set_rules('customer_id', "customer id",'required|numeric');
		$this->form_validation->set_rules('is_checkout', "checkout ",'required');
		$this->form_validation->set_rules('amount', "Customer Id",'required');
		

            /*-----------CREATE A NEW RECORD-----------*/
        if ($this->form_validation->run() === true) {
        	// $room = $this->room_model->getByRoomNumber($this->input->post('room_number',true));
        	$postData = array(
			'booking_id' 					=> $this->input->post('booking_id',true),
			'customer_id' 		  			=> $this->input->post('customer_id',true),
			'amount' 	   					=> $this->input->post('amount',true),
			'date' 	   						=> date('Y-m-d h:m:s'),
			); 
           
           if($this->payment_model->create($postData)){
           	$insert_id = $this->db->insert_id();

           	$booking = $this->booking_model->getById($booking_id);

           	if($this->input->post('is_checkout',true)==true){

           		// update booking table
           		$bookingData = array(
           			'id' => $booking_id,
           			'checkout' => date('Y-m-d H:i:s'),
           		);
           		$this->booking_model->update($bookingData);

           		// release room
           		$roomData = array(
           			'id' => $booking['room_id'],
           			'locked' => 0,
           		);
           		$this->room_model->update($roomData);
           	}
           	echo json_encode($this->payment_model->getById($insert_id));
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

	public function check_booking_details($booking_id,$amount)
    { 
    	//dd($email)
    	$payment = $this->payment_model->getAllByBookingId($booking_id);

    	$booking = $this->booking_model->getById($booking_id);

        if (empty($booking)) {
            $this->form_validation->set_message('check_booking_details', 'The {field} is not found.');
            return false;
        }
        return true;
    }

}
