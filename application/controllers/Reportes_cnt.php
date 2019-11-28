<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reportes_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros'));
          $this->load->model('reporte_mdl');
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

	public function index(){
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			extract($user);	

			$data['proveedor'] = $nombre_comercial_pr;
			$data['getAtenciones'] = $this->reporte_mdl->getAtenciones($idproveedor);
			
			$this->load->view('templates/atenciones.php',$data);
		}
		else{
			$this->load->view('templates/login.php');
		}		
	}

	public function facturacion(){
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			extract($user);	
			$data['proveedor'] = $nombre_comercial_pr;			
			$this->load->view('templates/facturacion.php',$data);
		}
		else{
			$this->load->view('templates/login.php');
		}	
	}
	
}
