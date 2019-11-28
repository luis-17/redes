<?php
 class Reporte_mdl extends CI_Model {

 function __construct(){
	parent::__construct();
	$this->load->database();
}

	function getAtenciones($id){
		$this->db->select("a.aseg_id,  s.idsiniestro, estado_atencion, num_orden_atencion, fecha_atencion, aseg_numDoc, concat(coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''),' ',coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,'')) as afiliado, nombre_plan, nombre_esp, fase_atencion");
		$this->db->from("siniestro s");
		$this->db->join("asegurado a","a.aseg_id=s.idasegurado");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan p","c.plan_id=p.idplan");
		$this->db->join("especialidad e","e.idespecialidad=s.idespecialidad");
		$this->db->where("s.idproveedor",$id);
		$this->db->where("estado_siniestro",1);
		$this->db->order_by("fecha_atencion","desc");
		$query = $this->db->get();
		return $query->result();
	}

}
?>