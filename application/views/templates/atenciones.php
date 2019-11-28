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



  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
  <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/custom.8.css"/>
  <link rel="stylesheet" href="<?=base_url()?>assets/css/animate.min.css">
   <link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/bootstrap/css/bootstrap.css"/>
  
  <link rel="stylesheet" href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css"/>


  <!-- para paginacion -->
     <script src="<?=base_url()?>pagination/jquery.dataTables.min.css"></script>
    <script src="<?=base_url()?>pagination/jquery-1.12.4.js"></script>
    <script src="<?=base_url()?>pagination/jquery.dataTables.min.js"></script>
    <!--<script src="<?=base_url()?>pagination/dataTables.bootstrap.min.js"></script> -->

     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css"/>

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

   

  <!-- Global site tag (gtag.js) - Google Analytics -->
</head>
<body>

<?php include(APPPATH."views/templates/header.php"); ?>

<section class="seccion-interior">
    <div class="container">
      <br><br><br>
    <div class="row">
      <div class="col-12">
        <div class="seccion-title-int">
          <h1><?=$proveedor?> : Resumen de atenciones </h1>
        </div>
      </div>
    </div>
    
    
    <div>
      <div class="col-xs-12">
        <table  id="example" class="table table-striped table-bordered table-hover" style="width:100%">
          <thead>
            <tr>
            <th>Fecha</th>
            <th>N° Orden</th>
            <th>DNI</th>
            <th>Afiliado</th>
            <th>Plan</th>
            <th>Especialidad</th>
            <th>Estado</th>
          </tr>
          </thead>
          <tbody>
            <?php foreach ($getAtenciones as $a) { 
              //$fecha = date('d/m/Y',strtotime($a->fecha_atencion));
               $fecha = $a->fecha_atencion;?>
            <tr>             
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>><?=$fecha?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>><?=$a->estado_atencion?><?=$a->num_orden_atencion?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>><?=$a->aseg_numDoc?></td>              
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>><?=$a->afiliado?></td>                             
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>><?=$a->nombre_plan?></td>            
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>><?=$a->nombre_esp?></td>
              <td <?php if($a->estado_atencion=='P'){ echo "style='color:red;'"; } ?>>
                <?php 
                    if($a->estado_atencion=='P'){
                      echo "Cita Reservada";
                    }else{
                      $fecha = strtotime($a->fecha_atencion); 
                      $hoy= date("Y-m-d");
                      $hoy= strtotime($hoy); 
                      if($fecha==$hoy){
                        echo '<a class="boton fancybox btn btn-custom btn-celeste" title="DETALLE DE LA ATENCION" href="'.base_url().'index.php/reg_triaje/'.$a->aseg_id.'/'.$a->idsiniestro.'" data-fancybox-width="1050" data-fancybox-height="780">Atención Abierta</a>';
                      }else{
                        echo '<a class="boton fancybox btn btn-custom btn-celeste" title="DETALLE DE LA ATENCION" href="'.base_url().'index.php/reimprimir_atencion_copia/'.$a->aseg_id.'/'.$a->idsiniestro.'" data-fancybox-width="1050" data-fancybox-height="780">Re-imprimir Atención</a>'; 
                      }
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
            "order": [[ 0, "desc" ]],
            "pagingType": "full_numbers"
          } );
        } );
      </script>

      </div>
    
    </div>
        
</section>

<?php include(APPPATH."views/templates/footer.html"); ?>

</body></html>