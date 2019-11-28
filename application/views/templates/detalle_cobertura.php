<?php 
$user = $this->session->userdata('user');
extract($user);
?>
<html lang="es">
<head>
  <meta name="theme-color" content="#c63538">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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

  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/custom.8.css">
  <link rel="stylesheet" href="<?=base_url()?>assets/css/animate.min.css">


<!-- Global site tag (gtag.js) - Google Analytics -->

<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-132525819-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-132525819-1');
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
   <script>
    jQuery.noConflict(); 
    var j = jQuery.noConflict();
   j(document).ready(function(){
    j("#dianostico_temp3").autocomplete("<?=base_url()?>assets/jqueryAutocomplete/autocomplete.php", {
          selectFirst: true
    });
    j("#sin_diagnosticoSec").autocomplete("<?=base_url()?>assets/jqueryAutocomplete/autocomplete.php", {
          selectFirst: true
    });
   });
  </script>
   <script>
    jQuery.noConflict(); 
    var j = jQuery.noConflict();
   j(document).ready(function(){
    j("#dianostico_temp4").autocomplete("<?=base_url()?>assets/jqueryAutocomplete/autocomplete.php", {
          selectFirst: true
    });
    j("#sin_diagnosticoSec").autocomplete("<?=base_url()?>assets/jqueryAutocomplete/autocomplete.php", {
          selectFirst: true
    });
   });
  </script>
</head>
<body>

