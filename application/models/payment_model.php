<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

	private $table = "payments";

	// add new customer
 
	public function create($data = [])
	{ 
		return $this->db->insert($this->table,$data);
	}

	public function getById($id)
	{
		return $this->db->select("r.*")
					->from($this->table." AS r")
					->where("r.id",$id)
					->get()
					->row_array();
	}

	public function getAllByBookingId($bookingId)
	{
		return $this->db->select("p.*")
					->from($this->table." AS p")
					->where("p.booking_id",$bookingId)
					->get()
					->result_array();
	}
	
 
  
}