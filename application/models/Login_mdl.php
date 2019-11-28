<?php
 class Login_mdl extends CI_Model {

 function __construct(){
	parent::__construct();
	$this->load->database();
}

public function login($email, $password){
	$this->db->select("*");
	$this->db->from("usuario u");
	$this->db->join("proveedor c","u.idusuario=c.idusuario");
	$this->db->where("username",$email);
	$this->db->where("u.estado_us",1);
	$this->db->where("password",md5($password));

	$query=$this->db->get();
	return $query->row_array();
}

public function atenciones(){
	$this->db->select("idsiniestro, idcita");
	$this->db->from("siniestro");
	$this->db->where("fecha_atencion<(DATE_FORMAT(NOW(),'%Y-%m-%d')) and estado_atencion='P' and estado_siniestro=1");
$query=$this->db->get();
return $query->result();
}

public function eliminar_cita($data){
	$array = array(
		'estado_cita' => 0
 	);
$this->db->where('idcita',$data['idcita']);
return $this->db->update('cita', $array);
}

public function eliminar_orden($data){
	$array = array(
		'estado_siniestro' => 0
	);
$this->db->where('idsiniestro',$data['idsiniestro']);
return $this->db->update('siniestro', $array);
}

}
?>