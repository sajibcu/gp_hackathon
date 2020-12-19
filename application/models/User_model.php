<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

	private $table = "customers";

	// add new customer
 
	public function create($data = [])
	{
		$data['registered_at'] = date('Y-m-d H:i:s');	 
		return $this->db->insert($this->table,$data);
	}

	public function getuserById($id)
	{
		return $this->db->select("u.id
								,u.first_name
								,u.last_name
								,u.email
								,u.phone
								")
					->from($this->table." AS u")
					->where("u.id",$id)
					->get()
					->row_array();
	}

	public function getuserByEmail($email)
	{
		return $this->db->select("u.id
								,u.first_name
								,u.last_name
								,u.email
								,u.phone
								")
					->from($this->table." AS u")
					->where("u.email",$email)
					->get()
					->row_array();
	}
	public function checkUserIsValid($email,$password) {

		return $this->db->select("u.id
								,u.first_name
								,u.last_name
								,u.email
								,u.phone
								")
					->from($this->table." AS u")
					->where("u.email",$email)
					->where("u.password",$password)
					->get()
					->row_array();
	}
 
  
}