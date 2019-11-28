<?php 
$user = $this->session->userdata('user');
extract($user);
?>
<html lang="es">
<head>
  <meta name="theme-color" content="#c63538">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>

  <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url()?>assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?=base_url()?>assets/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="<?=base_url()?>assets/images/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <meta name="geo.placename" content="Lima, Peru">
  <meta name="geo.region" content="PE-LIM">

  <title>Red Salud :: Un mundo de posibilidades para tu salud</title>
  <meta name="description" content="Somos una empresa fundada en Miami, Florida; con más de 25 años de experiencia internacional en temas de salud. Estamos especializados en la creación, gestión y administración de planes y producto masivo.">
  <meta name="keywords" content="red salud, red medica, planes de salud, cuida a tu familia, salud, medicina, medicos en peru, medicos en lima, seguros medicos, seguros de salud">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/custom.8.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/css/animate.min.css">
  <!-- FancyBox -->
  <!-- Add jQuery library -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js"></script>
    
  <!-- Add mousewheel plugin (this is optional) -->
  <script type="text/javascript" src="<?=  base_url()?>assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
  <!-- para paginacion -->
    <script src="<?=base_url()?>assets/pagination/jquery.dataTables.min.css"></script>
    <script src="<?=base_url()?>assets/pagination/jquery-1.12.4.js"></script>
    <script src="<?=base_url()?>assets/pagination/jquery.dataTables.min.js"></script>
    <script src="<?=base_url()?>assets/pagination/dataTables.bootstrap.min.js"></script>

  <!-- Add fancyBox -->
  <link rel="stylesheet" href="<?=  base_url()?>assets/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
  <script type="text/javascript" src="<?=  base_url()?>assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
  <script>
        $(".fancybox")
        .attr('rel', 'gallery')
        .fancybox({
            type: 'iframe',
            autoSize : false,
            beforeLoad : function() {         
                this.width  = parseInt(this.element.data('fancybox-width'));  
                this.height = parseInt(this.element.data('fancybox-height'));
            }
        });
    </script>
  <!-- script para el autocomplete de diagnostico -->

  <script type="text/javascript" src="<?=base_url()?>assets/jqueryAutocomplete/jquery.js"></script>

  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/jqueryAutocomplete/jquery.autocomplete.css" />
  <script type="text/javascript" src="<?=base_url()?>assets/jqueryAutocomplete/jquery.js"></script>
  <script type="text/javascript" src="<?=base_url()?>assets/jqueryAutocomplete/jquery.autocomplete.js"></script>
  <script>
    jQuery.noConflict(); 
    var j = jQuery.noConflict();
   j(document).ready(function(){
    j("#dianostico_temp").autocomplete("<?=base_url()?>assets/jqueryAutocomplete/autocomplete.php", {
          selectFirst: true
    });
    j("#sin_diagnosticoSec").autocomplete("<?=base_url()?>assets/jqueryAutocomplete/autocomplete.php", {
          selectFirst: true
    });
   });
  </script>


  <!-- Global site tag (gtag.js) - Google Analytics -->

<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-132525819-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-132525819-1');
</script>
</head>
<body>

<?php include(APPPATH."views/templates/header.php"); ?>

