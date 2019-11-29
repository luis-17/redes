<?php
 class Siniestro_mdl extends CI_Model {

 function __construct(){
	parent::__construct();
	$this->load->database();
}

	function getPlanes($dni){
		$query = $this->db->query("select ca.certase_id, c.cert_num, p.nombre_plan, ca.cert_id,	ca.aseg_id,	ca.cert_estado,	p.dias_carencia, p.dias_mora, p.dias_atencion, cert_upProv,	DATE_ADD(
		ca.cert_iniVig, INTERVAL p.dias_carencia DAY) AS cert_iniVig, DATE_ADD(ca.cert_finVig, INTERVAL p.dias_mora DAY) AS cert_finVig, ca.cert_finVig AS fin_vig, (SELECT MAX(fecha_atencion) AS ultima_atencion FROM	siniestro sin INNER JOIN producto pr ON pr.idespecialidad = sin.idespecialidad WHERE sin.idcertificado = c.cert_id AND sin.idasegurado = a.aseg_id AND estado_siniestro = 1
		AND estado_atencion = 'O' AND pr.idvariableplan = 1	ORDER BY idsiniestro DESC LIMIT 1) AS ultima_atencion
			FROM
				plan p
			JOIN certificado c ON p.idplan = c.plan_id
			JOIN certificado_asegurado ca ON ca.cert_id = c.cert_id
			JOIN asegurado a ON a.aseg_id = ca.aseg_id
			WHERE
				aseg_numDoc = $dni
				and (p.flg_tipo in (1,3) or (select count(idsiniestro) from siniestro s inner join certificado_asegurado ca on s.idasegurado=ca.aseg_id inner join asegurado a on a.aseg_id=ca.aseg_id
			inner join certificado c on c.cert_id=ca.cert_id inner join plan p on p.idplan=c.plan_id where a.aseg_numDoc=$dni and p.flg_tipo=2 and s.estado_siniestro=1 and fecha_atencion=date_format(now(),'%Y-%m-%d'))>0)
			ORDER BY
			ca.cert_estado ASC,
			c.cert_id DESC");
		return $query->result();
	}	

	function getAtencion($dni){
		$this->db->select("idsiniestro, certase_id, observaciones_cita");
		$this->db->from("siniestro s");
		$this->db->join("cita c","s.idcita=c.idcita","left");
		$this->db->join("asegurado a","a.aseg_id=s.idasegurado");
		$this->db->join("certificado_asegurado ca","ca.aseg_id=a.aseg_id and s.idcertificado=ca.cert_id");
		$this->db->where("aseg_numDoc=$dni and fecha_atencion=DATE_FORMAT(now(),'%Y-%m-%d') and estado_siniestro=1");
		$this->db->order_by("idsiniestro","desc");
		$this->db->limit(1);
		$query =  $this->db->get();
		return $query->result();
	}

	function getAfiliado($dni){
		$this->db->select("aseg_id, aseg_numDoc, concat(coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,''),' ',coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''))as afiliado, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2))as aseg_fechNac, aseg_telf, aseg_direcc");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito=SUBSTR(aseg_ubg,8,2) )as distrito");
		$this->db->from("asegurado");
		$this->db->where("aseg_numDoc",$dni);
		$query = $this->db->get();
		return $query->result();
	}

	function getAfiliadoId($id){
		$this->db->select("a.aseg_id, aseg_numDoc, concat(coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,''),' ',coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''))as afiliado, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2))as aseg_fechNac, aseg_telf, aseg_direcc");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito=SUBSTR(aseg_ubg,8,2) )as distrito");
		$this->db->select("(select MAX(fecha_atencion) AS ultima_atencion FROM siniestro sin WHERE sin.idcertificado = ca.cert_id and estado_siniestro=1 and estado_atencion='O' order by idsiniestro desc LIMIT 1) AS ultima_atencion");
		$this->db->from("asegurado a");
		$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
		$this->db->where("certase_id",$id);
		$query = $this->db->get();
		return $query->result();
	}

	function getAfiliadoId2($id){
		$this->db->select("a.aseg_id, aseg_numDoc, concat(coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,''),' ',coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''))as afiliado, concat(SUBSTR(aseg_fechNac,1,4),'-',SUBSTR(aseg_fechNac,5,2),'-',SUBSTR(aseg_fechNac,7,2))as aseg_fechNac, aseg_telf, aseg_direcc, ultima_atencion, estado_atencion, coalesce(observaciones_cita,'') as observaciones_cita, idcita");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia='00' and iddistrito='00' )as departamento");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito='00' )as provincia");
		$this->db->select("(select descripcion_ubig from ubigeo where iddepartamento=SUBSTR(aseg_ubg,4,2) and idprovincia=SUBSTR(aseg_ubg,6,2) and iddistrito=SUBSTR(aseg_ubg,8,2) )as distrito");
		//$this->db->select("(select MAX(fecha_atencion) AS ultima_atencion FROM siniestro sin WHERE sin.idcertificado = ca.cert_id and estado_siniestro=1 order by idsiniestro desc LIMIT 1) AS ultima_atencion");
		$this->db->from("asegurado a");		
		$this->db->join("certificado_asegurado ca","a.aseg_id=ca.aseg_id");
		$this->db->join("(SELECT idsiniestro, c.idcita, fecha_atencion as ultima_atencion, idcertificado, s.idasegurado,	estado_atencion, observaciones_cita	FROM siniestro s LEFT JOIN cita c ON s.idcita = c.idcita INNER JOIN certificado_asegurado ca on ca.aseg_id=s.idasegurado and ca.cert_id=s.idcertificado inner join producto pr on pr.idespecialidad =s.idespecialidad WHERE estado_siniestro = 1 and pr.idvariableplan=1 and certase_id=$id order by fecha_atencion desc limit 1)x","x.idasegurado=ca.aseg_id and x.idcertificado=ca.cert_id","left");
		$this->db->where("certase_id",$id);
		$query = $this->db->get();
		return $query->result();
	}

	function getPlan($id){
		$this->db->select("ca.certase_id, ca.cert_id, ca.aseg_id, nombre_comercial_cli, nombre_plan, ca.cert_estado, p.dias_carencia, p.dias_mora, p.dias_atencion, cert_upProv,DATE_ADD(c.cert_iniVig, INTERVAL p.dias_carencia DAY) as cert_iniVig, DATE_ADD(c.cert_finVig, INTERVAL p.dias_mora DAY) AS cert_finVig");
		$this->db->from("plan p");
		$this->db->join("cliente_empresa ce","ce.idclienteempresa=p.idclienteempresa");
		$this->db->join("certificado c","c.plan_id=p.idplan");
		$this->db->join("certificado_asegurado ca","c.cert_id=ca.cert_id");
		$this->db->where("certase_id",$id);
		$query = $this->db->get();
		return $query->result();
	}

	function getAtenciones($id,$idusuario){
		$this->db->select("*");
		$this->db->from("siniestro s");
		$this->db->join("certificado_asegurado ca","s.idcertificado=ca.cert_id and s.idasegurado=ca.aseg_id");
		$this->db->join("proveedor pr", "pr.idproveedor=s.idproveedor");
		$this->db->join("producto pd","pd.idespecialidad=s.idespecialidad");
		$this->db->where("certase_id",$id);
		$this->db->where("pr.idusuario",$idusuario);
		$this->db->where("estado_siniestro",1);
		$this->db->order_by("idsiniestro","desc");
		$this->db->limit(15);
		$query = $this->db->get();
		return $query->result();
	}

	function getCoberturas($id){
		$this->db->select("dp.idplandetalle, dp.idplan, dp.idvariableplan, dp.valor_detalle, dp.simbolo_detalle, dp.texto_web, dp.visible, dp.estado_pd, dp.flg_liquidacion, dp.tiempo, dp.num_eventos, UPPER(vp.nombre_var) as nombre_var, coalesce(idperiodo,0) as idperiodo, coalesce(vez_actual,0) as vez_actual, coalesce(total_vez,0) as total_vez, cobertura, tiempo, num_eventos, dp.iniVig, dp.finVig,  coalesce(bloqueos,'-') as bloqueos, vp.tipo_var");
		$this->db->from("plan_detalle dp");
		$this->db->join("variable_plan vp","dp.idvariableplan = vp.idvariableplan");
		$this->db->join("(select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
 			where pc.estado=1 group by idplandetalle)a",'a.idplandetalle=dp.idplandetalle');
		$this->db->join("certificado c","c.plan_id=dp.idplan");
		$this->db->join("certificado_asegurado ca","c.cert_id=ca.cert_id");
		$this->db->join("periodo_evento pe","ca.certase_id=pe.certase_id and dp.idplandetalle=pe.idplandetalle","left");
		$this->db->join("(select idplandetalle_bloquea, GROUP_CONCAT(concat(' ', vp2.nombre_var)) as bloqueos from plan_detalle_bloqueo pb 
						inner join plan_detalle  pd2 on pb.idplandetalle_bloqueado=pd2.idplandetalle
						inner join variable_plan vp2 on pd2.idvariableplan=vp2.idvariableplan
						GROUP BY idplandetalle_bloquea)b","b.idplandetalle_bloquea=dp.idplandetalle","left");
		$this->db->where("ca.certase_id=$id and visible = 1 and dp.estado_pd=1");
		$this->db->order_by("vp.idvariableplan","asc");
		$this->db->order_by("dp.idplandetalle","asc");
		$query = $this->db->get();
		return $query->result();
	}

	function getCoberturas2($id){
		$this->db->select("dp.idplandetalle, UPPER(vp.nombre_var) as nombre_var,texto_web");
		$this->db->from("plan_detalle dp");
		$this->db->join("variable_plan vp","dp.idvariableplan = vp.idvariableplan");
		$this->db->join("certificado c","c.plan_id=dp.idplan");
		$this->db->join("certificado_asegurado ca","c.cert_id=ca.cert_id");
		$this->db->where("ca.certase_id=$id and visible = 1 and tiempo='' and idplandetalle not in (select idplandetalle from plan_coaseguro where estado=1) and dp.estado_pd=1");
		$this->db->order_by("dp.idplandetalle","asc");
		$query = $this->db->get();
		return $query->result();
	}

	function getSiniestro($data){
		$query = $this->db->query("select idsiniestro, s.idproveedor, nombre_comercial_pr, estado_atencion, s.idespecialidad, idvariableplan from siniestro s 
									join proveedor pr on s.idproveedor=pr.idproveedor
									inner join producto  p on p.idespecialidad=s.idespecialidad
									where idcertificado=".$data['cert_id']." and idasegurado=".$data['aseg_id']." and fecha_atencion='".$data['hoy']."' and estado_siniestro=1");
		return $query->result();
	}

	function getSiniestro2($data){
		$query = $this->db->query("select idsiniestro, s.idproveedor, nombre_comercial_pr, estado_atencion, idespecialidad from siniestro s 
									join proveedor pr on s.idproveedor=pr.idproveedor
									where idcertificado=".$data['cert_id']." and idasegurado=".$data['aseg_id']." and fecha_atencion='".$data['hoy']."' and estado_siniestro=1");
		return $query->row_array();
	}

	function getHistorial($data){
		$this->db->select("idhistoria");
		$this->db->from("historia");
		$this->db->where("idasegurado",$data['aseg_id']);
		$query = $this->db->get();
		return $query->result();
	}

	function inHistoria($data){
		$array = array('idasegurado' => $data['aseg_id'] );
		$this->db->insert("historia", $array);
	}

	function inSiniestro($data){
		$array = array
		(
			'idasegurado' => $data['aseg_id'], 
			'idcertificado' => $data['cert_id'],
			'idproveedor' => $data['idproveedor'],
			'fecha_atencion' => $data['hoy'],
			'idhistoria' => $data['idhistoria'],
			'idareahospitalaria' => 1,
			'idespecialidad' =>$data['especialidad'],
			'num_orden_atencion' => $data['num'],
			'fase_atencion' =>0
		);
		$this->db->insert("siniestro",$array);
	}

	function upSiniestro($data){
		$array = array
		(
			'estado_atencion' => 'O' 
		);
		$this->db->where("idsiniestro", $data['idsiniestro']);
		$this->db->update("siniestro",$array);
	}

	function numEventos($data){
		$this->db->select("num_eventos, tiempo, tipo_evento");
		$this->db->from("plan_detalle");
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$query = $this->db->get();
		return $query->result();
	}

	function periodo($data){
		$this->db->select("idperiodo, case when finVig>now() then 1 else 0 end as estado, iniVig, finVig, vez_actual");
		$this->db->from("periodo_evento pe");
		$this->db->join("certificado_asegurado ca","ca.certase_id=pe.certase_id");
		$this->db->join("certificado c", "c.cert_id=ca.cert_id");
		$this->db->where("pe.certase_id",$data['certase_id']);
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$query = $this->db->get();
		return $query->result();
	}

	function periodo2($data){
		$this->db->select("idperiodo, case when finVig>now() then 1 else 0 end as estado, iniVig, finVig, vez_actual");
		$this->db->from("periodo_evento pe");
		$this->db->join("certificado c", "c.cert_id=pe.cert_id");
		$this->db->where("pe.cert_id",$data['cert_id']);
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$this->db->order_by("idperiodo","desc");
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->result();
	}

	function getCertificado($data){
		$this->db->select("cert_iniVig");
		$this->db->from("certificado");
		$this->db->where("cert_id",$data['cert_id']);
		$query = $this->db->get();
		return $query->result();
	}

	function inPeriodo($data){
		$array = array
		(
			'certase_id' => $data['certase_id'],
			'idplandetalle' => $data['idplandetalle'],
			'iniVig' => $data['ini'],
			'finVig' => $data['fin'],
			'total_vez' => $data['num_eventos'],
			'vez_actual' =>0
		);
		$this->db->insert("periodo_evento",$array);
	}

	function inPeriodo2($data){
		$array = array
		(
			'cert_id' => $data['cert_id'],
			'idplandetalle' => $data['idplandetalle'],
			'iniVig' => $data['ini'],
			'finVig' => $data['fin'],
			'total_vez' => $data['num_eventos'],
			'vez_actual' =>0
		);
		$this->db->insert("periodo_evento",$array);
	}

	function upVez_evento($data){
		$array = array
		(
			'vez_actual' =>$data['vez_actual']
		);
		$this->db->where("idperiodo",$data['idperiodo']);
		$this->db->update("periodo_evento",$array);
	}

	function upPeriodo($data){
		$array = array
		(
			'iniVig' => $data['ini'],
			'finVig' => $data['fin'],
			'vez_actual' => 0
		);
		$this->db->where("idperiodo",$data['idperiodo']);
		$this->db->update("periodo_evento",$array);
	}	

	function inSiniestroDetalle($data){
		$array = array
		(
			'idplandetalle' => $data['idplandetalle'],
			'idsiniestro' => $data['idsiniestro'],
			'vez_evento' => $data['vez_actual']
		);
		$this->db->insert("siniestro_detalle",$array);
	}

	function upPeriodo_evento($data){
		$array = array('vez_actual' => $data['vez_actual']);
		$this->db->where("idperiodo",$data['idperiodo']);
		$this->db->update("periodo_evento",$array);
	}

	function num_orden_atencion(){
		$this->db->select("lpad((num_orden_atencion +1),6,'0') as num_orden_atencion");
		$this->db->from("siniestro");
		$this->db->order_by("idsiniestro","desc");
		$this->db->limit(1);
		$num = $this->db->get();
		return $num->result();
	}

	function vez_Evento($data){
		$this->db->select("case when vez_evento is null then 1 else (vez_evento+1) end vez_evento");
		$this->db->from("siniestro_detalle");
		$this->db->where("idplandetalle",$data['idplandetalle']);
		$this->db->where("idsiniestro",$data['idsiniestro']);
		$query = $this->db->get();
		return $query->result();
	}

	function OrdenAtencion($data){
		$this->db->select("num_orden_atencion, nombre_plan, fecha_atencion, descripcion_prod, s.idespecialidad, pr.idproducto");
		$this->db->from("siniestro s");
		$this->db->join("producto pr","s.idespecialidad=pr.idespecialidad");
		$this->db->join("certificado c","c.cert_id=s.idcertificado");
		$this->db->join("plan p","p.idplan=c.plan_id");
		$this->db->where("s.idsiniestro",$data['idsiniestro']);
		$query = $this->db->get();
		return $query->result();
	}

	function coberturas($data){
		$query  = $this->db->query("select vp.idvariableplan, pd.idplandetalle, UPPER(nombre_var) as nombre_var, case when a.cobertura is null then texto_web else concat(texto_web,', ',a.cobertura) end as 										texto_web, num_eventos, tiempo
								from  certificado c 
								inner join plan_detalle pd on pd.idplan=c.plan_id
								inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan
								left join (select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as cobertura, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
 								where pc.estado=1 group by idplandetalle)a on a.idplandetalle=pd.idplandetalle
								where cert_id=(select idcertificado from siniestro where idsiniestro=".$data['idsiniestro'].") and estado_pd=1 and visible=1
								and pd.idplandetalle not in (select idplandetalle from plan_detalle where idvariableplan in (select idvariableplan from variable_plan where tipo_var=1) and  idplandetalle NOT IN(select distinct sd.idplandetalle from siniestro_detalle sd inner join plan_detalle pd on sd.idplandetalle=pd.idplandetalle where pd.idvariableplan in(select idvariableplan from variable_plan where tipo_var=1) and idsiniestro=".$data['idsiniestro']."))
								and pd.idplandetalle not in (select idplandetalle_bloqueado from plan_detalle_bloqueo where idplandetalle_bloquea in (select sd.idplandetalle from siniestro_detalle sd inner join plan_detalle pd on sd.idplandetalle=pd.idplandetalle where pd.idvariableplan in (select idvariableplan from variable_plan where tipo_var=1) and idsiniestro=".$data['idsiniestro']."))");
		return $query->result();
	}

	function getMedicamentosBloqueados($data){
		$query = $this->db->query("select idvariableplan from plan_detalle_bloqueo b 
									inner join plan_detalle pd on b.idplandetalle_bloqueado=pd.idplandetalle
									where idplandetalle_bloquea=".$data['idplandetalle']." and idvariableplan=2");
		return $query->result();
	}

	function getEspecialidad($data){
		$this->db->select("idespecialidad, descripcion_prod");
		$this->db->from("producto pr");
		$this->db->join("producto_detalle pd","pr.idproducto=pd.idproducto");
		$this->db->join("plan_detalle dp","pd.idplandetalle=dp.idplandetalle");
		$this->db->where("pd.idplandetalle",$data['idplandetalle']);
		$query = $this->db->get();
		return $query->result();
	}

	function getNombreCobertura($id){
		$this->db->select("UPPER(nombre_var) as nombre2, nombre_var, valor_detalle, simbolo_detalle, tiempo, num_eventos, coaseguro");
		$this->db->from("plan_detalle pd");
		$this->db->join("variable_plan vp","pd.idvariableplan=vp.idvariableplan");
		$this->db->join("(select GROUP_CONCAT(concat(' ',descripcion,' ',valor)) as coaseguro, idplandetalle from plan_coaseguro pc inner join operador o on pc.idoperador=o.idoperador
 			where pc.estado=1 and idplandetalle=$id group by idplandetalle)a",'a.idplandetalle=pd.idplandetalle');
		$this->db->where("pd.idplandetalle",$id);
		$query = $this->db->get();
		return $query->row_array();
	}

	function getDetalleProductos($id){
		$this->db->select("pr.idproducto, descripcion_prod, idespecialidad");
		$this->db->from("producto pr");
		$this->db->join("producto_detalle dp","pr.idproducto=dp.idproducto");
		$this->db->join("plan_detalle p","p.idplandetalle=dp.idplandetalle");
		$this->db->where("p.idplandetalle","$id");
		$this->db->order_by("descripcion_prod");
		$query = $this->db->get();
		return $query->result();
	}

	function getCertificadoAsegurado($certase_id){
		$query = $this->db->query("select ca.cert_id, ca.aseg_id, aseg_telf, c.cert_iniVig, c.cert_finVig from certificado_asegurado ca inner join certificado c on c.cert_id=ca.cert_id inner join asegurado a on ca.aseg_id=a.aseg_id  where certase_id=$certase_id");
		return $query->result();
	}

	function getCertificadoAsegurado2($certase_id){
		$query = $this->db->query("select ca.cert_id, ca.aseg_id, aseg_telf, c.cert_iniVig, c.cert_finVig from certificado_asegurado ca inner join certificado c on c.cert_id=ca.cert_id inner join asegurado a on ca.aseg_id=a.aseg_id  where certase_id=$certase_id");
		return $query->row_array();
	}

	function getSiniestroDetalle($data){
		$query = $this->db->query("select * from siniestro_detalle where idplandetalle=".$data['idplandetalle']." and idsiniestro=".$data['idsiniestro']);
		return $query->row_array();
	}

	function getTiempo($idplandetalle){
		$query = $this->db->query("select coalesce(tiempo,0) as tiempo from plan_detalle where idplandetalle=$idplandetalle");
		return $query->row_array();
	}

	function getTipoConteo($data){
		$query = $this->db->query("select tipo_evento from plan_detalle where idplandetalle=".$data['idplandetalle']);
		return $query->row_array();
	}
 
	function getPeriodoEvento($data){
		$query = $this->db->query("select * from periodo_evento pe 
									inner join plan_detalle pd on pe.idplandetalle=pd.idplandetalle
									where pe.idplandetalle=".$data['idplandetalle']." and case when tipo_evento=1 then certase_id=".$data['certase_id']." else cert_id=(select cert_id from certificado_asegurado where certase_id=".$data['certase_id'].") end");
		return $query->row_array();
	}

	function inSiniestroDiagnostico($data){
		$array = array
		(
			'idsiniestro' => $data['idsiniestro'],
			'tipo_diagnostico' => 1,
			'es_principal' => 1,
			'estado_sdi' => 1,
			'dianostico_temp' => $data['dianostico_temp']
		);
		$this->db->insert("siniestro_diagnostico",$array);
	}

	function inTratamiento($data){
		$array = array
		(
			'idsiniestrodiagnostico' => $data['idsiniestrodiagnostico'],
			'idmedicamento' => $data['idmedicamento'],
			'estado_trat' => 1,
			'tipo_tratamiento'=>1
		);
		$this->db->insert("tratamiento",$array);
	}

	function getSiniestroCertificado($idsiniestro){
		$query = $this->db->query("select certase_id, descripcion_prod, nombre_plan, num_orden_atencion, case when fecha_atencion_act is null then fecha_atencion else fecha_atencion_act end as fecha_atencion, aseg_numDoc, concat(coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''),' ',coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,'')) as afiliado, concat(coalesce(aseg_nom1,''),' ',coalesce(aseg_nom2,''),' ',coalesce(aseg_ape1,''),' ',coalesce(aseg_ape2,''))as afiliado, concat(SUBSTR(aseg_fechNac,7,2),'/',SUBSTR(aseg_fechNac,5,2),'/',SUBSTR(aseg_fechNac,1,4))as aseg_fechNac, a.aseg_id
									from siniestro s 
									inner join certificado_asegurado ca on s.idcertificado=ca.cert_id and s.idasegurado=ca.aseg_id 
									inner join asegurado a on a.aseg_id=s.idasegurado
									inner join producto pr on pr.idespecialidad=s.idespecialidad
									inner join certificado c on s.idcertificado=c.cert_id
									inner join plan pl on pl.idplan = c.plan_id
									where idsiniestro=$idsiniestro");
		return $query->row_array();
	}

	function getCobertura($idplandetalle){
		$query = $this->db->query("select pd.idvariableplan, nombre_plan, UPPER(nombre_var) as nombre_var, concat(texto_web,', ',op.descripcion,valor_detalle) as descripcion, num_eventos, tiempo
									from plan_detalle pd
									inner join plan p on p.idplan=pd.idplan
									inner join variable_plan vp on pd.idvariableplan=vp.idvariableplan
									inner join operador op on op.idoperador=pd.simbolo_detalle where pd.idplandetalle=$idplandetalle");
		return $query->row_array();
	}

	function getMedicamentos($idsiniestro){
		$query = $this->db->query("select dianostico_temp, concat(nombre_med,' / ',presentacion_med) as nombre_med
									from siniestro_diagnostico sd 
									inner join tratamiento t on sd.idsiniestrodiagnostico=t.idsiniestrodiagnostico
									inner join medicamento m on t.idmedicamento=m.idmedicamento
									where sd.idsiniestro = $idsiniestro");
		return $query->result();
	}

	function getDiagnostico($idsiniestro){
		$query = $this->db->query("select idsiniestrodiagnostico, dianostico_temp from siniestro_diagnostico where idsiniestro=$idsiniestro and es_principal=1");
		return $query->row_array();
	}

	function inSinAnalisis($data){
		$array = array
		(
			'idsiniestro' => $data['idsiniestro'],
			'idproducto' => $data['idproducto'],
			'si_cubre' => 1,
			'estado_sian' => 1
		);
		$this->db->insert("siniestro_analisis",$array);
	}

	function detProducto($data){
		$query = $this->db->query("select descripcion_prod from siniestro_analisis sa inner join producto pr on sa.idproducto=pr.idproducto inner join producto_detalle pd on pr.idproducto=pd.idproducto where idsiniestro=".$data['idsiniestro']." and pd.idplandetalle=".$data['idplandetalle']);
		return $query->result();
	}

	function upTelf($data){
		$array = array('aseg_telf' => $data['aseg_telf'] );
		$this->db->where('aseg_id',$data['aseg_id']);
		$this->db->update("asegurado",$array);
	}

	function getCita($idcita){
		$query = $this->db->query("select observaciones_cita, concat(coalesce(nombres_col),' ',coalesce(ap_paterno_col,''),' ',coalesce(ap_materno_col,'')) as colaborador from cita c inner join colaborador u on c.idusuario=u.idusuario where idcita=$idcita");
		return $query->row_array();
	}

	function getCoberturas_Periodos($data){
		$query = $this->db->query("select * from plan_detalle pd inner join variable_plan vp on vp.idvariableplan=pd.idvariableplan where idplan=(select plan_id from certificado where cert_id=".$data['cert_id'].")");
		return $query->result();
	}

	function getTriajeMedicamentos($data){
		$query = $this->db->query("select * from siniestro s inner join producto p on p.idespecialidad=s.idespecialidad where idsiniestro=".$data['idsiniestro']);
		return $query->row_array();
	}

	function inTriaje($data){
		$array = array
		(
			'idasegurado' => $data['aseg_id'],
			'idsiniestro' => $data['idsiniestro'],
			'motivo_consulta' => $data['motivo'],
			'presion_arterial_mm' => $data['pa'],
			'frec_cardiaca' => $data['fc'],
			'frec_respiratoria' => $data['fr'],
			'peso' => $data['peso'],
			'talla' => $data['talla'],
			'estado_cabeza' => $data['cabeza'],
			'piel_faneras' => $data['piel_faneras'],
			'cv_ruido_cardiaco' => $data['cv_cr'],
			'tp_murmullo_vesicular' => $data['tp_mv'],
			'estado_abdomen' => $data['abdomen'],
			'ruido_hidroaereo' => $data['rha'],
			'estado_neurologico' => $data['neuro'],
			'estado_osteomuscular' => $data['osteomuscular'],
			'gu_puno_percusion_lumbar' => $data['gu_ppl'],
			'gu_puntos_reno_uretelares' => $data['gu_pru']
		);
		$this->db->insert("triaje", $array);
	}

	function upSiniestroTriaje($data){
		$array = array('est_tr' => 1);
		$this->db->where("idsiniestro",$data['idsiniestro']);
		$this->db->update("siniestro",$array);
	}

	function getSiniestroDiagnostico($data){
		$query = $this->db->query("select sd.idsiniestrodiagnostico, dianostico_temp, t.idtratamiento, concat(nombre_med,case when presentacion_med='' then '' else concat('/', presentacion_med) end) as medicamento from siniestro_diagnostico sd inner join tratamiento t  on sd.idsiniestrodiagnostico=t.idsiniestrodiagnostico	inner join medicamento m on m.idmedicamento=t.idmedicamento where sd.idsiniestro=".$data['idsiniestro']);
		return $query->result();
	}

	function upTratamiento($data){
		$array = array
		(
			'cantidad_trat' => $data['cantidad'],
			'dosis_trat' => $data['dosis'] 
		);
		$this->db->where('idtratamiento',$data['idtratamiento']);
		$this->db->update('tratamiento',$array);
	}

	function upSiniestroMed($data){
		$array = array('est_md' => 1);
		$this->db->where("idsiniestro",$data['idsiniestro']);
		$this->db->update("siniestro",$array);
	}

	function getIdDetPlan($idsiniestro){
		$query = $this->db->query("select idplandetalle from siniestro s
				inner join certificado c on s.idcertificado=c.cert_id
				inner join plan pl on pl.idplan=c.plan_id
				inner join plan_detalle dp on pl.idplan=dp.idplan
				where idsiniestro=$idsiniestro and idvariableplan=2");
		return $query->row_array();
	}

	function getTriaje($idsiniestro){
		$query = $this->db->query("select * from triaje where idsiniestro=$idsiniestro");
		return $query->row_array();
	}

	function getTratamiento($idsiniestro){
		$query = $this->db->query("select case when presentacion_med='' then nombre_med else concat(nombre_med,'/',presentacion_med) end as nombre_med, cantidad_trat, dosis_trat
									from siniestro_diagnostico sd 
									inner join tratamiento t on sd.idsiniestrodiagnostico=t.idsiniestrodiagnostico 
									inner join medicamento m on t.idmedicamento=m.idmedicamento where idsiniestro=$idsiniestro");
		return $query->result();
	}

	function getValidacion($idsiniestro){
		$query = $this->db->query("select nombre_var, descripcion_prod
									FROM
										siniestro_analisis sa
									INNER JOIN producto pr ON sa.idproducto = pr.idproducto
									INNER JOIN producto_detalle pd ON pr.idproducto = pd.idproducto
									inner join plan_detalle dp on dp.idplandetalle=pd.idplandetalle
									inner join variable_plan v on v.idvariableplan=dp.idvariableplan
									WHERE
										idsiniestro = $idsiniestro
									AND pd.idplandetalle in (select idplandetalle from plan_detalle where idplan=(select plan_id from certificado where cert_id=(select idcertificado from siniestro where idsiniestro=$idsiniestro)))");
		return $query->result();
	}

	function getColaborador(){
		$query = $this->db->query("select idcolaborador, nombres_col, correo_laboral, c.updatedat 
							from colaborador c 
							inner join usuario u on c.idusuario=u.idusuario 
							inner join tipo_usuario tu on u.idtipousuario=tu.idtipousuario 
							where u.idtipousuario=5 and estado_us=1
							order by updatedat limit 1");
		return $query->row_array();
	}

	function upColaborador($data){
		$array = array('updatedat' => $data['hoy'] );
		$this->db->where('idcolaborador', $data['idcol']);
		$this->db->update('colaborador',$array);
	}

	function getBloqueos($id){
		$query = $this->db->query("select idplandetalle_bloqueado, concat(UPPER(vp.nombre_var),' ',pd.texto_web) as nombre_var, UPPER(vp2.nombre_var) as nombre_var2
			from plan_detalle_bloqueo b 
			inner join plan_detalle pd on b.idplandetalle_bloquea=pd.idplandetalle 
			inner join variable_plan vp on vp.idvariableplan=pd.idvariableplan 
			inner join plan_detalle pd2 on b.idplandetalle_bloqueado=pd2.idplandetalle 
			inner join variable_plan vp2 on vp2.idvariableplan=pd2.idvariableplan 
			where idplandetalle_bloquea in (select idplandetalle from siniestro_detalle where idsiniestro=".$id.")");
		return $query->result();
	}

	function getVarieble($idplandetalle){
		$query = $this->db->query("select idvariableplan from plan_detalle where idplandetalle=$idplandetalle");
		return $query->row_array();
	}

	function getMedicamentos2($idsiniestro){
		$query = $this->db->query("select m.idmedicamento, concat(nombre_med,' / ',presentacion_med) as nombre_med from diagnostico_medicamento dm 
				inner join medicamento m on dm.idmedicamento=m.idmedicamento
				inner join diagnostico d on dm.iddiagnostico=d.iddiagnostico
				where codigo_cie = (select ltrim(rtrim(SUBSTR(SUBSTRING_INDEX(dianostico_temp,':',1),4))) as codigo_cie from siniestro_diagnostico where idsiniestro=$idsiniestro);");
		return $query->result();
	}

	function getProductos($idsiniestro, $idplandetalle){
		$query = $this->db->query("select pr.idproducto, descripcion_prod from diagnostico_producto dp
			inner join producto pr on pr.idproducto=dp.idproducto
			inner join producto_detalle pd on pr.idproducto=pd.idproducto
			inner join diagnostico d on d.iddiagnostico=dp.iddiagnostico
			where codigo_cie = (select ltrim(rtrim(SUBSTR(SUBSTRING_INDEX(dianostico_temp,':',1),4))) as codigo_cie from siniestro_diagnostico where idsiniestro=$idsiniestro)
			and pd.idplandetalle=$idplandetalle");
		return $query->result();
	}

	function getProveedor($idsiniestro){
		$query  = $this->db->query("select nombre_comercial_pr from siniestro s inner join proveedor pr on s.idproveedor=pr.idproveedor where idsiniestro=$idsiniestro");
		return $query->row_array();
	}

	function getVariable($id){
		$query = $this->db->query("select * from variable_plan where idvariableplan=$id");
		return $query->row_array();
	}

	function getIdPlanDetalle($cert_id,$idespecialidad){
		$query = $this->db->query("select idplandetalle, (select nombre_esp from especialidad where idespecialidad=21) as nombre_esp from plan_detalle where idplan in (select plan_id from certificado where cert_id=$cert_id) and idvariableplan=1 and idplandetalle in (select idplandetalle from producto_detalle where idproducto=(select idproducto from producto where idespecialidad=$idespecialidad))");
		return $query->row_array();
	}
}
?>