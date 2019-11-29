<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siniestro_cnt extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        // Se le asigna a la informacion a la variable $sessionVP.
        // $this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->sessionRS = @$this->session->userdata('sess_reds_'.substr(base_url(),-20,7));
        //$this->load->helper(array('fechas','otros'));
          $this->load->model('siniestro_mdl');
          $this->load->library('My_PHPMailer');
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

	public function getPlanes()
	{	
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$dni = $_POST['dni'];
			$getAtencion = $this->siniestro_mdl->getAtencion($dni);
			if(empty($getAtencion)){
				$data['planes'] = $this->siniestro_mdl->getPlanes($dni);
				$afiliado = $this->siniestro_mdl->getAfiliado($dni);
				foreach ($afiliado as $a) {
					$data['dni'] = $a->aseg_numDoc;
					$data['afiliado'] = $a->afiliado;
					$data['direccion'] = $a->aseg_direcc.' '.$a->departamento.' - '.$a->provincia.' - '.$a->distrito;
					$data['telefono'] = $a->aseg_telf;
					$fech_nac = date('Y-m-d',strtotime($a->aseg_fechNac));
					$hoy = date('Y-m-d');
					$data['edad'] = abs($hoy-$fech_nac);
				}
				$this->load->view('templates/planes.php',$data);
			}else{
				foreach ($getAtencion as $a) {
					$certase_id = $a->certase_id;
				}
				redirect("index.php/verdetalle/".$certase_id);
			}
			
		}
		else{
			$this->load->view('templates/login.php');
		}		
	}

	public function verdetalle($id){
		$this->load->library('session');
		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			extract($user);
			$data['id'] = $id;

			$data['getAtenciones'] = $this->siniestro_mdl->getAtenciones($id,$idusuario);
			$data['getCoberturas'] = $this->siniestro_mdl->getCoberturas($id);
			$data['getCoberturas2'] = $this->siniestro_mdl->getCoberturas2($id);
			$afiliado = $this->siniestro_mdl->getAfiliadoId2($id);
			foreach ($afiliado as $a) {
				$data['dni'] = $a->aseg_numDoc;
				$data['afiliado'] = $a->afiliado;
				$data['direccion'] = $a->aseg_direcc.' '.$a->departamento.' - '.$a->provincia.' - '.$a->distrito;
				$data['telefono'] = $a->aseg_telf;
				$fech_nac = date('Y-m-d',strtotime($a->aseg_fechNac));
				$hoy = date('Y-m-d');
				$data['edad'] = abs($hoy-$fech_nac);
				$data['fech_nac'] = date('d/m/Y',strtotime($a->aseg_fechNac));
				$estado_atencion = $a->estado_atencion;
				$idcita = $a->idcita;
				$ultima_atencion = $a->ultima_atencion;
				if($a->ultima_atencion==""){
					$data['ultima_atencion'] = "Sin atenciones";
				}else{
					$data['ultima_atencion'] = date('d/m/Y',strtotime($a->ultima_atencion));
				}				
			}
			$data['tipo_orden'] = '';
			$data['idcita'] = $idcita;
			$plan = $this->siniestro_mdl->getPlan($id);
			foreach ($plan as $p) {
				$data['canal'] = $p->nombre_comercial_cli;
				$data['plan'] = $p->nombre_plan;
				$data['cert_id'] = $p->cert_id;
				$data['aseg_id'] = $p->aseg_id;
				$data['certase_id'] = $p->certase_id;
				$data['cert_iniVigc'] = $p->cert_iniVig;
				$data['cert_finVigc'] = $p->cert_finVig;
				$carencia = $p->dias_carencia;
              	$mora = $p->dias_mora;
             	$periodo = $p->dias_atencion;
              	$hoy = time();
              	$iniVig =$p->cert_iniVig;  
              	$finVig =$p->cert_finVig;
              	$finVig = strtotime($finVig);                     
              	$iniVig = strtotime($iniVig);
              	$estado = $p->cert_estado;
              	$manual = $p->cert_upProv;
			}

			if($estado==1){
                $estado = 'Vigente';
                $e1=1;
              }elseif ($hoy<=$finVig){
                $estado= 'Vigente';
                $e1=1;
              }else{
                $estado = 'Cancelado';
                $e1=3;
              }

              if($e1==1){
                if($hoy>$iniVig && $hoy<=$finVig){
                  $e2 = 1;
                  $data['estado2'] = 'Activo';
                }else{
                  if($manual==1){
                    $e2 = 1;
                    $data['estado2'] = 'Activo Manual';
                  }elseif($hoy<$iniVig){
                    $e2 = 4;
                    $data['estado2'] = 'En Carencia';
                  }else{
                    $e2 = 3;
                    $data['estado2'] = 'Inactivo';
                  }
                }
              }else{
                $data['estado2'] = 'Inactivo';
                $e2 = 3;
              }

              if($ultima_atencion<>""){
              	  $hoy = time();
	              $ultima_atencion3 =  date("Y-m-d", strtotime($ultima_atencion));  
	              $ultima_atencion = date("Y-m-d", strtotime($ultima_atencion."+ ".$periodo." days"));  
	              $hoy2 = date("Y-m-d");
	              $ultima_atencion2 = strtotime($ultima_atencion);
	              $diff = ($ultima_atencion2 - $hoy)/86400;

	              if($e2==1){
	              	if($hoy2==$ultima_atencion3){
	              		if($estado_atencion=='O'){	              			
		              		$data['estado2'] = 'En Consulta Médica';
	              		}else{
		              		$data['estado2'] = 'Cita Reservada';
		              		$data['tipo_orden'] = 'P';
	              		}
		              	$e2=2;
		            }elseif($diff>0){
						$data['estado2'] = 'Próxima atención en '.round($diff+1).' días';	    
		              	$e2=5;
		            }              	
	              }

              }              
            $data['e2'] = $e2;
            //$data['dif'] =$diff;
			$this->load->view('templates/detalle_plan.php',$data);
		}
		else{
			$this->load->view('templates/login.php');
		}		
	}

	public function detalle_cobertura($idplandetalle,$certase_id,$idvariableplan,$e,$estado2){
		$user = $this->session->userdata('user');
		extract($user);

		//Datos del certificado y asegurado
		$getCertificadoAsegurado = $this->siniestro_mdl->getCertificadoAsegurado2($certase_id);
		$data['cert_id'] = $getCertificadoAsegurado['cert_id'];
		$cert_id = $getCertificadoAsegurado['cert_id'];
		$data['aseg_id'] = $getCertificadoAsegurado['aseg_id'];
		$data['aseg_telf'] = $getCertificadoAsegurado['aseg_telf'];
		$cert_iniVigc = $getCertificadoAsegurado['cert_iniVig'];
		$cert_finVigc = $getCertificadoAsegurado['cert_finVig'];

		$data['hoy'] = date("Y-m-d");
		$data['idplandetalle'] = $idplandetalle;
		$data['certase_id'] = $certase_id;
		$data['contenido'] = '';
		$data['idvariableplan'] = $idvariableplan;
		$cobertura = $this->siniestro_mdl->getNombreCobertura($idplandetalle);	
		$simbolo = $cobertura['simbolo_detalle'];
		$tiempo = $cobertura['tiempo'];
		$coaseguro = $cobertura['coaseguro'];
		$data['productos'] = $this->siniestro_mdl->getDetalleProductos($idplandetalle);
		$data['productos2'] = $this->siniestro_mdl->getDetalleProductos($idplandetalle);
		$data['idespecialidad'] = 0;

		$idsiniestro=0;

		// Validar si tiene atención hoy
		$siniestro = $this->siniestro_mdl->getSiniestro2($data);
		$idsiniestro = $siniestro['idsiniestro'];
		$idproveedor2 = $siniestro['idproveedor'];
		$nombre_comercial_pr2 = $siniestro['nombre_comercial_pr'];
		$estado_atencion = $siniestro['estado_atencion'];
		$data['idespecialidad'] = $siniestro['idespecialidad'];
		$idespecialidad = $siniestro['idespecialidad'];

		$getIdPlanDetalle = $this->siniestro_mdl->getIdPlanDetalle($cert_id,$idespecialidad);
		$idplandetalle2 = $getIdPlanDetalle['idplandetalle'];
		$nombre_esp = $getIdPlanDetalle['nombre_esp'];

		//validar tipo variable
		$variable = $this->siniestro_mdl->getVariable($idvariableplan);
		$tipo_var = $variable['tipo_var'];
		$data['tipo_var'] = $tipo_var;
		

		$getCertificadoAsegurado = $this->siniestro_mdl->getCertificadoAsegurado($certase_id);
		if(!empty($getCertificadoAsegurado)){
			foreach ($getCertificadoAsegurado as $ca) {
				$data['cert_id'] = $ca->cert_id;
				$data['aseg_id'] = $ca->aseg_id;
				$data['aseg_telf'] = $ca->aseg_telf;
				$cert_iniVigc = $ca->cert_iniVig;
				$cert_finVigc = $ca->cert_finVig;
			}
		}else{
			$data['cert_id'] = "";
			$data['aseg_id'] = "";
			$data['aseg_telf'] = "";
		}
		$data['hoy'] = date("Y-m-d");
		$data['idplandetalle'] = $idplandetalle;
		$data['certase_id'] = $certase_id;
		$data['contenido'] = '';
		$data['idvariableplan'] = $idvariableplan;
		$cobertura = $this->siniestro_mdl->getNombreCobertura($idplandetalle);	
		$simbolo = $cobertura['simbolo_detalle'];
		$tiempo = $cobertura['tiempo'];
		$coaseguro = $cobertura['coaseguro'];
		$data['productos'] = $this->siniestro_mdl->getDetalleProductos($idplandetalle);
		$data['productos2'] = $this->siniestro_mdl->getDetalleProductos($idplandetalle);
		$data['idespecialidad'] = 0;
		$var=0;
		
		switch ($e) {
			case 1:
				$idsiniestro=0;
				$e2 = 1;
				
				if($tipo_var==1 && $idvariableplan==1){
					$per_evento = $this->siniestro_mdl->getPeriodoEvento($data);
					if(empty($per_evento)){
						$e2=1;
					}else{
						if($per_evento['total_vez']==$per_evento['vez_actual']){
							$e=6;
							$e2=2;
							if($per_evento['tipo_evento']==1){
								$estado2 = "El afiliado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}else{													
								$estado2 = "El certificado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}
						}else{
							$e2=1;												
						}
					}
				}elseif($tipo_var==1 && $idvariableplan!=1){
					$e2=1;
					$per_evento = $this->siniestro_mdl->getPeriodoEvento($data);
					if(empty($per_evento)){
						$e2=1;
					}else{
						if($per_evento['total_vez']==$per_evento['vez_actual']){
							$e=6;
							$e2=2;
							if($per_evento['tipo_evento']==1){
								$estado2 = "El afiliado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}else{													
								$estado2 = "El certificado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}
						}else{
							$e2=1;
						}
					}

				}else{
					$e=7;
					$estado2 = "Debe generar una orden de atención para validar ésta cobertura";
				}
			break;
			case 2:
				// Verificar si el asegurado tiene una reserva
				// $e (6=cita reservada con otro proveedor, 7 = primero debe generar OA)
				// $e2 (1 = generar, 2 = reimprimir)
				if($tipo_var==1 && $idvariableplan!=1){
					$e=1;
					$e2=1;
					$per_evento = $this->siniestro_mdl->getPeriodoEvento($data);
					if(empty($per_evento)){
						$e2=1;
					}else{
						if($per_evento['total_vez']==$per_evento['vez_actual']){
							$e=6;
							$e2=2;
							if($per_evento['tipo_evento']==1){
								$estado2 = "El afiliado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}else{													
								$estado2 = "El certificado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}
						}else{
							$e2=1;
						}
					}

				}else{
					$siniestro = $this->siniestro_mdl->getSiniestro($data);
					if(!empty($siniestro)){
						foreach ($siniestro as $s) {
							$idsiniestro = $s->idsiniestro;
							$idproveedor2 = $s->idproveedor;
							$nombre_comercial_pr2 = $s->nombre_comercial_pr;
							$estado_atencion = $s->estado_atencion;
							$var = $s->idvariableplan;
							$data['idespecialidad'] = $s->idespecialidad;
						}

						if($estado_atencion=='P'){
							if($idproveedor == $idproveedor2){						
								if($tipo_var==1){
									$e=1;
									if($idplandetalle==$idplandetalle2){
										$e2 =1;
									}else{
										$e=7;
										$e2=2;
										$estado2 = "La consulta fue reservada para la especialidad de ".$nombre_esp;
									}
								}else{
									$e2=1;
									$e=7;
									$estado2 = "Debe generar una orden de atención para validar ésta cobertura";
								}
							}else{

								if($tipo_var==1 && $idvariableplan!=1){	
									$idsiniestro=0;
									$e2 = 1;
									$e=1;
									$e2=1;
								}else{
									$e=6;
									$e2=1;
									$estado2 = "El afiliado cuenta con una cita reservada en ".$nombre_comercial_pr2;
								}
								
							}						
						}else{
							if($idproveedor == $idproveedor2){						
								/*if($tipo_var==1){
									if($var == $idvariableplan){									
										$e=1;
										$e2 =2;
									}else{
										$e=1;
										$e2 =1;
									}
								}else{*/
									$data['idsiniestro']=$idsiniestro;
									$getSinDetalle = $this->siniestro_mdl->getSiniestroDetalle($data);
									$e=1;
									if(empty($getSinDetalle)){	
										$plan_detalle = $this->siniestro_mdl->getTiempo($idplandetalle);
										if($plan_detalle['tiempo']==0){																
											$e2=1;
										}else{
											$per_evento = $this->siniestro_mdl->getPeriodoEvento($data);
											if(empty($per_evento)){
												$e2=1;
											}else{
												if($per_evento['total_vez']<$per_evento['vez_actual']){
													$e2=1;
												}else{
													$e=6;
													$e2=2;
													if($per_evento['tipo_evento']==1){
														$estado2 = "El afiliado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos';
													}else{													
														$estado2 = "El certificado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos';
													}
												}
											}
										}
									}else{
										$e2=2;
									}
								//}
							}else{
								if($tipo_var==1 && $idvariableplan!=1){	
									$idsiniestro=0;
									$e2 = 1;
									$e=1;
									$e2=1;
								}else{
									$e=6;
									$e2=1;
									$estado2 = "El afiliado está siendo atendido en ".$nombre_comercial_pr2;
								}
								
							}
						}	
					}else{
						$idsiniestro = 0;
						$e=6;
						$e2=1;
					}
				}
				
			break;


			case 5:
				if($tipo_var==1 && $idvariableplan!=1){
					$idsiniestro=0;
					$per_evento = $this->siniestro_mdl->getPeriodoEvento($data);
					if(empty($per_evento)){
						$e2=1;
					}else{
						if($per_evento['total_vez']==$per_evento['vez_actual']){
							$e=6;
							$e2=2;
							if($per_evento['tipo_evento']==1){
								$estado2 = "El afiliado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}else{													
								$estado2 = "El certificado tiene ".$per_evento['vez_actual'].' de '.$per_evento['total_vez'].' eventos consumidos' ;
							}
						}else{
							$e2=1;							
						}
					}
				}else{
					$idsiniestro=0;
					$e=5;
					$e2=1;
				}
			break;
			
			default:
				$idsiniestro = 0;
				$e=6;
				$e2=1;
			break;
		}		

		$bloqueos = $this->siniestro_mdl->getBloqueos($idsiniestro);
		$cant = 0;
		$nom_var="";
		$nom_var2="";

		if(!empty($bloqueos)){			
			foreach ($bloqueos as $b) {
				if($b->idplandetalle_bloqueado==$idplandetalle){
					          
				}
			}
		}


		if($cant == 1){
			$e=6;
			$estado2= "Según el condicionado de su plan, ".$nom_var." no cubre ".$nom_var2.", para mayor información comuníquese con su asesor de ventas";
			$e2=2;
		}
		$data['estado'] = $e;
		$data['estado2'] = $estado2;
		$data['estado_impresion'] = $e2;
		$data['idsiniestro'] = $idsiniestro;

		if($idsiniestro==0){
			$data['diagnostico'] = '';
			$data['medicamentos'] = '';
			$data['productos'] = '';
		}else{
			$getDiagnostico = $this->siniestro_mdl->getDiagnostico($idsiniestro);
			$data['diagnostico'] = $getDiagnostico['dianostico_temp'];

			$data['medicamentos'] = $this->siniestro_mdl->getMedicamentos2($idsiniestro);
			$data['productos'] = $this->siniestro_mdl->getProductos($idsiniestro, $idplandetalle);
		}


		$data['coaseguro'] = $coaseguro;

		switch($tiempo){
            case '':
                $data['eventos'] = "Eventos ilimitados";
                break;
            case '1 month':
                $data['eventos'] = $cobertura['num_eventos']." eventos mensuales";
                break;
            case '2 month':
                $data['eventos'] = $cobertura['num_eventos']." eventos bimestrales";
                break;
            case '3 month':
                $data['eventos'] = $cobertura['num_eventos']." eventos trimestrales";
                break;
            case '6 month':
                $data['eventos'] = $cobertura['num_eventos']." eventos semestrales";
                break;
            case '1 year':
                $data['eventos'] = $cobertura['num_eventos']." eventos anuales";
                break;
        }


		switch ($idvariableplan) {
			case '1':
				$data['cobertura'] = "CONSULTA MÉDICA";
				$data['descripcion'] = "Seleccionar especialidad:";
				$data['producto_detalle'] = $this->siniestro_mdl->getEspecialidad($data);
				break;

			case '2':
				$data['cobertura'] = "MEDICAMENTOS GENÉRICOS";
				$data['descripcion'] = "Digitar diagnóstico para consultar medicamentos cubiertos.";
				$data['producto_detalle'] = "";
				break;

			case '3':
				$data['cobertura'] = "LABORATORIOS";
				$data['descripcion'] = "Digitar diagnóstico para consultar laboratorios cubiertos.";
				$data['producto_detalle'] = "";
				break;

			case '4':
				$data['cobertura'] = "IMAGENEOLOGÍA";
				$data['descripcion'] = "Digitar diagnóstico para consultar imágenes cubiertas.";
				$data['producto_detalle'] = "";
				break;
			
			default:							
				$data['cobertura'] = $cobertura['nombre2'];
				$data['descripcion'] = "Cubiertos:";
				if($tipo_var==1){
		        	$data['producto_detalle'] = $this->siniestro_mdl->getEspecialidad($data);
		        }else{		        	
					$data['producto_detalle'] = "";
				break;
		        }
		}
		        
		$this->load->view('templates/detalle_cobertura.php',$data);
	}

	public function generar_orden(){
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			extract($user);					
			$data['cert_id'] = $_POST['cert_id'];
			$data['aseg_id'] = $_POST['aseg_id'];
			$data['certase_id'] = $_POST['certase_id'];
			$data['aseg_telf'] = $_POST['telf'];
			$data['hoy'] = date("Y-m-d");
			$data['idproveedor'] = $idproveedor;
			$data['especialidad'] = $_POST['especialidad'];
			$data['idplandetalle'] = $_POST['idplandetalle'];
			$idvariableplan = $_POST['idvariableplan'];
			$nombre_var2="";

			$med = $this->siniestro_mdl->getMedicamentosBloqueados($data);
			if(!empty($med)){
				$comentario = "No cubierto para la especialidad seleccionada";
			}else{
				$comentario = "El plan sólo cubre medicamentos en su presentación genérica";
			}

			//validar tipo variable
			$variable = $this->siniestro_mdl->getVariable($idvariableplan);
			$nombre = $variable['nombre_var'];
			$tipo_var = $variable['tipo_var'];
			$data['tipo_var'] = $tipo_var;

			$this->siniestro_mdl->upTelf($data);
			//Verificar si el asegurado tiene historia
			$historia = $this->siniestro_mdl->getHistorial($data);
			if(!empty($historia)){
				foreach ($historia as $h) {
					$idhistoria = $h->idhistoria;
				}
			}else{
				$idhistoria = 0;
			}

			if($idhistoria==0){
				$this->siniestro_mdl->inHistoria($data);
				$idhistoria = $this->db->insert_id();
			}

			$data['idhistoria'] = $idhistoria;

			//Verificar si el asegurado tiene una reserva
			$siniestro = $this->siniestro_mdl->getSiniestro($data);
			if(!empty($siniestro)){
				foreach ($siniestro as $s) {
					$idsiniestro = $s->idsiniestro;
				}
			}else{
				$idsiniestro = 0;
			}

			$idsiniestro2=$idsiniestro;

			$num = $this->siniestro_mdl->num_orden_atencion();
			foreach ($num as $n) {
				$numero=$n->num_orden_atencion;
				$data['num'] = $numero;
			}

			if($idsiniestro == 0){
				$this->siniestro_mdl->inSiniestro($data);
				$idsiniestro = $this->db->insert_id();
				$data['idsiniestro'] = $idsiniestro;
			}else{
				$data['idsiniestro'] = $idsiniestro;
				$this->siniestro_mdl->upSiniestro($data);
			}

			// crear periodo_evento a todas las coberturas del certificado
			$coberturas2 = $this->siniestro_mdl->getCoberturas_Periodos($data);
			 foreach ($coberturas2 as $c2) {

				$eventos = $this->siniestro_mdl->numEventos($data);
				if(!empty($eventos)){
					foreach ($eventos as $e) {
						$num_eventos = $e->num_eventos;
						$data['num_eventos'] = $num_eventos;
						$tipo_evento = $e->tipo_evento;
						$tiempo = $e->tiempo;
					}
				}else{
					$num_eventos=0;
					$tipo_evento=0;
				}
				
				if($num_eventos>0){
					if($tipo_evento==1){
						$periodo = $this->siniestro_mdl->periodo($data);
					}else{
						$periodo = $this->siniestro_mdl->periodo2($data);
					}
					if(!empty($periodo)){
						foreach ($periodo as $pe) {
							$data['idperiodo'] = $pe->idperiodo;
							$estado = $pe->estado;
							$vez_actual = $pe->vez_actual;
							$fin = strtotime($pe->finVig);
					        $fin = date("Y-m-d", $fin);
					        $hoy = time();
							if($estado==0){
								while(strtotime($fin)<$hoy){
									$ini = strtotime($fin."+ 1 day");
									$ini = date("Y-m-d", $ini);
						            $fin = strtotime($ini."+ ".$tiempo);
						            $fin = date("Y-m-d", $fin);
								};
								$data['ini'] = $ini;
								$data['fin'] = $fin;
								$this->siniestro_mdl->upPeriodo($data);							
							}
						}

								$test = 1;	
					}else{
						$certificado = $this->siniestro_mdl->getCertificado($data);
						foreach ($certificado as $c) {
							$cert_iniVig = $c->cert_iniVig;
						}

						$ini = strtotime($cert_iniVig."+ 1 day");
						$ini = date("Y-m-d", $ini);
						$fin = strtotime($ini."+ ".$tiempo);
					    $hoy = time();
						while($hoy<strtotime($fin)){
							$ini = strtotime($fin."+ 1 day");
							$ini = date("Y-m-d", $ini);
						    $fin = strtotime($ini."+ ".$tiempo);
						    $fin = date("Y-m-d", $fin);
						}
						$fin = strtotime($fin);
						$fin = date("Y-m-d", $fin);
						
						$data['ini'] = $ini;
						$data['fin'] = $fin;
						if($tipo_evento==1){
							$this->siniestro_mdl->inPeriodo($data);
						}else{
							$this->siniestro_mdl->inPeriodo2($data);
						}
						$data['idperiodo'] = $this->db->insert_id();
						$vez_actual = 0;


								$test = 2;	
					}
				}
			 }

			 // Verificar Periodo_evento
			$cobertura = $_POST['idvariableplan'];
			
			$vez_evento = $this->siniestro_mdl->vez_Evento($data);
				if(empty($vez_evento)){
					$data['vez_actual'] = 1;
				}else{
					foreach ($vez_evento as $ve) {
			 			$data['vez_actual'] = $ve->vez_evento;
			 		}
				}
			$this->siniestro_mdl->upVez_evento($data);						
			$this->siniestro_mdl->inSiniestroDetalle($data); 

			$afiliado = $this->siniestro_mdl->getAfiliadoId($data['certase_id']);
			foreach ($afiliado as $a) {
				$data['dni'] = $a->aseg_numDoc;
				$data['afiliado'] = $a->afiliado;
				$data['direccion'] = $a->aseg_direcc.' '.$a->departamento.' - '.$a->provincia.' - '.$a->distrito;
				$data['telefono'] = $a->aseg_telf;
				$fech_nac = date('d/m/Y',strtotime($a->aseg_fechNac));
			}
			$OrdenAtencion = $this->siniestro_mdl->OrdenAtencion($data);
			foreach ($OrdenAtencion as $oa){
				$idproducto = $oa->idproducto;
				$num = $oa->num_orden_atencion;
				$plan = $oa->nombre_plan;
				$fecha_atencion = $oa->fecha_atencion;
				$especialidad = $oa->descripcion_prod;
			}

			$nombre_var = "MEDICAMENTOS GENÉRICOS";
			$texto_web = "";

			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$fecha = date("d/m/Y");

			// //Crear formato de liquidación
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();  
	          	$this->pdf->SetFont('Arial','B',10); 


			if($cobertura==1){
			    $this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	 
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$data['dni'],1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($data['afiliado']),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Especialidad: ".$especialidad),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln(); 
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: ".$plan),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			    $coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($c->idvariableplan==2){
			    		$nombre_var = $c->nombre_var;
			    		$texto_web = $c->texto_web;
			    	}elseif($c->idvariableplan==38){
			    		$nombre_var2 = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}			    	
			    }
			    if($cobertura==1){
			    	foreach ($coberturas as $c) {
			   			$this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var.': '.$c->texto_web),1,'J');
			   		}
			   	}else{
			   		$this->pdf->MultiCell(190,6,utf8_decode($nombre_var2.': '.$texto_web2),1,'J');
			   	}	          	
	            $this->pdf->SetFont('Arial','B',9);
	           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
	            $this->pdf->Ln(); 
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(38,7,utf8_decode("PA:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FC:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FR:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Peso(kg):"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Talla(m):"),1,0,'L',false);	
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("Cabeza:"),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("CV:RC:"),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("TP:MV:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(114,7,utf8_decode("Abdomen:"),1,0,'L',false);
	            $this->pdf->Cell(76,7,utf8_decode("RHA:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(190,7,utf8_decode("Neuro:"),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (".$comentario.")"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln(15);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información médica que requiera para la evaluación de expediente médico."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);

			    if(empty($med)){
			     $this->pdf->AddPage();				    
			    $this->pdf->AliasNbPages();	
			    $this->pdf->Ln(-2);  
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE MEDICAMENTOS"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10);
	          	$this->pdf->MultiCell(190,6,utf8_decode("ORDEN DE MEDICAMENTOS"),0,'R',true);
	          	$this->pdf->Ln();	          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($data['afiliado']),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($data['dni']),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(190,6,utf8_decode("CONDICIONES DEL PLAN: ".$plan),0,'L',true);
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->MultiCell(190,6,utf8_decode($nombre_var.': '.$texto_web),1,'J');			    
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Tratamiento: "),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Cubiertos (el plan sólo cubre medicamentos en su presentación genérica)"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("No Cubiertos"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln(25);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* El plan no cubre vitaminas ni ansiolíticos."),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("* La presente orden de medicamentos tiene validez por 7 días."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Es obligatorio el registro de los eventos de laboratorio por parte del Centro Médico."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
			    }
			}else{
				$idespecialidad=0;
				$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	 
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$data['dni'],1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($data['afiliado']),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Servicio: ".$nombre),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln(); 
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: ".$plan),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			    $coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($idvariableplan==$c->idvariableplan){
			    		$nombre_variable = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}    	
			    }
			   	$this->pdf->MultiCell(190,6,utf8_decode($nombre_variable.': '.$texto_web2),1,'J');
	            $this->pdf->SetFont('Arial','B',9);
	           	$this->pdf->Cell(190,7,utf8_decode("Motivo de atención"),0,0,'L',false);	           	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);	
	            $this->pdf->Ln();
	           
	            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (".$comentario.")"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode("Servicio (Ejem: Laboratorio, Imágenes, etc)"),1,0,'C',false);
	            $this->pdf->Cell(110,7,utf8_decode("Descripción (Ejem: Hemograma Completo, Urocultivo, etc)"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(80,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(110,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Ln(15);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Proveedor del Servicio"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información que requiera para la evaluación de la atención."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);
			}

			$this->pdf->Output("uploads/".$data['idsiniestro'].".pdf", 'F');
			//email para post venta
			if($idsiniestro2==0){
				$getColaborador = $this->siniestro_mdl->getColaborador();
				$nombre_colaborador = $getColaborador['nombres_col'];
				$correo_colaborador = $getColaborador['correo_laboral'];
				$data['idcol'] = $getColaborador['idcolaborador'];
				$data['hoy'] = date('Y-m-d H:i:s');

				$this->siniestro_mdl->upColaborador($data);

				$tipo="'Century Gothic'";
				$texto='<div><p>Hola '.$nombre_colaborador.'</p>
					<p>Se ha generado la orden de atenci&oacute;n con los siguientes datos:</p>
					<table align="center" border="1" width="90%">
						<tr>
							<th>N° Orden de Atenci&oacute;n:</th>
							<td> OA'.$num.'</td>
							<th>Centro M&eacute;dico:</th>
							<td>'.$nombre_comercial_pr.'</td>
						</tr>
						<tr>
							<th>Plan:</th>
							<td>'.$plan.'</td>
							<th>DNI:</th>
							<td>'.$data['dni'].'</td>
						</tr>
						<tr>
							<th>Nombres y Apellidos: </th>
							<td>'.$data['afiliado'].'</td>
							<th>Tel&eacute;fono de contacto:</th>
							<td>'.$data['telefono'].'</td>
						</tr>
					</table>
					<p>Se remite la informaci&oacute;n para la post-venta.</p>
					<p>Saludos Cordiales</p>
					<p>Atte. Red Salud</p></div>';		
			
			$mail = new PHPMailer;	
			$mail->isSMTP();
	        $mail->Host     = 'localhost';
	        $mail->SMTPAuth = false;
	        $mail->Username = '';
	        $mail->Password = '';
	        $mail->SMTPSecure = 'false';
	        $mail->Port     = 25;	
			// Armo el FROM y el TO
			$mail->setFrom('contacto@red-salud.com', 'Red Salud');
			$mail->addAddress($correo_colaborador, $nombre_colaborador);
			$mail->addAddress('pvigil@red-salud.com', 'Pilar Vigil');
			$mail->addAddress('contacto@red-salud.com', 'Red Salud');
			// El asunto
			$mail->Subject = "NOTIFICACION - ORDEN DE ATENCION DIRECTA";
			// El cuerpo del mail (puede ser HTML)
			$mail->Body = '<!DOCTYPE html>
					<head>
	                <meta charset="UTF-8" />
	                </head>
	                <body style="font-size: 1vw; width: 100%; font-family: '.$tipo.', CenturyGothic, AppleGothic, sans-serif;">
	                <div style="padding-top: 2%; text-align: right; padding-right: 15%;"><img src="https://www.red-salud.com/mail/logo.png" width="17%" style="text-align: right;"></img>
	                </div>
	                <div style="padding-right: 15%; padding-left: 8%;"><b><label style="color: #000000;"> </b></div>
	                <div style="padding-right: 15%; padding-left: 8%; padding-bottom: 1%; color: #12283E;">
	                '.$texto.'
	                <div style="background-color: #BF3434; padding-top: 0.5%; padding-bottom: 0.5%">
	                <div style="text-align: center;"><b><a href="https://www.google.com/maps/place/Red+Salud/@-12.11922,-77.0370327,17z/data=!3m1!4b1!4m5!3m4!1s0x9105c83d49a4312b:0xf0959641cc08826!8m2!3d-12.11922!4d-77.034844" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">Av. Jos&eacute; Pardo Nro 601 Of. 502, Miraflores - Lima.</a></b></div>
	                <div style="text-align: center;"><b><a href="https://www.red-salud.com" style="text-decoration-color: #FFFFFF; text-decoration: none; color:  #FFFFFF;">www.red-salud.com</a></b></div>
	                </div>
	                <div style=""><img src="https://www.red-salud.com/mail/bottom.png" width="50%"></img></div>
	                </div>
	            </body>
				</html>';
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			// Los archivos adjuntos
			//$mail->addAttachment('adjunto/'.$plan.'.pdf', 'Condicionado.pdf');
			//$mail->addAttachment('adjunto/RED_MEDICA_2018.pdf', 'Red_Medica.pdf');
			// Enviar
			$mail->send();
		}				

			$this->load->view('templates/detalle_pdf',$data);
		}
		else{
			$this->load->view('templates/login.php');
		}	


	}

	
	function guardar_medicamentos(){
		$user = $this->session->userdata('user');
		extract($user);	
		$data['dianostico_temp'] = $_POST['dianostico_temp'];
		$idsiniestro = $_POST['idsiniestro'];
		$data['idsiniestro'] = $idsiniestro;
		$idplandetalle = $_POST['idplandetalle'];
		$data['idplandetalle'] = $idplandetalle;
		$medicamentos = $_POST['chk'];
		$cant = count($medicamentos);
		$tipo = $_POST['tipo'];

		if($tipo == 1){			
			$this->siniestro_mdl->inSiniestroDiagnostico($data);
			$id= $this->db->insert_id();
		}else{
			$getDiagnostico = $this->siniestro_mdl->getDiagnostico($idsiniestro);
			$id = $getDiagnostico['idsiniestrodiagnostico'];
		}

		$data['idsiniestrodiagnostico'] = $id;	

		for($i=0;$i<$cant;$i++){
			$data['idmedicamento'] = $medicamentos[$i];
			$this->siniestro_mdl->inTratamiento($data);
		}


		$vez_evento = $this->siniestro_mdl->vez_Evento($data);
		if(empty($vez_evento)){
			$data['vez_actual'] = 1;
		}else{
			foreach ($vez_evento as $ve) {
			$data['vez_actual'] = $ve->vez_evento;	
			}
		}
		
		$this->siniestro_mdl->inSiniestroDetalle($data);

		$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
			$data['certase_id'] = $getSin['certase_id'];
			$data['idsiniestro'] = $idsiniestro;
			$dni = $getSin['aseg_numDoc'];
			$afiliado = $getSin['afiliado'];
			$num = $getSin['num_orden_atencion'];
			$fecha_atencion = $getSin['fecha_atencion'];
			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$getCobertura = $this->siniestro_mdl->getCobertura($idplandetalle);
			$detalle = $getCobertura['descripcion'];
			$nombre_var = $getCobertura['nombre_var'];
			$idvariableplan = $getCobertura['idvariableplan'];

			switch($getCobertura['tiempo']){
            case '':
                $eventos = "Eventos ilimitados";
                break;
            case '1 month':
                $eventos = $getCobertura['num_eventos']." eventos mensuales";
                break;
            case '2 month':
                $eventos = $getCobertura['num_eventos']." eventos bimestrales";
                break;
            case '3 month':
                $eventos = $getCobertura['num_eventos']." eventos trimestrales";
                break;
            case '6 month':
                $eventos = $getCobertura['num_eventos']." eventos semestrales";
                break;
            case '1 year':
                $eventos = $getCobertura['num_eventos']." eventos anuales";
                break;
        	}

        	$med = $this->siniestro_mdl->getMedicamentos($idsiniestro);

       		 foreach ($med as $m2) {
       		 	$diagnostico = $m2->dianostico_temp;
       		 }


			// Crear formato de consulta
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln(); 
			    $this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMATO DE CONSULTA"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',13);
	          	$this->pdf->MultiCell(190,8,utf8_decode("MEDICAMENTOS GENÉRICOS"),0,'R',true);
	          	$this->pdf->Ln();          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(190,6,utf8_decode("CONDICIONES: ".$getCobertura['nombre_plan']),0,'L',true);
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->MultiCell(190,6,utf8_decode($nombre_var.': '.$detalle.', '.$eventos),1,'J');
			    $this->pdf->SetFont('Arial','B',10); 
			    if($idvariableplan==2){
			    $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
	            $this->pdf->Ln();			    	
			    }
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Items consultados: "),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(10,7,utf8_decode(" N°"),1,0,'L',false);
			    $this->pdf->Cell(140,7,utf8_decode(" Descripción"),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Cubierto"),1,0,'L',false);
			    $this->pdf->Ln();
			    $cont=1;
			    $this->pdf->SetFont('Arial','',10); 
			    foreach ($med as $m) {			    	
				    $this->pdf->Cell(10,7,$cont,1,0,'L',false);
				    $this->pdf->Cell(140,7,utf8_decode($m->nombre_med),1,0,'L',false);
				    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'C',false);
				    $this->pdf->Ln();
				    $cont++;
			    }
			   
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Sólo la impresión de éste formato de consulta, garantiza la aprobación de los items consultados para su facturación."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->Output("uploads/".$idsiniestro.".pdf", 'F');
				$this->load->view('templates/detalle_pdf',$data);
	}

	public function guardar_cobertura(){
		$user = $this->session->userdata('user');
		extract($user);	
		$tipo = $_POST['tipo'];
		$idsiniestro = $_POST['idsiniestro'];
		$idplandetalle = $_POST['idplandetalle'];

			$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
			$data['certase_id'] = $getSin['certase_id'];
			$data['idsiniestro'] = $idsiniestro;
			$data['idplandetalle'] = $idplandetalle;
			$detalle = $_POST['checkboxes'];
			$cant = count($detalle);
			for($i=0;$i<$cant;$i++){
				$data['idproducto'] = $detalle[$i];
				$this->siniestro_mdl->inSinAnalisis($data);
			}

			$getPeriodo = $this->siniestro_mdl->getPeriodoEvento($data);
			$vez_actual = $getPeriodo['vez_actual'];
			$vez_actual++;
			$data['vez_actual'] = $vez_actual;
			$data['idperiodo'] = $getPeriodo['idperiodo'];
			$dni = $getSin['aseg_numDoc'];
			$afiliado = $getSin['afiliado'];
			$num = $getSin['num_orden_atencion'];
			$fecha_atencion = $getSin['fecha_atencion'];
			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$getCobertura = $this->siniestro_mdl->getCobertura($idplandetalle);
			$detalle = $getCobertura['descripcion'];
			$nombre_var = $getCobertura['nombre_var'];
			$idvariableplan = $getCobertura['idvariableplan'];

			$this->siniestro_mdl->upPeriodo_evento($data);
			$this->siniestro_mdl->inSiniestroDetalle($data);

			$getVarieble = $this->siniestro_mdl->getVarieble($idplandetalle);
			$var = $getVarieble['idvariableplan'];

			if($var==3 || $var=4){
				if($tipo==1){								
					$dianostico_temp = $_POST['dianostico_temp'];
					if($dianostico_temp!=1){
						$data['dianostico_temp'] = $dianostico_temp;
						$this->siniestro_mdl->inSiniestroDiagnostico($data);						
					}	
				}
			}

			switch($getCobertura['tiempo']){
            case '':
                $eventos = "Eventos ilimitados";
                break;
            case '1 month':
                $eventos = $getCobertura['num_eventos']." eventos mensuales";
                break;
            case '2 month':
                $eventos = $getCobertura['num_eventos']." eventos bimestrales";
                break;
            case '3 month':
                $eventos = $getCobertura['num_eventos']." eventos trimestrales";
                break;
            case '6 month':
                $eventos = $getCobertura['num_eventos']." eventos semestrales";
                break;
            case '1 year':
                $eventos = $getCobertura['num_eventos']." eventos anuales";
                break;
        	}

        	$detalle2 = $this->siniestro_mdl->detProducto($data);

			// Crear formato de consulta
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln(); 
			    $this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMATO DE VALIDACIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',13);
	          	$this->pdf->MultiCell(190,8,utf8_decode($nombre_var),0,'R',true);
	          	$this->pdf->Ln();          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(190,6,utf8_decode("CONDICIONES: ".$getCobertura['nombre_plan']),0,'L',true);
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->MultiCell(190,6,utf8_decode($nombre_var.': '.$detalle.', '.$eventos),1,'J');
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Items consultados: "),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(10,7,utf8_decode(" N°"),1,0,'L',false);
			    $this->pdf->Cell(140,7,utf8_decode(" Descripción"),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Cubierto"),1,0,'L',false);
			    $this->pdf->Ln();
			    $cont=1;
			    $this->pdf->SetFont('Arial','',10); 
			    foreach ($detalle2 as $d) {			    	
				    $this->pdf->Cell(10,7,$cont,1,0,'L',false);
				    $this->pdf->Cell(140,7,utf8_decode($d->descripcion_prod),1,0,'L',false);
				    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'C',false);
				    $this->pdf->Ln();
				    $cont++;
			    }
			   
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Sólo la impresión de éste formato de consulta, garantiza la aprobación de los items consultados para su facturación."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->Output("uploads/".$idsiniestro.".pdf", 'F');
				$this->load->view('templates/detalle_pdf',$data);
	}

	function reimprimir_pdf($idsiniestro,$idvariableplan,$idplandetalle){
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			extract($user);		
			$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
			$data['certase_id'] = $getSin['certase_id'];
			$data['idsiniestro'] = $idsiniestro;
			$dni = $getSin['aseg_numDoc'];
			$afiliado = $getSin['afiliado'];
			$num = $getSin['num_orden_atencion'];
			$especialidad = $getSin['descripcion_prod'];
			$plan = $getSin['nombre_plan'];
			$fech_nac = $getSin['aseg_fechNac'];
			$fecha_atencion = $getSin['fecha_atencion'];
			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$nombre_var = "MEDICAMENTOS GENÉRICOS";
			$texto_web = "";

			$data['idplandetalle'] = $idplandetalle;
			$variable = $this->siniestro_mdl->getVariable($idvariableplan);
			$nombre = $variable['nombre_var'];
			$tipo_var = $variable['tipo_var'];
			$data['tipo_var'] = $tipo_var;

			$med2 = $this->siniestro_mdl->getMedicamentosBloqueados($data);
			if(!empty($med2)){
				$comentario = "No cubierto para la especialidad seleccionada";
			}else{
				$comentario = "El plan sólo cubre medicamentos en su presentación genérica";
			}

			$med = $this->siniestro_mdl->getDiagnostico($idsiniestro);
       		 

			// //Crear formato de liquidación
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();  
	          	$this->pdf->SetFont('Arial','B',10); 


			if($idvariableplan==1){
			    $this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);   
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,40,190);	
	          	$this->pdf->Ln(10);	 
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$dni,1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($afiliado),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Especialidad: ".$especialidad),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln(); 
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: ".$plan),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			    $coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($c->idvariableplan==2){
			    		$nombre_var = $c->nombre_var;
			    		$texto_web = $c->texto_web;
			    	}elseif($c->idvariableplan==38){
			    		$nombre_var2 = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}			    	
			    }
			    if($idvariableplan==1){
			    	foreach ($coberturas as $c) {
			   			$this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var.': '.$c->texto_web),1,'J');
			   		}
			   	}else{
			   		$this->pdf->MultiCell(190,6,utf8_decode($nombre_var2.': '.$texto_web2),1,'J');
			   	}	          	
	            $this->pdf->SetFont('Arial','B',9);
	           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
	            $this->pdf->Ln(); 
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(38,7,utf8_decode("PA:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FC:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FR:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Peso(kg):"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Talla(m):"),1,0,'L',false);	
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("Cabeza:"),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("CV:RC:"),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("TP:MV:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(114,7,utf8_decode("Abdomen:"),1,0,'L',false);
	            $this->pdf->Cell(76,7,utf8_decode("RHA:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(190,7,utf8_decode("Neuro:"),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (".$comentario.")"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln(15);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información médica que requiera para la evaluación de expediente médico."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);

			    if(empty($med2)){
			    $this->pdf->AddPage();				    
			    $this->pdf->AliasNbPages();	
			    $this->pdf->Ln(-2);  
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln();
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE MEDICAMENTOS"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10);
	          	$this->pdf->MultiCell(190,6,utf8_decode("ORDEN DE MEDICAMENTOS"),0,'R',true);
	          	$this->pdf->Ln();	          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(190,6,utf8_decode("CONDICIONES DEL PLAN: ".$plan),0,'L',true);
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->MultiCell(190,6,utf8_decode($nombre_var.': '.$texto_web),1,'J');			    
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Tratamiento: "),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Cubiertos (el plan sólo cubre medicamentos en su presentación genérica)"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("No Cubiertos"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln(25);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* El plan no cubre vitaminas ni ansiolíticos."),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("* La presente orden de medicamentos tiene validez por 7 días."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Es obligatorio el registro de los eventos de laboratorio por parte del Centro Médico."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
			    }
			}else{
				$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	 
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$data['dni'],1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($data['afiliado']),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Servicio: ".$nombre),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln(); 
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: ".$plan),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			    $coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($c->idvariableplan==2){
			    		$nombre_var = $c->nombre_var;
			    		$texto_web = $c->texto_web;
			    	}elseif($c->idvariableplan==38){
			    		$nombre_var2 = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}			    	
			    }
			    if($cobertura==1){
			    	foreach ($coberturas as $c) {
			   			$this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var.': '.$c->texto_web),1,'J');
			   		}
			   	}else{
			   		$this->pdf->MultiCell(190,6,utf8_decode($nombre_var2.': '.$texto_web2),1,'J');
			   	}	          	
	            $this->pdf->SetFont('Arial','B',9);
	           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);	
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
	            $this->pdf->Ln(); 
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(38,7,utf8_decode("PA:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FC:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FR:"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Peso(kg):"),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Talla(m):"),1,0,'L',false);	
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("Cabeza:"),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("CV:RC:"),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("TP:MV:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(114,7,utf8_decode("Abdomen:"),1,0,'L',false);
	            $this->pdf->Cell(76,7,utf8_decode("RHA:"),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(190,7,utf8_decode("Neuro:"),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (".$comentario.")"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(63,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Cell(64,7,utf8_decode(""),1,0,'L',false);
	            $this->pdf->Ln(15);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información médica que requiera para la evaluación de expediente médico."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);
			}
			

			$this->pdf->Output("uploads/".$data['idsiniestro'].".pdf", 'F');
			$this->load->view('templates/detalle_pdf',$data);
		}else{
			$this->load->view('templates/login.php');
		}			
	}

	function reimprimir_cobertura($idsiniestro,$idplandetalle){
		//load session library
		$this->load->library('session');

		//restrict users to go back to login if session has been set
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			extract($user);		
			$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
			$data['certase_id'] = $getSin['certase_id'];
			$data['idsiniestro'] = $idsiniestro;
			$dni = $getSin['aseg_numDoc'];
			$afiliado = $getSin['afiliado'];
			$num = $getSin['num_orden_atencion'];
			$fecha_atencion = $getSin['fecha_atencion'];
			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$getCobertura = $this->siniestro_mdl->getCobertura($idplandetalle);
			$detalle = $getCobertura['descripcion'];
			$nombre_var = $getCobertura['nombre_var'];
			$idvariableplan = $getCobertura['idvariableplan'];
			$data['idplandetalle'] = $idplandetalle;

			switch($getCobertura['tiempo']){
            case '':
                $eventos = "Eventos ilimitados";
                break;
            case '1 month':
                $eventos = $getCobertura['num_eventos']." eventos mensuales";
                break;
            case '2 month':
                $eventos = $getCobertura['num_eventos']." eventos bimestrales";
                break;
            case '3 month':
                $eventos = $getCobertura['num_eventos']." eventos trimestrales";
                break;
            case '6 month':
                $eventos = $getCobertura['num_eventos']." eventos semestrales";
                break;
            case '1 year':
                $eventos = $getCobertura['num_eventos']." eventos anuales";
                break;
       		 }

       		 $med = $this->siniestro_mdl->getMedicamentos($idsiniestro);
       		 $detalle2 = $this->siniestro_mdl->detProducto($data);

       		 foreach ($med as $m2) {
       		 	$diagnostico = $m2->dianostico_temp;
       		 }

			// Crear formato de consulta
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln(); 
			    $this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMATO DE VALIDACIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,35,190);	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',13);
	          	$this->pdf->MultiCell(190,8,utf8_decode($nombre_var),0,'R',true);
	          	$this->pdf->Ln();          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(190,6,utf8_decode("CONDICIONES: ".$getCobertura['nombre_plan']),0,'L',true);
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->MultiCell(190,6,utf8_decode($nombre_var.': '.$detalle.', '.$eventos),1,'J');
			    $this->pdf->SetFont('Arial','B',10); 
			    if($idvariableplan==2){
			    $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
	            $this->pdf->Ln();			    	
			    }
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Items consultados: "),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(10,7,utf8_decode(" N°"),1,0,'L',false);
			    $this->pdf->Cell(140,7,utf8_decode(" Descripción"),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Cubierto"),1,0,'L',false);
			    $this->pdf->Ln();
			    $cont=1;
			    $this->pdf->SetFont('Arial','',10); 
			    if($idvariableplan==2){
			    	foreach ($med as $m) {			    	
				    $this->pdf->Cell(10,7,$cont,1,0,'L',false);
				    $this->pdf->Cell(140,7,utf8_decode($m->nombre_med),1,0,'L',false);
				    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'C',false);
				    $this->pdf->Ln();
				    $cont++;
			    	}	
			    }else{
			    	foreach ($detalle2 as $d2) {			    	
				    $this->pdf->Cell(10,7,$cont,1,0,'L',false);
				    $this->pdf->Cell(140,7,utf8_decode($d2->descripcion_prod),1,0,'L',false);
				    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'C',false);
				    $this->pdf->Ln();
				    $cont++;
			    	}	
			    }
			    		   
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Sólo la impresión de éste formato de consulta, garantiza la aprobación de los items consultados para su facturación."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->Output("uploads/".$idsiniestro.".pdf", 'F');
				$this->load->view('templates/detalle_pdf',$data);
		}else{
			$this->load->view('templates/login.php');
		}		
	}

	public function PopUp($idcita){
		$cita = $this->siniestro_mdl->getCita($idcita);
		$data['obs'] = $cita['observaciones_cita'];
		$data['colaborador'] = $cita['colaborador'];
		$this->load->view('templates/popup.php', $data);
	}

	public function reg_triaje($aseg_id,$idsiniestro){
		$user = $this->session->userdata('user');
		extract($user);		
		$getIdDetPlan = $this->siniestro_mdl->getIdDetPlan($idsiniestro);
		$data['idplandetalle'] = $getIdDetPlan['idplandetalle'];
		$data['aseg_id'] = $aseg_id;
		$data['idsiniestro'] = $idsiniestro;
		$triaje = $this->siniestro_mdl->getTriajeMedicamentos($data);
		$data['especialidad'] = $triaje['descripcion_prod'];		
		if($triaje['est_tr'] == 0){
			$this->load->view('templates/triaje.php',$data);
		}else{
			if($triaje['est_md']==0){				
				$getSiniestroDiagnostico = $this->siniestro_mdl->getSiniestroDiagnostico($data);
				$data['siniestro_diagnostico'] = $getSiniestroDiagnostico;
				$this->load->view('templates/medicamentos_cubiertos.php',$data);
			}else{
			$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
			$data['certase_id'] = $getSin['certase_id'];
			$data['idsiniestro'] = $idsiniestro;
			$dni = $getSin['aseg_numDoc'];
			$afiliado = $getSin['afiliado'];
			$num = $getSin['num_orden_atencion'];
			$especialidad = $getSin['descripcion_prod'];
			$plan = $getSin['nombre_plan'];
			$fech_nac = $getSin['aseg_fechNac'];
			$fecha_atencion = $getSin['fecha_atencion'];
			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$nombre_var = "MEDICAMENTOS GENÉRICOS";
			$texto_web = "";
			$med = $this->siniestro_mdl->getMedicamentos($idsiniestro);

       		 foreach ($med as $m2) {
       		 	$diagnostico = $m2->dianostico_temp;
       		 }

		// Crear formato de consulta
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();  
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	    
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,40,190);	      	
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$dni,1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($afiliado),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Especialidad: ".$especialidad),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: "),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			     $coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($c->idvariableplan==2){
			    		$nombre_var = $c->nombre_var;
			    		$texto_web = $c->texto_web;
			    	}elseif($c->idvariableplan==38){
			    		$nombre_var2 = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}			    	
			    }
			    foreach ($coberturas as $c) {
			   		$this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var.': '.$c->texto_web),1,'J');
			   	}      

			   	$getTriaje = $this->siniestro_mdl->getTriaje($idsiniestro);

	            $this->pdf->SetFont('Arial','B',9);
	           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(190,7,utf8_decode($getTriaje['motivo_consulta']),1,0,'L',false);	
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
	            $this->pdf->Ln(); 
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(38,7,utf8_decode("PA: ".$getTriaje['presion_arterial_mm']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FC: ".$getTriaje['frec_cardiaca']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FR: ".$getTriaje['frec_respiratoria']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Peso(kg): ".$getTriaje['peso']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Talla(cm): ".$getTriaje['talla']),1,0,'L',false);	
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("Cabeza: ".$getTriaje['estado_cabeza']),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras: ".$getTriaje['piel_faneras']),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("CV:RC: ".$getTriaje['cv_ruido_cardiaco']),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("TP:MV: ".$getTriaje['tp_murmullo_vesicular']),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(114,7,utf8_decode("Abdomen: ".$getTriaje['estado_abdomen']),1,0,'L',false);
	            $this->pdf->Cell(76,7,utf8_decode("RHA: ".$getTriaje['ruido_hidroaereo']),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(190,7,utf8_decode("Neuro: ".$getTriaje['estado_neurologico']),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (Según el primer diagnóstico, cubiertos sólo en presentación genérica)"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $getTratamiento = $this->siniestro_mdl->getTratamiento($idsiniestro);
	            $this->pdf->Ln();
	            foreach ($getTratamiento as $t) {
	            	$this->pdf->Cell(64,7,utf8_decode($t->nombre_med),1,0,'L',false);
	            	$this->pdf->Cell(63,7,utf8_decode($t->cantidad_trat),1,0,'L',false);
	            	$this->pdf->Cell(64,7,utf8_decode($t->dosis_trat),1,0,'L',false);
	            	$this->pdf->Ln();
	            }
	            $this->pdf->Ln(14);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información médica que requiera para la evaluación de expediente médico."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);
	            $this->pdf->SetTextColor(0,0,0);
	            // Coberturas validadas
	            $getValidacion = $this->siniestro_mdl->getValidacion($idsiniestro);
	            if(!empty($getValidacion)){
	            $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln(); 
			    $this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMATO DE VALIDACIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,35,190);	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',13);
	          	$this->pdf->MultiCell(190,8,utf8_decode("COBERTURAS VALIDADAS"),0,'R',true);
	          	$this->pdf->Ln();          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln(); 
	          	$this->pdf->SetFont('Arial','B',10); 	        
			    $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->SetTextColor(0,0,0);
	            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
	            $this->pdf->Ln();			
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Items consultados: "),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(10,7,utf8_decode(" N°"),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" Cobertura"),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" Descripción"),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Cubierto"),1,0,'L',false);
			    $this->pdf->Ln();	
	          	$this->pdf->SetFont('Arial','',10);
			    $cont=1;
			    foreach ($getValidacion as $v) {
			   	$this->pdf->Cell(10,7,utf8_decode(" ".$cont),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" ".$v->nombre_var),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" ".$v->descripcion_prod),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'L',false);
			    $this->pdf->Ln();	
			    $cont++;
			    }		    		   
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Sólo la impresión de éste formato de consulta, garantiza la aprobación de los items consultados para su facturación."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
	        	}
			    $this->pdf->Output("uploads/".$idsiniestro.".pdf", 'F');
				$this->load->view('templates/detalle_pdf',$data);
			}
			
		}
	}

	public function guardar_triaje(){
		$data['aseg_id'] = $_POST['aseg_id'];
		$idsiniestro = $_POST['idsiniestro'];
		$data['idsiniestro'] = $idsiniestro;
		$data['motivo'] = $_POST['motivo'];
		$data['pa'] = $_POST['pa'];
		$data['fc'] = $_POST['fc'];
		$data['fr'] = $_POST['fr'];
		$data['peso'] = $_POST['peso'];
		$data['talla'] = $_POST['talla'];
		$data['cabeza'] = $_POST['cabeza'];
		$data['piel_faneras'] = $_POST['piel_faneras'];
		$data['cv_cr'] = $_POST['cv_cr'];
		$data['tp_mv'] = $_POST['tp_mv'];
		$data['abdomen'] = $_POST['abdomen'];
		$data['rha'] = $_POST['rha'];
		$data['neuro'] = $_POST['neuro'];
		$data['osteomuscular'] = $_POST['osteomuscular'];
		$data['gu_ppl'] = $_POST['gu_ppl'];
		$data['gu_pru'] = $_POST['gu_pru'];
		$getIdDetPlan = $this->siniestro_mdl->getIdDetPlan($idsiniestro);
		$data['idplandetalle'] = $getIdDetPlan['idplandetalle'];
		$this->siniestro_mdl->inTriaje($data);
		$this->siniestro_mdl->upSiniestroTriaje($data);
		$triaje = $this->siniestro_mdl->getTriajeMedicamentos($data);
		$data['especialidad'] = $triaje['descripcion_prod'];
		$this->load->view('templates/medicamentos_cubiertos.php',$data);
	} 

	public function guardar_medicamentos2(){
		$user = $this->session->userdata('user');
		extract($user);		
		$idsiniestro = $_POST['idsiniestro'];
		$data['idsiniestro'] = $idsiniestro;
		$getSiniestroDiagnostico = $this->siniestro_mdl->getSiniestroDiagnostico($data);
		foreach ($getSiniestroDiagnostico as $sd) {
			$idtratamiento = $sd->idtratamiento;
			$data['cantidad'] = $_POST['cantidad'.$idtratamiento];
			$data['dosis'] = $_POST['dosis'.$idtratamiento];
			$data['idtratamiento'] = $idtratamiento;
			$this->siniestro_mdl->upTratamiento($data);
		}		
			$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
			$data['certase_id'] = $getSin['certase_id'];
			$this->siniestro_mdl->upSiniestroMed($data);
			$data['idsiniestro'] = $idsiniestro;
			$dni = $getSin['aseg_numDoc'];
			$afiliado = $getSin['afiliado'];
			$num = $getSin['num_orden_atencion'];
			$especialidad = $getSin['descripcion_prod'];
			$plan = $getSin['nombre_plan'];
			$fech_nac = $getSin['aseg_fechNac'];
			$fecha_atencion = $getSin['fecha_atencion'];
			$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
			$nombre_var = "MEDICAMENTOS GENÉRICOS";
			$texto_web = "";
			$med = $this->siniestro_mdl->getMedicamentos($idsiniestro);

       		 foreach ($med as $m2) {
       		 	$diagnostico = $m2->dianostico_temp;
       		 }

		// Crear formato de consulta
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();  
	          	$this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	    
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,40,190);	      	
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$dni,1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($afiliado),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Especialidad: ".$especialidad),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: "),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			     $coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($c->idvariableplan==2){
			    		$nombre_var = $c->nombre_var;
			    		$texto_web = $c->texto_web;
			    	}elseif($c->idvariableplan==38){
			    		$nombre_var2 = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}			    	
			    }
			    foreach ($coberturas as $c) {
			   		$this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var.': '.$c->texto_web),1,'J');
			   	}      

			   	$getTriaje = $this->siniestro_mdl->getTriaje($idsiniestro);

	            $this->pdf->SetFont('Arial','B',9);
	           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(190,7,utf8_decode($getTriaje['motivo_consulta']),1,0,'L',false);	
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
	            $this->pdf->Ln(); 
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(38,7,utf8_decode("PA: ".$getTriaje['presion_arterial_mm']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FC: ".$getTriaje['frec_cardiaca']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("FR: ".$getTriaje['frec_respiratoria']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Peso(kg): ".$getTriaje['peso']),1,0,'L',false);
	            $this->pdf->Cell(38,7,utf8_decode("Talla(cm): ".$getTriaje['talla']),1,0,'L',false);	
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("Cabeza: ".$getTriaje['estado_cabeza']),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras: ".$getTriaje['piel_faneras']),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(76,7,utf8_decode("CV:RC: ".$getTriaje['cv_ruido_cardiaco']),1,0,'L',false);
	            $this->pdf->Cell(114,7,utf8_decode("TP:MV: ".$getTriaje['tp_murmullo_vesicular']),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(114,7,utf8_decode("Abdomen: ".$getTriaje['estado_abdomen']),1,0,'L',false);
	            $this->pdf->Cell(76,7,utf8_decode("RHA: ".$getTriaje['ruido_hidroaereo']),1,0,'L',false);
	            $this->pdf->Ln(); 
	            $this->pdf->Cell(190,7,utf8_decode("Neuro: ".$getTriaje['estado_neurologico']),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (Según el primer diagnóstico, cubiertos sólo en presentación genérica)"),0,0,'L',false);	            
	            $this->pdf->SetFont('Arial','',9);
	            $this->pdf->Ln();
	            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
	            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
	            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
	            $getTratamiento = $this->siniestro_mdl->getTratamiento($idsiniestro);
	            $this->pdf->Ln();
	            foreach ($getTratamiento as $t) {
	            	$this->pdf->Cell(64,7,utf8_decode($t->nombre_med),1,0,'L',false);
	            	$this->pdf->Cell(63,7,utf8_decode($t->cantidad_trat),1,0,'L',false);
	            	$this->pdf->Cell(64,7,utf8_decode($t->dosis_trat),1,0,'L',false);
	            	$this->pdf->Ln();
	            }
	            $this->pdf->Ln(14);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información médica que requiera para la evaluación de expediente médico."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);
	            $this->pdf->SetTextColor(0,0,0);
	            // Coberturas validadas
	            $getValidacion = $this->siniestro_mdl->getValidacion($idsiniestro);
	            if(!empty($getValidacion)){
	            $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln(); 
			    $this->pdf->SetFont('Arial','B',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMATO DE VALIDACIÓN"),0,'R',false);
	          	$this->pdf->Ln(10);	    	          	
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,35,190);	
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
	          	$this->pdf->SetFont('Arial','B',13);
	          	$this->pdf->MultiCell(190,8,utf8_decode("COBERTURAS VALIDADAS"),0,'R',true);
	          	$this->pdf->Ln();          	
			    $this->pdf->SetFont('Arial','',10);
			    $this->pdf->SetTextColor(0,0,0); 
	          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
	          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
	          	$this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
			    $this->pdf->SetTextColor(0,0,0); 
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
			    $this->pdf->Ln(); 
	          	$this->pdf->SetFont('Arial','B',10); 	        
			    $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
	            $this->pdf->Ln(8);
	            $this->pdf->SetFont('Arial','',10);
	            $this->pdf->SetTextColor(0,0,0);
	            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
	            $this->pdf->Ln();			
			    $this->pdf->SetFont('Arial','B',10); 
			    $this->pdf->Cell(190,7,utf8_decode("Items consultados: "),0,0,'L',false);	          	
			    $this->pdf->Ln();
			    $this->pdf->Cell(10,7,utf8_decode(" N°"),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" Cobertura"),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" Descripción"),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Cubierto"),1,0,'L',false);
			    $this->pdf->Ln();	
			    $this->pdf->SetFont('Arial','',10);
			    $cont=1;
			    foreach ($getValidacion as $v) {
			   	$this->pdf->Cell(10,7,utf8_decode(" ".$cont),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" ".$v->nombre_var),1,0,'L',false);
			    $this->pdf->Cell(70,7,utf8_decode(" ".$v->descripcion_prod),1,0,'L',false);
			    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'L',false);
			    $this->pdf->Ln();	
			    $cont++;
			    }		    		   
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Sólo la impresión de éste formato de consulta, garantiza la aprobación de los items consultados para su facturación."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
	        	}
			    $this->pdf->Output("uploads/".$idsiniestro.".pdf", 'F');
				$this->load->view('templates/detalle_pdf',$data);
	}

	function guardar_medicamentos3(){
		$user = $this->session->userdata('user');
		extract($user);	
		$data['dianostico_temp'] = $_POST['dianostico_temp'];
		$idsiniestro = $_POST['idsiniestro'];
		$data['idsiniestro'] = $idsiniestro;
		$idplandetalle = $_POST['idplandetalle'];
		$data['idplandetalle'] = $idplandetalle;
		$medicamentos = $_POST['chk'];
		$cant = count($medicamentos);
		$getSiniestroCertificado = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro);
		$aseg_id = $getSiniestroCertificado['aseg_id'];		

		$this->siniestro_mdl->inSiniestroDiagnostico($data);
		$id= $this->db->insert_id();
		$data['idsiniestrodiagnostico'] = $id;

		for($i=0;$i<$cant;$i++){
			$data['idmedicamento'] = $medicamentos[$i];
			$this->siniestro_mdl->inTratamiento($data);
		}


		$vez_evento = $this->siniestro_mdl->vez_Evento($data);
		if(empty($vez_evento)){
			$data['vez_actual'] = 1;
		}else{
			foreach ($vez_evento as $ve) {
			$data['vez_actual'] = $ve->vez_evento;	
			}
		}
		
		$this->siniestro_mdl->inSiniestroDetalle($data);
		redirect(base_url()."index.php/reg_triaje/".$aseg_id."/".$idsiniestro);
	}

	public function reimprimir_atencion_copia($aseg_id,$idsiniestro){
		$user = $this->session->userdata('user');
		extract($user);	
		date_default_timezone_set('America/Lima');
		$hoy = date("d/m/Y H:i");
		$getIdDetPlan = $this->siniestro_mdl->getIdDetPlan($idsiniestro);
		$data['aseg_id'] = $aseg_id;
		$data['idsiniestro'] = $idsiniestro;
		$triaje = $this->siniestro_mdl->getTriajeMedicamentos($data);
		$data['especialidad'] = $triaje['descripcion_prod'];	
		$sin = $this->siniestro_mdl->getProveedor($idsiniestro);
		$nombre_comercial_pr = $sin['nombre_comercial_pr'];
		$getSin = $this->siniestro_mdl->getSiniestroCertificado($idsiniestro); 
		$data['certase_id'] = $getSin['certase_id'];
		$data['idsiniestro'] = $idsiniestro;
		$dni = $getSin['aseg_numDoc'];
		$nombre_plan = $getSin['nombre_plan'];
		$afiliado = $getSin['afiliado'];
		$num = $getSin['num_orden_atencion'];
		$especialidad = $getSin['descripcion_prod'];
		$plan = $getSin['nombre_plan'];
		$fech_nac = $getSin['aseg_fechNac'];
		$fecha_atencion = $getSin['fecha_atencion'];
		$fecha_atencion = date('d/m/Y',strtotime($fecha_atencion));
		$nombre_var = "MEDICAMENTOS GENÉRICOS";
		$texto_web = "";
		$med = $this->siniestro_mdl->getMedicamentos($idsiniestro);
		$diagnostico = '';

			if(!empty($med)){
       		 	foreach ($med as $m2) {
       		 		$diagnostico = $m2->dianostico_temp;
       			}
       		}

		// Crear formato de consulta
			$this->load->library('Pdf');
	        $this->pdf = new Pdf();

			    $this->pdf->AddPage();
			    $this->pdf->AliasNbPages();
			    $this->pdf->Ln();  
	          	$this->pdf->SetFont('Arial','B',10); 	          	
	          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','',10); 
	          	$this->pdf->MultiCell(0,6,utf8_decode("FORMULARIO DE ORDEN DE ATENCIÓN"),0,'R',false);
	          	$this->pdf->Ln(-2);
	          	$this->pdf->SetFont('Arial','I',8); 	
	          	$this->pdf->MultiCell(0,6,utf8_decode("Fecha de Impresión: ".$hoy),0,'R',false);
	          	$this->pdf->Ln(10);	    
	          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,40,190);	      	
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("ORDEN DE ATENCIÓN N°".$num),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFont('Arial','',9);
	    		$this->pdf->SetTextColor(0,0,0); 	    		
			    $this->pdf->Cell(47,7,"DNI: ".$dni,1,0,'L',false);
			    $this->pdf->Cell(104,7,"Paciente: ".utf8_decode($afiliado),1,0,'L',false);
			    $this->pdf->Cell(39,7,"Fech. Nac: ".$fech_nac,1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(47,7,utf8_decode("Fecha de Atención: ".$fecha_atencion),1,0,'L',false);
			    $this->pdf->Cell(143,7,utf8_decode("Lugar de Atención: ".$nombre_comercial_pr),1,0,'L',false);
			    $this->pdf->Ln();
			    $this->pdf->Cell(190,7,utf8_decode("Especialidad: ".$especialidad),1,0,'L',false);
			    $this->pdf->SetFont('Arial','B',10);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(0,0,0);
			    $this->pdf->SetTextColor(255,255,255); 
			    $this->pdf->Cell(190,7,utf8_decode("CONDICIONES DEL PLAN: ".$nombre_plan),1,0,'L',true);
			    $this->pdf->Ln();
			    $this->pdf->SetFillColor(213,210,210);
			    $this->pdf->SetTextColor(0,0,0);
			    $this->pdf->SetFont('Arial','',8);
			   	$coberturas = $this->siniestro_mdl->coberturas($data);
			    foreach ($coberturas as $c) {
			    	if($c->idvariableplan==2){
			    		$nombre_var = $c->nombre_var;
			    		$texto_web = $c->texto_web;
			    	}elseif($c->idvariableplan==38){
			    		$nombre_var2 = $c->nombre_var;
			    		$texto_web2 = $c->texto_web;
			    	}			    	
			    }
			    foreach ($coberturas as $c) {
			   		$this->pdf->MultiCell(190,6,utf8_decode($c->nombre_var.': '.$c->texto_web),1,'J');
			   	}      

			   	$getTriaje = $this->siniestro_mdl->getTriaje($idsiniestro);

			   	if(!empty($getTriaje)){
				    $this->pdf->SetFont('Arial','B',9);
		           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Cell(190,7,utf8_decode($getTriaje['motivo_consulta']),1,0,'L',false);	
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','B',9);
		            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
		            $this->pdf->Ln(); 
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Cell(38,7,utf8_decode("PA: ".$getTriaje['presion_arterial_mm']),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("FC: ".$getTriaje['frec_cardiaca']),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("FR: ".$getTriaje['frec_respiratoria']),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("Peso(kg): ".$getTriaje['peso']),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("Talla(cm): ".$getTriaje['talla']),1,0,'L',false);	
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(76,7,utf8_decode("Cabeza: ".$getTriaje['estado_cabeza']),1,0,'L',false);
		            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras: ".$getTriaje['piel_faneras']),1,0,'L',false);
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(76,7,utf8_decode("CV:RC: ".$getTriaje['cv_ruido_cardiaco']),1,0,'L',false);
		            $this->pdf->Cell(114,7,utf8_decode("TP:MV: ".$getTriaje['tp_murmullo_vesicular']),1,0,'L',false);
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(114,7,utf8_decode("Abdomen: ".$getTriaje['estado_abdomen']),1,0,'L',false);
		            $this->pdf->Cell(76,7,utf8_decode("RHA: ".$getTriaje['ruido_hidroaereo']),1,0,'L',false);
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(190,7,utf8_decode("Neuro: ".$getTriaje['estado_neurologico']),1,0,'L',false);
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','B',9);
		            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','B',9);
		            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (Según el primer diagnóstico, cubiertos sólo en presentación genérica)"),0,0,'L',false);	            
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Ln();
		            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
		            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
		            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
			   	}else{
				   	$this->pdf->SetFont('Arial','B',9);
		           	$this->pdf->Cell(190,7,utf8_decode("Motivo de consulta"),0,0,'L',false);	           	
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Cell(190,7,'',1,0,'L',false);	
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','B',9);
		            $this->pdf->Cell(190,7,utf8_decode("Exámen Físico / Historia Actual"),0,0,'L',false);	           	
		            $this->pdf->Ln(); 
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Cell(38,7,utf8_decode("PA: "),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("FC: "),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("FR: "),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("Peso(kg): "),1,0,'L',false);
		            $this->pdf->Cell(38,7,utf8_decode("Talla(cm): "),1,0,'L',false);	
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(76,7,utf8_decode("Cabeza: "),1,0,'L',false);
		            $this->pdf->Cell(114,7,utf8_decode("Piel y Faneras: "),1,0,'L',false);
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(76,7,utf8_decode("CV:RC: "),1,0,'L',false);
		            $this->pdf->Cell(114,7,utf8_decode("TP:MV: "),1,0,'L',false);
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(114,7,utf8_decode("Abdomen: "),1,0,'L',false);
		            $this->pdf->Cell(76,7,utf8_decode("RHA: "),1,0,'L',false);
		            $this->pdf->Ln(); 
		            $this->pdf->Cell(190,7,utf8_decode("Neuro: "),1,0,'L',false);
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','B',9);
		            $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
		            $this->pdf->Ln();
		            $this->pdf->SetFont('Arial','B',9);
		            $this->pdf->Cell(190,7,utf8_decode("Tratamiento (Según el primer diagnóstico, cubiertos sólo en presentación genérica)"),0,0,'L',false);	            
		            $this->pdf->SetFont('Arial','',9);
		            $this->pdf->Ln();
		            $this->pdf->Cell(64,7,utf8_decode("Medicamento"),1,0,'C',false);
		            $this->pdf->Cell(63,7,utf8_decode("Cantidad"),1,0,'C',false);
		            $this->pdf->Cell(64,7,utf8_decode("Dosis"),1,0,'C',false);
			   	}

	           
	            $getTratamiento = $this->siniestro_mdl->getTratamiento($idsiniestro);
	            $this->pdf->Ln();
	            if(!empty($getTratamiento)){
		            foreach ($getTratamiento as $t) {
		            	$this->pdf->Cell(64,7,utf8_decode($t->nombre_med),1,0,'L',false);
		            	$this->pdf->Cell(63,7,utf8_decode($t->cantidad_trat),1,0,'L',false);
		            	$this->pdf->Cell(64,7,utf8_decode($t->dosis_trat),1,0,'L',false);
		            	$this->pdf->Ln();
		            }
	        	} else{
	        		$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Cell(63,7,"",1,0,'L',false);
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Ln();
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Cell(63,7,"",1,0,'L',false);
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Ln();
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Cell(63,7,"",1,0,'L',false);
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Ln();
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Cell(63,7,"",1,0,'L',false);
	            	$this->pdf->Cell(64,7,"",1,0,'L',false);
	            	$this->pdf->Ln();
	        	}
	            $this->pdf->Ln(14);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("________________________"),0,0,'C',false);
	            $this->pdf->Ln();
	            $this->pdf->Cell(95,7,utf8_decode("Médico Tratante"),0,0,'C',false);
	            $this->pdf->Cell(95,7,utf8_decode("Titular y/o Paciente"),0,0,'C',false);
	            $this->pdf->Ln(10);
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Mediante el presente autorizo a Red Salud se le proporcione toda información médica que requiera para la evaluación de expediente médico."),0,0,'L',false);
			    $this->pdf->SetFillColor(200,200,200);
	            $this->pdf->SetTextColor(0,0,0);
	            // Coberturas validadas
	            $getValidacion = $this->siniestro_mdl->getValidacion($idsiniestro);
	            if(!empty($getValidacion)){
		            $this->pdf->AddPage();
				    $this->pdf->AliasNbPages();
				    $this->pdf->Ln(); 
				    $this->pdf->SetFont('Arial','B',10); 
		          	$this->pdf->MultiCell(0,6,utf8_decode($nombre_comercial_pr),0,'R',false);
		          	$this->pdf->Ln(-2);
		          	$this->pdf->SetFont('Arial','',10); 
		          	$this->pdf->MultiCell(0,6,utf8_decode("FORMATO DE VALIDACIÓN"),0,'R',false);
		          	$this->pdf->Ln(10);	    	          	
		          	$this->pdf->Image(base_url().'assets/images/copia.jpg',0,35,190);	
				    $this->pdf->SetFillColor(0,0,0);
				    $this->pdf->SetTextColor(255,255,255); 
		          	$this->pdf->SetFont('Arial','B',13);
		          	$this->pdf->MultiCell(190,8,utf8_decode("COBERTURAS VALIDADAS"),0,'R',true);
		          	$this->pdf->Ln();          	
				    $this->pdf->SetFont('Arial','',10);
				    $this->pdf->SetTextColor(0,0,0); 
		          	$this->pdf->Cell(100,7,"Paciente: ".utf8_decode($afiliado),0,0,'L',false);
		          	$this->pdf->Cell(50,7,utf8_decode("Orden Atención N°: "),0,0,'R',false);  
		          	$this->pdf->SetFillColor(0,0,0);
				    $this->pdf->SetTextColor(255,255,255); 
				    $this->pdf->Cell(40,7,utf8_decode($num),1,0,'L',true);    	
				    $this->pdf->SetTextColor(0,0,0); 
				    $this->pdf->Ln();
				    $this->pdf->Cell(190,7,"DNI: ".utf8_decode($dni),0,0,'L',false);
				    $this->pdf->Ln();
				    $this->pdf->Cell(190,7,utf8_decode("Fecha Atención: ".$fecha_atencion),0,0,'L',false);
				    $this->pdf->Ln();
				    $this->pdf->Cell(190,7,utf8_decode("Lugar Atención: ".$nombre_comercial_pr),0,0,'L',false);
				    $this->pdf->Ln(); 
		          	$this->pdf->SetFont('Arial','B',10); 	        
				    $this->pdf->Cell(190,7,utf8_decode("Diagnóstico"),0,0,'L',false);
		            $this->pdf->Ln(8);
		            $this->pdf->SetFont('Arial','',10);
		            $this->pdf->SetTextColor(0,0,0);
		            $this->pdf->Cell(190,7,utf8_decode($diagnostico),1,0,'L',false);
		            $this->pdf->Ln();			
				    $this->pdf->SetFont('Arial','B',10); 
				    $this->pdf->Cell(190,7,utf8_decode("Items consultados: "),0,0,'L',false);
				    $this->pdf->Ln();
				    $this->pdf->Cell(10,7,utf8_decode(" N°"),1,0,'L',false);
				    $this->pdf->Cell(70,7,utf8_decode(" Cobertura"),1,0,'L',false);
				    $this->pdf->Cell(70,7,utf8_decode(" Descripción"),1,0,'L',false);
				    $this->pdf->Cell(40,7,utf8_decode(" Cubierto"),1,0,'L',false);
				    $this->pdf->Ln();	
		          	$this->pdf->SetFont('Arial','',10);
				    $cont=1;
				    foreach ($getValidacion as $v) {
					   	$this->pdf->Cell(10,7,utf8_decode(" ".$cont),1,0,'L',false);
					    $this->pdf->Cell(70,7,utf8_decode(" ".$v->nombre_var),1,0,'L',false);
					    $this->pdf->Cell(70,7,utf8_decode(" ".$v->descripcion_prod),1,0,'L',false);
					    $this->pdf->Cell(40,7,utf8_decode(" Sí"),1,0,'L',false);
					    $this->pdf->Ln();	
				    $cont++;
			    	}	
			    }else{
			    	$this->pdf->Ln();
			    }	    		   
	            $this->pdf->SetFont('Arial','I',8);
	            $this->pdf->Cell(190,7,utf8_decode("* Sólo la impresión de éste formato de consulta, garantiza la aprobación de los items consultados para su facturación."),0,0,'L',false);
	            $this->pdf->SetTextColor(243,45,45); 
	            $this->pdf->Ln();
	            $this->pdf->SetFont('Arial','B',9);
	            $this->pdf->MultiCell(190,6,utf8_decode("Si tuviera algun problema o consulta, puede comunicarse con Red-Salud."),0,'J');
	            $this->pdf->Ln();
	            $this->pdf->MultiCell(190,6,utf8_decode("Central Telefónica: (01) 445-3019. RPM: #999908022. Email: contacto@red-salud.com"),0,'J');
	            $this->pdf->SetTextColor(0,0,0);
	            $this->pdf->Output("uploads/".$idsiniestro.".pdf", 'F');
				$this->load->view('templates/detalle_pdf',$data);	
		
	}

}