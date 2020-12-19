<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {

	private $table = "bookings";

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
	public function getAllRoom() {
		return $this->db->select("r.*")
					->from($this->table." AS r")
					->where("r.locked",0)
					->get()
					->result_array();
	}
 
  
}