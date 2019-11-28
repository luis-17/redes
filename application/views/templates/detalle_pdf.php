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
<section class="">
  <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="seccion-title">
            <input type="hidden" name="idvariableplan" value="">
                <input type="hidden" name="cert_id" value="">
                <input type="hidden" name="aseg_id" value="">
                <input type="hidden" name="certase_id" value="">
            <a href="" onclick="retornar(<?=$certase_id?>)" class="btn btn-custom btn-celeste">Retornar</a>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-sm-12 col-md-12">  
         <!-- <iframe  src="<?=base_url()?>uploads/<?=$idsiniestro?>.pdf" width="100%" height="80%"></iframe> -->
         <object style=" width: 100%; height: 500px;" data="<?=base_url()?>uploads/<?=$idsiniestro?>.pdf" type="application/pdf">
            <embed src="<?=base_url()?>uploads/<?=$idsiniestro?>.pdf" type="application/pdf" />
        </object>
        </div>
      </div>
  </div>
</section>

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
<script >
  function retornar(certase_id){      
    parent.location.reload(true); 
    parent.$.fancybox.close();        
    redirect("<?=base_url()?>index.php/ver_detalle"+certase_id);
  }
</script>

</body></html>