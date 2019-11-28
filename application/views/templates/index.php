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

  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="<?=base_url()?>/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>/assets/css/custom.8.css">
  <link rel="stylesheet" href="<?=base_url()?>/assets/css/animate.min.css">


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

<section class="seccion-bloque grad">
  <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="seccion-title">
          <br>
          <br>
          <br>
          <br>
            <h2>Bienvenid@ <?=$nombre_comercial_pr?></h2>
            <p>Realizar búsqueda del afiliado</p>
            <hr>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-12 col-md-4">          
        </div>
        <div class="col-12 col-sm-12 col-md-4">
            <form method="POST" action="<?=base_url()?>index.php/getPlanes">
              <div class="form-group">
                <input type="text" class="form-control" id="dni" name="dni" placeholder="DNI del Afiliado" required="true">
              </div>
              <div class="form-group">
                <input type="submit"  value="Buscar" class="btn btn-dark btn-block btn-lg">
              </div>
            </form>            
        </div>
        <div class="col-12 col-sm-12 col-md-4">
          
        </div>
      </div>
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