<section class="seccion-bloque grad"> 
  <?php switch ($estado) {
    case 1:?>
  <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="seccion-title"><h2><?=$cobertura?></h2></div>         
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-12 col-md-6">
          <div class="comunica"><?=$coaseguro?></div>
        </div>
        <div class="col-12 col-sm-12 col-md-6">
          <div class="comunica"><?=$eventos?></div>
        </div>
      </div>      
      <hr>
      <?php switch($idvariableplan){
        case 1:
            if($estado_impresion==1 || $estado_impresion==38){?>
              <form method="post" action="<?=base_url()?>index.php/generar_orden">
                <input type="hidden" name="idvariableplan" value="<?=$idvariableplan?>">
                <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">
                <input type="hidden" name="cert_id" value="<?=$cert_id?>">
                <input type="hidden" name="aseg_id" value="<?=$aseg_id?>">
                <input type="hidden" name="certase_id" value="<?=$certase_id?>">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12">
                    <table>
                      <thead>
                        <th colspan="2"><?=$descripcion?></th>
                      </thead>
                      <tbody>
                        <?php 
                        $cant = count($producto_detalle);
                        foreach ($producto_detalle as $pd) {?>
                          <tr>
                            <td><input type="radio" name="especialidad" value="<?=$pd->idespecialidad?>" <?php if($idespecialidad==$pd->idespecialidad){ echo "checked";}elseif($cant==1){ echo "checked";}?> onclick="javascript:validar(this);"></td>
                            <td><?=$pd->descripcion_prod?></td>                 
                          </tr>
                        <?php } ?>          
                      </tbody>            
                    </table>
                  </div>        
                </div>
                <br>
                <div><b>Teléfono de contacto:</b><input type="text" class="form-control col-md-4" name="telf" required="true" value="<?=$aseg_telf?>"></div>
                <hr>
                <div class="col-12 col-sm-12 col-md-12">
                   <input type="submit" id="generar" name="" <?php if($estado_impresion==1 && $idespecialidad==0){ if($cant<>1){ echo "disabled";}}?> value="Generar Orden de Atención" class="btn btn-custom btn-celeste">    
                </div>
              </form>
            <?php }else{ ?>
              <div class="col-12 col-sm-12 col-md-12">
                <a href="<?=base_url()?>index.php/reimprimir_pdf/<?=$idsiniestro?>/<?=$idvariableplan?>" class="btn btn-custom btn-celeste">Re-Imprimir Orden de Atención</a>     
              </div>
            <?php } ?>
          </div>
        <?php break;
        case 2:
            if($estado_impresion==1){
              if($diagnostico==''){?>              
              <div class="row">
                <div class="col-12">
                  <div class="seccion-title">
                    <p><b><?=$descripcion?></b></p>
                  </div>
                      <div class="col-12 col-sm-12 col-md-12">
                        <form id="form2" name="form2">
                          <input class="form-control" name="dianostico_temp" id="dianostico_temp"/>                          
                          <input type="hidden" name="idvariableplan" value="<?=$idvariableplan?>">
                          <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">
                          <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">                          
                          <input type="hidden" name="dianostico_temp" id="dianostico_temp" />  
                          <input type='hidden' name='tipo' id='tipo' value='1'>               
                        </form>
                        <br><button type="submit" class="btn btn-custom btn-celeste btnSearch">Ver Medicamentos</button>
                      </div>
                      <div id="grupo4"></div>
            <?php }else{?>
               <div class="row">
                <div class="col-12">
                  <div class="seccion-title">
                    <p><b>Medicamentos cubiertos según el primer diagnóstico:</b></p>
                  </div>
                  <form method='post' action='<?=base_url()?>index.php/guardar_medicamentos'>
                    <input class="form-control" name="dianostico_temp" id="dianostico_temp" value="<?=$diagnostico?>" disabled />
                    <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">
                    <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">  
                    <input type='hidden' name='dianostico_temp' id='dianostico_temp' value=''>
                    <input type='hidden' name='tipo' id='tipo' value='2'> 
                    <br>
                    <table class='col-12 col-sm-12 col-md-12'>
                    <?php
                    $cont=0;
                    $cont2=3;
                    foreach ($medicamentos as $m) {
                    if($cont2==3){ echo "<tr>"; }?>
                    <td width="2%"><input onclick="valida_med();" type="checkbox" name="chk[]" value="<?=$m->idmedicamento?>"></td>
                      <td width="48%"><?=$m->nombre_med?></td>
                        <?php $cont= $cont +1; 
                        if($cont==2){
                          $cont2=3; 
                          $cont=0; 
                        }else{ 
                          $cont2 = 0; 
                        } 
                        if($cont2==3){ echo "</tr>"; }
                      } ?>  
                    </table>
                    <br><input type="submit" id="btn_med" name="" value="Validar Medicamentos Cubiertos" class="btn btn-custom btn-celeste" disabled>
                  </form>
                </div>
               </div>
            <?php } 
            }else{ ?>
              <div class="col-12 col-sm-12 col-md-12">
                <a href="<?=base_url()?>index.php/reimprimir_cobertura/<?=$idsiniestro?>/<?=$idplandetalle?>" class="btn btn-custom btn-celeste">Re-Imprimir Validación</a>     
              </div>
            <?php } ?>
              </div>
            </div>
        <?php break;
        case 3:
            if($estado_impresion==1){
              if($diagnostico==''){?>              
              <div class="row">
                <div class="col-12">
                  <div class="seccion-title">
                    <p><b><?=$descripcion?></b></p>
                  </div>
                      <div class="col-12 col-sm-12 col-md-12">
                        <form id="form3" name="form3">
                          <input class="form-control" name="dianostico_temp3" id="dianostico_temp3"/>
                          <input type="hidden" id="idsiniestro3" name="idsiniestro3" value="<?=$idsiniestro?>">
                          <input type="hidden" id="idplandetalle3" name="idplandetalle3" value="<?=$idplandetalle?>">                          
                           <input type="hidden" name="dianostico_temp" id="dianostico_temp" />  
                           <input type='hidden' name='tipo' id='tipo' value='1'>   
                        </form>
                        <br><button type="submit" class="btn btn-custom btn-celeste btnSearch3">Ver Laboratorios</button>
                      </div>
                      <div id="grupo3"></div> <?php }else{?>
               <div class="row">
                <div class="col-12">
                  <div class="seccion-title">
                    <p><b>Laboratorios cubiertos según el primer diagnóstico:</b></p>
                  </div>
                  <form method='post' action='<?=base_url()?>index.php/guardar_cobertura'>
                    <input class="form-control" name="dianostico_temp" id="dianostico_temp" value="<?=$diagnostico?>" disabled />
                    <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">
                    <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">  
                    <input type='hidden' name='dianostico_temp' id='dianostico_temp' value=''>
                           <input type='hidden' name='tipo' id='tipo' value='1'>
                    <input type='hidden' name='tipo' id='tipo' value='2'> 
                    <br>
                    <table class='col-12 col-sm-12 col-md-12'>
                    <?php
                    $cont=0;
                    $cont2=3;
                    foreach ($productos as $p) {
                    if($cont2==3){ echo "<tr>"; }?>
                    <td width="2%"><input onclick="valida_lab();" type="checkbox" name="checkboxes[]" value="<?=$p->idproducto?>"></td>
                      <td width="48%"><?=$p->descripcion_prod?></td>
                        <?php $cont= $cont +1; 
                        if($cont==2){
                          $cont2=3; 
                          $cont=0; 
                        }else{ 
                          $cont2 = 0; 
                        } 
                        if($cont2==3){ echo "</tr>"; }
                      } ?>  
                    </table>
                    <br><input type="submit" id="btn_med" name="" value="Validar Laboratorios Cubiertos" class="btn btn-custom btn-celeste" disabled>
                  </form>
                </div>
               </div>
            <?php } }else{ ?>
              <div class="col-12 col-sm-12 col-md-12">
                <a href="<?=base_url()?>index.php/reimprimir_cobertura/<?=$idsiniestro?>/<?=$idplandetalle?>" class="btn btn-custom btn-celeste">Re-Imprimir Validación</a>     
              </div>
            <?php } ?>
              </div>
            </div>
        <?php break;
        case 4:
            if($estado_impresion==1){
              if($diagnostico==''){?>               
              <div class="row">
                <div class="col-12">
                  <div class="seccion-title">
                    <p><b><?=$descripcion?></b></p>
                  </div>
                      <div class="col-12 col-sm-12 col-md-12">
                        <form id="form4" name="form4">
                          <input class="form-control" name="dianostico_temp4" id="dianostico_temp4"/>
                          <input type="hidden" id="idsiniestro4" name="idsiniestro4" value="<?=$idsiniestro?>"> 
                           <input type="hidden" name="dianostico_temp" id="dianostico_temp" />
                          <input type="hidden" id="idplandetalle4" name="idplandetalle4" value="<?=$idplandetalle?>">              
                        </form>
                        <br><button type="submit" class="btn btn-custom btn-celeste btnSearch4">Ver Imágenes</button>
                      </div>
                      <div id="grupo5"></div><?php }else{?>
               <div class="row">
                <div class="col-12">
                  <div class="seccion-title">
                    <p><b>Imágenes cubiertas según el primer diagnóstico:</b></p>
                  </div>
                  <form method='post' action='<?=base_url()?>index.php/guardar_cobertura'>
                    <input class="form-control" name="dianostico_temp" id="dianostico_temp" value="<?=$diagnostico?>" disabled />
                    <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">
                    <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">  
                    <input type='hidden' name='dianostico_temp' id='dianostico_temp' value=''>
                    <input type='hidden' name='tipo' id='tipo' value='2'> 
                    <br>
                    <table class='col-12 col-sm-12 col-md-12'>
                    <?php
                    $cont=0;
                    $cont2=3;
                    foreach ($productos as $p) {
                    if($cont2==3){ echo "<tr>"; }?>
                    <td width="2%"><input onclick="valida_lab();" type="checkbox" name="checkboxes[]" value="<?=$p->idproducto?>"></td>
                      <td width="48%"><?=$p->descripcion_prod?></td>
                        <?php $cont= $cont +1; 
                        if($cont==2){
                          $cont2=3; 
                          $cont=0; 
                        }else{ 
                          $cont2 = 0; 
                        } 
                        if($cont2==3){ echo "</tr>"; }
                      } ?>  
                    </table>
                    <br><input type="submit" id="btn_med" name="" value="Validar Imágenes Cubiertas" class="btn btn-custom btn-celeste" disabled>
                  </form>
                </div>
               </div>
            <?php }}else{ ?>
              <div class="col-12 col-sm-12 col-md-12">
                <a href="<?=base_url()?>index.php/reimprimir_cobertura/<?=$idsiniestro?>/<?=$idplandetalle?>" class="btn btn-custom btn-celeste">Re-Imprimir Validación</a>     
              </div>
            <?php } ?>
              </div>
            </div>
        <?php break;
        case 38:
            if($estado_impresion==1 || $estado_impresion==38){?>
              <form method="post" action="<?=base_url()?>index.php/generar_orden">
                <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">  
                <input type="hidden" name="idvariableplan" value="<?=$idvariableplan?>">
                <input type="hidden" name="cert_id" value="<?=$cert_id?>">
                <input type="hidden" name="aseg_id" value="<?=$aseg_id?>">
                <input type="hidden" name="certase_id" value="<?=$certase_id?>">
                <div class="row">
                  <div class="col-12 col-sm-12 col-md-12">
                    <table>
                      <thead>
                        <th colspan="2"><?=$descripcion?></th>
                      </thead>
                      <tbody>                       
                          <tr>
                            <td><input type="radio" name="especialidad" value="27" checked ></td>
                            <td>Todos los servicios derivados de la emergencia.</td>                 
                          </tr>
                      </tbody>            
                    </table>
                  </div>        
                </div>
                <br>
                <div><b>Teléfono de contacto:</b> <input type="text" class="form-control col-md-4" name="telf" required="true" value="<?=$aseg_telf?>"></div>
                <hr>
                <div class="col-12 col-sm-12 col-md-12">
                   <input type="submit" id="generar" name="" value="Generar Orden de Emergencia/ Urgencia" class="btn btn-custom btn-celeste">      
                </div>
              </form>
            <?php }else{ ?>
              <div class="col-12 col-sm-12 col-md-12">
                <a href="<?=base_url()?>index.php/reimprimir_pdf/<?=$idsiniestro?>/<?=$idvariableplan?>" class="btn btn-custom btn-celeste">Re-Imprimir Orden de Emergencias/Urgencias</a>     
              </div>
            <?php } ?>
          </div>
        <?php break;
        default:
            if($estado_impresion==1){?>
              <div class="row">
                <div class="col-12">
                  <form id="cobertura" name="cobertura" method="post" action="<?=base_url()?>index.php/guardar_cobertura">
                      <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">
                      <input type="hidden" name="idvariableplan" value="<?=$idvariableplan?>">
                      <input type="hidden" id="idplandetalle" name="idplandetalle" value="<?=$idplandetalle?>">   
                      <input type='hidden' name='dianostico_temp' id='dianostico_temp' value='1'>   
                    <table>
                      <thead>
                        <th colspan="2"><?=$descripcion?></th>
                      </thead>
                      <tbody>
                        <?php 
                        $cont=0;
                        $cont2=3;
                        $tipo=1;
                        if(!empty($productos2)){
                        $tipo=1;
                        foreach ($productos2 as $pr) {?>
                          <?php if($cont2==3){ echo "<tr>"; } ?>
                            <td width="2%"><input type="checkbox" name="checkboxes[]" value="<?=$pr->idproducto?>" onclick="contar()"></td>
                            <td width="48%"><?=$pr->descripcion_prod?></td>                 
                          <?php  $cont= $cont +1; if($cont==2){ $cont2=3; $cont=0; }else{ $cont2 = 0; } if($cont2==3){ echo "</tr>"; } ?>
                        <?php }} ?>              
                      </tbody> 
                    </table>
                    <input type="hidden" name="tipo" value="<?=$tipo?>">
                    <br><button type="submit" id="btn_producto" class="btn btn-custom btn-celeste" disabled="true">Validar <?=$cobertura?></button>   
                  </form>      
              <?php }else{ ?>
              <div class="col-12 col-sm-12 col-md-12">
                <a href="<?=base_url()?>index.php/reimprimir_cobertura/<?=$idsiniestro?>/<?=$idplandetalle?>" class="btn btn-custom btn-celeste">Re-Imprimir Validación</a>     
              </div>
            <?php } ?>
            </div>
          </div>
          <?php break;
      } ?>
  </div>      
  <?php break;
  default: ?>
  <div class="container">
      <div class="row">
        <div class="col-12">
          <br>
          <br>
          <br>
          <br>
          <div class="col-12 col-sm-12 col-md-12">
              <div class="seccion-title"><h2><?php echo str_replace("%C3%B3","ó",str_replace("%C3%AD","í",str_replace("%20"," ",$estado2)));?>.</h2></div>
              <div style="text-align: center;"> <a onclick="cerrar(<?=$certase_id?>)" class="btn btn-custom btn-celeste">Retornar</a></div>
          </div>          
        </div>
      </div>
  </div>
  <?php break; }?>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?=base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>assets/js/wow.min.js"></script>
