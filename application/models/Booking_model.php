<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Booking_model extends CI_Model {

	private $table = "bookings";

	// add new customer
 
	public function create($data = [])
	{ 
		return $this->db->insert($this->table,$data);
	}

	public function update($data = [])
	{ 
		return $this->db->update($this->table,$data,array('id'=>$data['id']));
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

	public function getBookingByUserId($userId) {

		return $this->db->select("b.*")
					->from($this->table." AS b")
					->where("b.checkout IS NULL")
					->get()
					->result_array();

	}
 
  
}