<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Room_model extends CI_Model {

	private $table = "rooms";

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
	public function getByRoomNumber($room_number)
	{
		return $this->db->select("r.*")
					->from($this->table." AS r")
					->where("r.room_number",$room_number)
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