<script src="<?=base_url()?>assets/js/jquery.sticky.js"></script>
<script src="<?=base_url()?>assets/js/Swipe.js"></script>
<script type="text/javascript">

     submitForms = function() {

      var dianostico_temp = document.getElementById("dianostico_temp").value;
      var idsiniestro = document.getElementById("idsiniestro").value;
      var idplandetalle = document.getElementById("idplandetalle").value;
    
      //document.getElementById("demo").innerHTML = 5 + 6;
      //alert(dianostico_temp);
      document.getElementById("form2").submit();
      //parent.location.reload(true);
      //parent.$.fancybox.close();
      
    }

    jQuery(document).ready(function($) {
      $('.btnSearch').click(function(){
        makeAjaxRequest();
      });

      $("#form2").submit(function(e){
                        e.preventDefault();
                        makeAjaxRequest();
                        return false;
                    
                    });

      function makeAjaxRequest() {
      
      var idsiniestro=$("#idsiniestro").val();
      var dianostico_temp=$("#dianostico_temp").val();
      var idplandetalle=$("#idplandetalle").val();
      
                      
      $.ajax({                          

        url: '<?=base_url()?>assets/jqueryTratamiento/search.php', 
        type: 'GET',
        data:   "idsiniestro="+idsiniestro+
                "&dianostico_temp="+dianostico_temp+
                "&idplandetalle="+idplandetalle,

                //data: { dianostico_temp : dianostico_temp },
                
                //data: { name: $('input#idsiniestro').val()},
                success: function(response) {
                $('#grupo4').html(response);
                }
      });
      }
    });
  </script>

  <script type="text/javascript">

     submitForms = function() {

      var dianostico_temp3 = document.getElementById("dianostico_temp3").value;
      var idsiniestro3 = document.getElementById("idsiniestro3").value;
      var idplandetalle3 = document.getElementById("idplandetalle3").value;
    
      //document.getElementById("demo").innerHTML = 5 + 6;
      //alert(dianostico_temp);
      document.getElementById("form3").submit();
      //parent.location.reload(true);
      //parent.$.fancybox.close();
      
    }

    jQuery(document).ready(function($) {
      $('.btnSearch3').click(function(){
        makeAjaxRequest();
      });

      $("#form3").submit(function(e){
                        e.preventDefault();
                        makeAjaxRequest();
                        return false;
                    
                    });

      function makeAjaxRequest() {
      
      var idsiniestro=$("#idsiniestro3").val();
      var dianostico_temp=$("#dianostico_temp3").val();
      var idplandetalle=$("#idplandetalle3").val();
      
                      
      $.ajax({                          

        url: '<?=base_url()?>assets/jqueryTratamiento/search3.php', 
        type: 'GET',
        data:   "idsiniestro="+idsiniestro+
                "&dianostico_temp="+dianostico_temp+
                "&idplandetalle="+idplandetalle,

                //data: { dianostico_temp : dianostico_temp },
                
                //data: { name: $('input#idsiniestro').val()},
                success: function(response) {
                $('#grupo3').html(response);
                }
      });
      }
    });
  </script>

  <script type="text/javascript">

     submitForms = function() {

      var dianostico_temp4 = document.getElementById("dianostico_temp4").value;
      var idsiniestro4 = document.getElementById("idsiniestro4").value;
      var idplandetalle4 = document.getElementById("idplandetalle4").value;
    
      //document.getElementById("demo").innerHTML = 5 + 6;
      //alert(dianostico_temp);
      document.getElementById("form4").submit();
      //parent.location.reload(true);
      //parent.$.fancybox.close();
      
    }

    jQuery(document).ready(function($) {
      $('.btnSearch4').click(function(){
        makeAjaxRequest();
      });

      $("#form4").submit(function(e){
                        e.preventDefault();
                        makeAjaxRequest();
                        return false;
                    
                    });

      function makeAjaxRequest() {
      
      var idsiniestro=$("#idsiniestro4").val();
      var dianostico_temp=$("#dianostico_temp4").val();
      var idplandetalle=$("#idplandetalle4").val();
      
                      
      $.ajax({                          

        url: '<?=base_url()?>assets/jqueryTratamiento/search4.php', 
        type: 'GET',
        data:   "idsiniestro="+idsiniestro+
                "&dianostico_temp="+dianostico_temp+
                "&idplandetalle="+idplandetalle,

                //data: { dianostico_temp : dianostico_temp },
                
                //data: { name: $('input#idsiniestro').val()},
                success: function(response) {
                $('#grupo5').html(response);
                }
      });
      }
    });
  </script>

  <script>
  function validar(obj) {
    if(obj.checked==true){
      document.getElementById('generar').disabled=false;
    }else{
      document.getElementById('generar').disabled=true;
    }
  }  