<section class="seccion-interior">
    <div class="container">
      <br><br><br>
    <div class="row">
      <div class="col-12">
        <div class="seccion-title-int">
          <h1><?=$canal?>: <?=$plan?> </h1>
        </div>
      </div>
    </div>
    <div class="row justify-content-md-center">
      <div class="col-12 col-sm-3">
      <br>
        <div style="text-align: left;" class="CMSImg">         
          <div>
            <h4><?=$afiliado?></h4>
            <p><b>DNI:</b> <?=$dni?></p>
            <p><b>Fecha de Nacimiento:</b> <?=$fech_nac?></p>
            <p><b>Edad:</b> <?=$edad?> años</p>
            <p><b>Dirección:</b> <?=$direccion?></p>
            <p><b>Teléfono:</b> <?=$telefono?></p>
            <p style="<?php if($e2<>1){ echo "color:red;";} ?>"><b>Estado:</b> <?=$estado2?></p>
            <p><b><?php if($estado2=="En Consulta Médica"){ echo "Fecha de Consulta:";} else{echo "Última Atención:";} ?></b> <?=$ultima_atencion?></p>
           <!--  <p><?=$dif?></p> -->
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-9">
        <br>
        <div class="CMS">          
          <div class="Block" style="margin-bottom:10px; margin-left:14px; margin-right:0px; margin-top:0px; width:93%">
            <table class="table table-responsive-md table-striped table-bordered table-hover">
              <thead>
                <th colspan="2"><input type="image" width="8%" src="<?=base_url()?>assets/images/aqui.gif"> Validar Cobertura</th>
                <th style="text-align: center;">Copago/Coaseguro</th>
                <th style="text-align: center;">Eventos</th>
                <th style="text-align: center;">No cubre</th>
              </thead>
              <?php
              foreach ($getCoberturas as $c) { 
                    if($c->num_eventos>0){
                      if($c->total_vez<>0 and $c->total_vez==$c->vez_actual){
                      }                     
                    }

                     $estado_cobertura='';
                     $titulo= 'CONSULTAR '.$c->nombre_var;
                     $img='https://www.red-salud.com/rsadmin/iconos/'.$c->idvariableplan.'.png';
                     $color="";
                      if($c->iniVig<>0){
                        $tiempo_cob = $c->iniVig;
                        $cert_iniVigc1 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
                        $cert_iniVigc2 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
                        $tiempo_cob2 = date('d/m/Y', ($cert_iniVigc2));
                        $fecha = time();
                        if($fecha<$cert_iniVigc1){
                          $estado_cobertura='disabled';
                          $titulo= 'Cobertura inactiva hasta el '.$tiempo_cob2;
                          $img='https://www.red-salud.com/rsadmin/iconos/bloqueada.png';
                          $color="red";
                        }
                      }

                      if($c->finVig<>0){
                        $tiempo_cob = $c->finVig;
                        $cert_finVigc1 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
                        $cert_finVigc2 = strtotime ( '+'.$tiempo_cob , strtotime ( $cert_iniVigc ) ) ;
                        $tiempo_cob2 = date('d/m/Y', ($cert_finVigc2));
                        $fecha = time();
                        if($fecha>$cert_finVigc1){
                          $estado_cobertura='disabled';
                          $titulo= 'Cobertura inactiva desde el '.$tiempo_cob2;
                          $img='https://www.red-salud.com/rsadmin/iconos/bloqueada.png';
                          $color="red";
                        }
                      }
              ?>     
              <form method="post" action="<?=base_url()?>index.php/generar_orden" id="form1" name="form1">
                <input type="hidden" name="cert_id" value="<?=$cert_id?>">
                <input type="hidden" name="aseg_id" value="<?=$aseg_id?>">
                <input type="hidden" name="certase_id" value="<?=$certase_id?>">
                <input type="hidden" name="idcita" id="idcita" value="<?=$idcita?>">
                <input type="hidden" name="tipo_orden" id="tipo_orden" value="<?=$tipo_orden?>">         
                    <tbody>
              <tr>
                <td width="5%" style="vertical-align: middle; text-align: center;">
                  <a class="boton fancybox" title="<?=$titulo?>" href="<?= base_url()?>index.php/detalle_cobertura/<?=$c->idplandetalle?>/<?=$certase_id?>/<?=$c->idvariableplan?>/<?=$e2?>/<?=$estado2?>" data-fancybox-width="950" data-fancybox-height="690">
                    <input type="image" height="50%" src="<?=$img?>" <?=$estado_cobertura?>> 
                  </a>
                </td>
                <td width="70%" style="vertical-align: middle;">
                  <span style="text-align:justify; color: <?=$color?>;">
                  <b><?=$c->nombre_var?></b> <?=$c->texto_web?>
                  </span>
                </td>
                <td style="color: <?=$color;?>; vertical-align: middle; text-align: center;"><?=$c->cobertura?></td>
                <td width="15%"  style="color: <?=$color;?>; vertical-align: middle; text-align: center;"><?php switch($c->tiempo){
                  case '':
                    echo "Ilimitados";
                    break;
                  case '1 month':
                    if($c->num_eventos==1){
                      $men = "evento al mes";
                    }else{
                      $men = "eventos mensuales";
                    }
                    echo $c->num_eventos." ".$men;
                    break;
                  case '2 month':
                     if($c->num_eventos==1){
                      $men = "evento bimestral";
                    }else{
                      $men = "eventos bimestrales";
                    }
                    echo $c->num_eventos." ".$men;
                    break;
                  case '3 month':
                    if($c->num_eventos==1){
                      $men = "evento trimestral";
                    }else{
                      $men = "eventos trimestrales";
                    }
                    echo $c->num_eventos." ".$men;
                    break;
                  case '6 month':
                    if($c->num_eventos==1){
                      $men = "evento semestral";
                    }else{
                      $men = "eventos semestrales";
                    }
                    echo $c->num_eventos." ".$men;
                    break;
                  case '1 year':
                    if($c->num_eventos==1){
                      $men = "evento al año";
                    }else{
                      $men = "eventos anuales";
                    }
                    echo $c->num_eventos." ".$men;
                    break;
                }?></td>
                <td width="8%" style="color: <?=$color;?>; vertical-align: middle; text-align: center; width: 8%;"><?=$c->bloqueos?></td>
              <tr>
              <?php } ?> 
              <?php foreach ($getCoberturas2 as $c2) {?>
              <tr>
                <td colspan="5"><b><?=$c2->nombre_var?></b> <?=$c2->texto_web?></td>
              </tr>
              <?php } ?>                
            </tbody>
            </table>            
          </div>
          <br>
          </form>
        </div>
      </div>      
    </div>
    <div class="row">
      <div class="col-12">
        <ul class="deco-01">
          <li></li><li></li><li></li><li></li><li></li>
        </ul>
      </div>
    </div>
    <?php if(!empty($getAtenciones)){ ?>
    <div class="row align-items-center">
      <div class="col-12 col-sm-12">
        <table  id="example" class="table table-responsive-md table-striped table-bordered table-hover">
          <thead>
            <th>N° Orden</th>
            <th>Fecha</th>            
            <th>Especialidad</th>
            <th>Centro Médico</th>
            <th width="20%">Estado</th>
          </thead>
          <tbody>
            <?php foreach ($getAtenciones as $a) { 
              $fecha2 = date('d/m/Y',strtotime($a->fecha_atencion));
              $fecha = strtotime($a->fecha_atencion); 
              $hoy= date("Y-m-d");
              $hoy= strtotime($hoy);
            ?>
            <tr>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; }elseif ($fecha==$hoy) { echo "style='color:blue;'"; } ?>><?=$a->estado_atencion?><?=$a->num_orden_atencion?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; }elseif ($fecha==$hoy) { echo "style='color:blue;'"; } ?>><?=$fecha2?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; }elseif ($fecha==$hoy) { echo "style='color:blue;'"; } ?>><?=$a->descripcion_prod?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; }elseif ($fecha==$hoy) { echo "style='color:blue;'"; } ?>><?=$a->nombre_comercial_pr?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; }elseif ($fecha==$hoy) { echo "style='color:blue;'"; } ?>  width="20%">
                <?php switch ($a->fase_atencion) { 
                  case 0: 
                    if($a->estado_atencion=='P'){
                      echo "Cita Reservada";
                    }else{
                       
                      if($fecha==$hoy){
                        if($idproveedor == $a->idproveedor){
                          echo '<a class="boton fancybox btn btn-custom btn-celeste" title="DETALLE DE LA ATENCION" href="'.base_url().'index.php/reg_triaje/'.$aseg_id.'/'.$a->idsiniestro.'" data-fancybox-width="1050" data-fancybox-height="780">Atención Abierta</a>';
                        }else{
                          echo 'Atención Abierta';
                        }
                      }/*else{ 
                        echo "Atención inconclusa";
                      } */
                    }; 
                  break; 
                  default: 
                    echo "Atención Cerrada"; 
                  break;
                  } ?> 
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <script>      
      //para paginacion
        $(document).ready(function() {
          $('#example').DataTable( {
            "pagingType": "full_numbers"
          } );
        } );
      </script> 
      
      </div>
    <?php } ?>
    </div>
        
    </div>
