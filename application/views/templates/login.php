<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="<?=base_url()?>assets/paper_img/favicon.ico">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  
  <title>Paper Kit by Creative Tim</title>

  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    
    <link href="<?=base_url()?>bootstrap3/css/bootstrap.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/css/ct-paper.css" rel="stylesheet"/>
    <link href="<?=base_url()?>assets/css/demo.css" rel="stylesheet" /> 
    <link href="<?=base_url()?>assets/css/examples.css" rel="stylesheet" /> 
        
    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
      
</head>
<body>
    <nav class="navbar navbar-ct-transparent navbar-fixed-top" role="navigation-demo" id="register-navbar">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <p><img src="<?=base_url()?>assets/paper_img/logo.png" alt="Red Salud" height="70"></p>       


        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navigation-example-2">
          <ul class="nav navbar-nav navbar-right">
           
            <li>
                <a href="https://www.red-salud.com/redes/manual_redes.pdf" target="_blank" class="btn btn-simple">Tutorial</a>
            </li>
            <li>
                <a href="#" target="_blank" class="btn btn-simple"><i class="fa fa-twitter"></i></a>
            </li>
            <li>
                <a href="#" target="_blank" class="btn btn-simple"><i class="fa fa-facebook"></i></a>
            </li>
           </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-->
    </nav> 
    
    <div class="wrapper">
        <div class="register-background"> 
            <div class="filter-black"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 ">
                            <div class="register-card">
                                <h3 class="title">Bienvenido a Redes</h3>
                                <form class="register-form" method="post" action="<?=base_url()?>index.php/start_sesion">
                                    <label>Usuario</label>
                                    <input type="text" class="form-control" placeholder="Usuario" name="email">

                                    <label>Contraseña</label>
                                    <input type="password" class="form-control" placeholder="Contraseña" name="password">
                                    <button class="btn btn-danger btn-block">Ingresar</button>
                                </form>
                                <div class="forgot">
                                    <a href="#" class="btn btn-simple btn-danger">¿Olvidó su password?</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>     
            <div class="footer register-footer text-center">
                    <h6>&copy; 2019 Copyright. Health Care Administration RED SALUD</h6>
            </div>
        </div>
    </div>      

</body>

<script src="<?=base_url()?>assets/js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="<?=base_url()?>assets/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>

<script src="<?=base_url()?>bootstrap3/js/bootstrap.js" type="text/javascript"></script>

<!--  Plugins -->
<script src="<?=base_url()?>assets/js/ct-paper-checkbox.js"></script>
<script src="<?=base_url()?>assets/js/ct-paper-radio.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap-select.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap-datepicker.js"></script>

<script src="<?=base_url()?>assets/js/ct-paper.js"></script>
    
</html>