</script>
<script>
  function valida_producto(){
    var cont = 0;  
    for(var i = 0 ; i < document.getElementsByName("producto").length; i++){
      cont = cont + 1;
    }
    if(cont>0){
      document.getElementById('producto').disabled=false;
    }
  }
</script>
<script language="javascript">
  function contar() {
    const chk = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(chk.length > 0){
      document.getElementById('btn_producto').disabled=false;
    }else{
      document.getElementById('btn_producto').disabled=true;
    }
  }
</script>

<script language="javascript">
  function valida_med() {
    const chk = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(chk.length > 0){
      document.getElementById('btn_med').disabled=false;
    }else{
      document.getElementById('btn_med').disabled=true;
    }
  }

   function valida_lab() {
    const chk = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(chk.length > 0){
      document.getElementById('btn_med').disabled=false;
    }else{
      document.getElementById('btn_med').disabled=true;
    }
  }

   function valida_rayos() {
    const chk = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(chk.length > 0){
      document.getElementById('btn_med').disabled=false;
    }else{
      document.getElementById('btn_med').disabled=true;
    }
  }
</script>

<script>
  function cerrar(certase_id){
    parent.location.reload(true);    
    parent.$.fancybox.close(); 
    redirect("<?=base_url()?>index.php/ver_detalle"+certase_id);
  }
</script>
</body></html>