<?php 
$user = $this->session->userdata('user');
extract($user);
?>
<html lang="es">
<head>
  <meta name="theme-color" content="#c63538">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>/assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?=base_url()?>/assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?=base_url()?>/assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?=base_url()?>/assets/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="<?=base_url()?>/assets/images/safari-pinned-tab.svg" color="#5bbad5">
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

<section class="seccion-bloque">
  <div class="container">
    <?php if(!empty($planes)){ ?>
      <div class="row">
        <div class="col-12">
          <br><br><br>
          <div class="seccion-title">
            <h2><?=$afiliado?></h2>
            <p>DNI: <?=$dni?></p>
            <p>Edad: <?=$edad?> años</p>
            <p>Dirección: <?=$direccion?></p>
            <p>Teléfono: <?=$telefono?></p>
            <hr>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-12 col-md-12">  
        <table id="example" class="table table-responsive-md table-striped table-bordered table-hover">
          <thead>
            <th>N° Certificado</th>
            <th>Plan</th>
            <th>Estado Plan</th>
            <th>Estado Atención</th>
            <th>Fin Vigencia</th>
            <th></th>
          </thead>
          <tbody>
            <?php foreach($planes as $p){
              $carencia = $p->dias_carencia;
              $mora = $p->dias_mora;
              $periodo = $p->dias_atencion;
              $hoy = time();

              $iniVig =$p->cert_iniVig;  
              $finVig =$p->cert_finVig;
              $finVig2=$p->fin_vig;
              $finVig = strtotime($finVig);                     
              $iniVig = strtotime($iniVig);
              $finVig2 = strtotime($finVig2);
              $estado = $p->cert_estado;
              $manual = $p->cert_upProv;
              $ultima_atencion = $p->ultima_atencion;

              // $e=1 puede atenderse, $e=2 = en carencia no puede atenderse, $e=3 = cancelado, $e=4 = Activo Manual
              if($estado==1){
                $estado = 'Vigente';
                $e1=1;
              }elseif ($hoy<=$finVig2){
                $estado= 'Vigente';
                $e1=1;
                }else{
                  $estado = 'Cancelado';
                  $e1=3;
                }

              if($e1==1){
                if($hoy>$iniVig && $hoy<=$finVig){
                  $e2 = 1;
                  $estado2 = 'Activo';
                }else{
                  if($manual==1){
                    $e2 = 1;
                    $estado2 = 'Activo Manual';
                  }elseif($hoy<$iniVig){
                    $e2 = 4;
                    $estado2 = 'En Carencia';
                  }else{
                    $e2 = 3;
                    $estado2 = 'Inactivo';
                  }
                }
              }else{
                $estado2 = 'Inactivo';
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
                      $estado2 = 'En atención';
                      $e2=2;
                  }elseif($diff>0){                      
                      $estado2 = 'Próxima atención en '.round($diff +1).' días';     
                      $e2=5;
                  }               
                }
              }
              ?>
              <tr>
                <td <?php if($e1==3){echo 'style ="color:red"';} ?>><?=$p->cert_num?></td>
                <td <?php if($e1==3){echo 'style ="color:red"';} ?>><?=$p->nombre_plan?></td>
                <td <?php if($e1==3){echo 'style ="color:red"';} ?>><?=$estado?></td>
                <td <?php if($e1==3){echo 'style ="color:red"';} ?>><?=$estado2?></td>
                <td <?php if($e1==3){echo 'style ="color:red"';} ?>><?php echo date('d/m/Y',$finVig); ?></td>
                <td style="text-align: center;"><?php if($e1==1 && $e2<>3){ ?>
                  <a href="<?=base_url()?>index.php/verdetalle/<?=$p->certase_id?>" class="btn btn-custom btn-celeste">Ver Detalle</a>
                <?php } ?></td>
              </tr>
            <?php } ?>            
          </tbody>          
        </table>      
        </div>
      </div>
    <?php } else{ ?>      
      <div class="row">
        <div class="col-12">
          <div class="seccion-title">
            <br><br><br><br>
            <h2>El DNI ingresado, no se encuentra disponible</h2>
            <h3>Se solicita referenciar al afiliado a nuestra central telefónica: (01)445-3019 anexo 100.</h3>
            <p><a href="<?=base_url()?>index.php" class="btn btn-custom btn-celeste">Retornar</a></p>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</section>


<?php include(APPPATH."views/templates/footer.html"); ?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="<?=base_url()?>/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url()?>/assets/js/wow.min.js"></script>
<script src="<?=base_url()?>/assets/js/jquery.sticky.js"></script>
<script src="<?=base_url()?>/assets/js/Swipe.js"></script>
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



</body></html>