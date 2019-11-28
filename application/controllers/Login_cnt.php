<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros'));
          $this->load->model('login_mdl');
          //$this->load->helper('form');
          //$this->load->library('form_validation');
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{	
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$this->home();
		}
		else{
			$this->load->view('templates/login.php');
		}		
	}

	public function home(){
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);			
			$data['id'] = $idusuario;	
			$data['nom'] = "";
			$atenciones = $this->login_mdl->atenciones();
			foreach ($atenciones as $a) {
				$data['idcita'] = $a->idcita;
				$data['idsiniestro'] = $a->idsiniestro;
				$this->login_mdl->eliminar_cita($data);
				$this->login_mdl->eliminar_orden($data);
			}		

			/*$files = glob('uploads/*'); //obtenemos todos los nombres de los ficheros
			foreach($files as $file){
			    if(is_file($file))
			    unlink($file); //elimino el fichero
			}	*/
			$this->load->view('templates/index.php', $data);
		}
		else{
			redirect('/');
		}		
	}

 	function start_sesion()
    {
    	//load session library
		$this->load->library('session');

		$email = $_POST['email'];
		$password = $_POST['password'];

		$data = $this->login_mdl->login($email, $password);
		
		if($data){
			$this->session->set_userdata('user', $data);
			$this->home();
		}
		else{
			header('location:'.base_url().$this->index());
			$this->session->set_flashdata('error','Contraseña o usuario incorrectos. Usuario no encontrado.');
		} 
        //$this->load->view('dsb/html/index');
    }


    public function logout(){
		//load session library
		$this->load->library('session');
		$this->session->unset_userdata('user');
		redirect('/');
	}

	public function denegado($desc){
		//load session library
		$this->load->library('session');

		//restrict users to go to home if not logged in
		if($this->session->userdata('user')){
			//$this->load->view('home');

			$user = $this->session->userdata('user');
			extract($user);

			$data['id'] = "";	
			$data['nom'] = $desc;
			$this->load->view('dsb/html/denegado.php', $data);
		}
		else{
			redirect('/');
		}		
	}

}
