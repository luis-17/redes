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
</head>
<body>

<section class="seccion-bloque grad">  
  
  <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="seccion-title"><h2>Registrar Medicamentos</h2></div>         
        </div>
      </div>
      <hr>
      <div class="row">
                <div class="col-12">
                  <?php if(empty($siniestro_diagnostico)){ ?>
                    <div class="seccion-title">
                      <h3>Debe validar Medicamentos Genéricos en las coberturas.</h3>
                    </div>
                    <hr>
                    <?php }else{ foreach($siniestro_diagnostico as $sd){
                      $diagnostico = $sd->dianostico_temp;
                    }?>
                       <div class="seccion-title">
                    <p><b>Primer Diágnóstico:</b></p>
                  </div>
                      <div class="col-12 col-sm-12 col-md-12">
                        <form method="post" action="<?=base_url()?>index.php/guardar_medicamentos2">
                          <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">      
                          <input class="form-control" value="<?=$diagnostico?>" disabled="true" />
                          <br>
                          <input type="hidden" id="idsiniestro" name="idsiniestro" value="<?=$idsiniestro?>">  
                            <div class="row justify-content-md-center">
                              <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group" style="text-align: center;">
                                  <label><b>Medicamento</b></label>
                                </div>
                              </div>
                              <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group" style="text-align: center;">
                                   <label><b>Cantidad</b></label>
                                </div>
                              </div>
                               <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group" style="text-align: center;">
                                  <label><b>Dosis</b></label>
                                </div>
                              </div>
                            </div>
                            <?php  foreach($siniestro_diagnostico as $sd){?>  
                              <div class="row justify-content-md-center">
                                <div class="col-12 col-sm-6 col-md-4">
                                  <div class="form-group">
                                    <input type="text" class="form-control" value="<?=$sd->medicamento?>" disabled>
                                  </div>
                                </div>
                                <div class="col-12 col-sm-6 col-md-4">
                                  <div class="form-group">
                                    <input type="text" name="cantidad<?=$sd->idtratamiento?>" class="form-control" value="" required>
                                  </div>
                                </div>
                                 <div class="col-12 col-sm-6 col-md-4">
                                  <div class="form-group">
                                    <input type="text" name="dosis<?=$sd->idtratamiento?>" class="form-control" value="" required>
                                  </div>
                                </div>
                              </div>                           
                            <?php } ?>                              
                        <br><button type="submit" class="btn btn-custom btn-celeste">Guardar y Continuar</button>
                        </form>
                      </div>
                  <?php } ?>
              </div>
            </div>
     </div>
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

        url: '<?=base_url()?>assets/jqueryTratamiento/search2.php', 
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
  <script language="javascript">
  function valida_med() {
    const chk = document.querySelectorAll('input[type=checkbox]:checked'); 
    if(chk.length > 0){
      document.getElementById('btn_med').disabled=false;
    }else{
      document.getElementById('btn_med').disabled=true;
    }
  }
</script>
</body></html>