<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model
{

	/**
	 * Tenplate: VN
	 * Model: Login Model
	 */
    
    public function __construct() {
        parent::__construct();
    }
    
    public function login_user($email,$contrasena)
    {
        $this->db->where('email',$email);
        $this->db->where('contrasena',$contrasena);
        $query = $this->db->get('usuarios');
        if($query->num_rows() == 1)
        {
            return $query->row();
        }else{
            $this->session->set_flashdata('error_login','<strong>Error: </strong>Los datos son incorrectos.');
            redirect(base_url('admin'),'refresh');
        }
    }

}