</section>

<?php include(APPPATH."views/templates/footer.html"); ?>

<script>
window.onload = function() {
  cita = document.getElementById('idcita').value;
  tipo = document.getElementById('tipo_orden').value;
  if(cita!='' && tipo=='P'){
    window.open("https://www.red-salud.com/redes/index.php/PopUp/"+cita,"PopUp","width=600, height=300, top=150, left=500, scrollbars=yes, menubar=no, status=no, location=no, resizable=yes");
  }
  
}
</script>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/wow.min.js"></script>
<script src="<?=base_url()?>assets/js/jquery.sticky.js"></script>
<script src="<?=base_url()?>assets/js/Swipe.js"></script>
<script>  
new WOW().init();
$("#menumain").sticky({ topSpacing: 0, zIndex: 99999 });$('#slidemain').bcSwipe({ threshold: 50 });
(function($) {
  function doAnimations(elems) {
    var animEndEv = "webkitAnimationEnd animationend";
    elems.each(function() {
      var $this = $(this),
        $animationType = $this.data("animation");
      $this.addClass($animationType).one(animEndEv, function() {
        $this.removeClass($animationType);
      });
    });
  }
  var $myCarousel = $("#slidemain"),
    $firstAnimatingElems = $myCarousel
      .find(".carousel-item:first")
      .find("[data-animation ^= 'animated']");
  $myCarousel.carousel();
  doAnimations($firstAnimatingElems);
  $myCarousel.on("slide.bs.carousel", function(e) {
    var $animatingElems = $(e.relatedTarget).find(
      "[data-animation ^= 'animated']"
    );
    doAnimations($animatingElems);
  });
})(jQuery);

function loadoption(str, result) {
    $.ajax({
        url: "home/citas",
        type: "POST",
        data: "q=" + str + "&cp=" + result,
        beforeSend: function() { $('#' + result).html(''); },
        success: function(data, textStatus, jqXHR) { $('#' + result).html(data); },
        error: function(jqXHR, textStatus, errorThrown) { alert("Ha ocurrido un error, intentelo mas tarde."); }
    });
}
function changelocal(str) {
    $('#estable').text(str);
    $('#establecimiento').val(str);
}
function getForm(id)
{
   $.ajax({
     type: "POST",
     url: 'planes/form',
     data: "id=" + id,
     success: function(data) {
           // data is ur summary
          $('#ajaxform'+id).html(data);
     }
   });
}
</script>
<script>
  function validar() {
    const chk = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(chk.length > 0){
      document.getElementById('generar').disabled=false;
    }else{
      document.getElementById('generar').disabled=true;
    }
  }  
</script>

</body></html>