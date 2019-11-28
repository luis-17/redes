<?php 
$user = $this->session->userdata('user');
extract($user);
?>
<header>
    <div class="menutop" style="display:none">
      <div class="container">
        <div class="row">
          <div class="col-12 col-sm-12 col-md-6">
            <div class="center-xs">
              <a href="https://www.red-salud.com/personas" class="btn btn-custom btn-outline-light"><i class="material-icons">people</i> PERSONAS</a>
              <a href="https://www.red-salud.com/empresas" class="btn btn-custom btn-outline-light"><i class="material-icons">business_center</i> EMPRESAS</a>
              <a href="https://www.red-salud.com/planes-de-salud" class="btn btn-custom btn-outline-light"><i class="material-icons">assignment</i> PLANES DE SALUD</a>
            </div>
          </div>
          <div class="col-12 col-sm-12 col-md-6 text-right">
            <div class="center-xs">
              <span class="mr-4"><i class="material-icons">phone</i> +511 445-3019</span>
              <span class="mr-4"><a href="https://www.facebook.com/RedSaludPeru/?ref=hl" target="_blank"><img src="https://www.red-salud.com/assets/images/icon_fb_0.png" alt="Facebook: Red Salud"></a></span>
              <a href="https://www.red-salud.com/red-salud" class="btn btn-custom btn-outline-light">MI RED SALUD</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav id="menumain" class="navbar navbar-expand-lg navbar-light menuinterno">
      <div class="container">
        <a class="navbar-brand" href="https://www.red-salud.com/"><img src="https://www.red-salud.com/assets/images/logo.png" height="70" alt="Red Salud"></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link " href="<?=base_url()?>"><i class="material-icons">home</i></a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link active" href="<?=base_url()?>index.php/facturacion">Facturación</a>
            </li> -->
            <li class="nav-item">
              <a class="nav-link active" href="<?=base_url()?>index.php/atenciones">Atenciones</a>
            </li>
            <li class="nav-item">
              <a class="nav-link " href="<?=base_url()?>index.php">Tutoriales</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-custom btn-celeste" href="<?=base_url()?>index.php/logout">Cerrar Sesión</a>
            </li>
            <li class="nav-item mobil-table text-center">
              <hr>
              <a href="https://www.red-salud.com/personas" class="btn btn-outline-info"><i class="material-icons">people</i></a>
              <a href="https://www.red-salud.com/empresas" class="btn btn-outline-info"><i class="material-icons">business_center</i></a>
              <a href="https://www.red-salud.com/planes-de-salud" class="btn btn-outline-info"><i class="material-icons">assignment</i></a>
              <a href="https://www.red-salud.com/red-salud" class="btn btn-outline-info">MI RED SALUD</a>
              <div class="mt-3 mb-3">
                <a href="tel:+511 445-3019" class="btn btn-success" style="font-size: 17.5px;"><i class="material-icons">phone</i></a>
                <a href="https://www.facebook.com/RedSaludPeru/?ref=hl" target="_blank" class="btn btn-info"><img src="https://www.red-salud.com/assets/images/icon_fb_0.png" alt="Facebook: Red Salud"></a>
              </div>              
            </li>
          </ul>
        </div>
      </div>
    </nav>
</header>