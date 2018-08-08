<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Ativador</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
       	<link href="<?php echo URL::asset('/'); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/datepicker3.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/fullcalendar.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/schoex.css" rel="stylesheet" type="text/css" />
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page rtlPage" ng-app="schoex" ng-controller="registeration">
      <div class="login-box">
        <div class="login-box-body">
          <p class="login-box-msg">Ativador</p>
          <form action="{{URL::to('/licenseInstaller')}}" method="post">
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
              <div class="body">
                  @if($errors->any())
                     <h4 style='color:red;'>{{$errors->first()}}</h4>
                  @endif

                  <center>Por favor, preencha os dados corretamente.
                  <br/>
                  <?php
                    if(isset($loginError)){
                        ?><h4 style='color:red;'>Código inválido!</h4><?php
                    }
                  ?>
                </center><br/>
                <?php if(isset($success)){ ?>

                     <h4 style='color:green;'>Código atualizado!</h4>

                <?php }else{ ?>
                  <div class="form-group">
                      <input type="text" name="email" class="form-control" placeholder="Usuário ou email"/>
                  </div>
                  <div class="form-group">
                      <input type="password" name="password" class="form-control" placeholder="Senha"/>
                  </div>
                  <div class="form-group">
                      <input type="text" name="cpc" class="form-control" placeholder="Código"/>
                  </div>
                  <input type="hidden" name="nextStep" value="1">
                  <button type="submit" class="btn bg-olive btn-block">Instalar agora</button>
                <?php } ?>
              </div>
              <div class="footer">
                  <br/>
                  <a href="https://koceno.com.br" target="_BLANK" class="btn bg-olive btn-block">Koceno</a>
              </div>
          </form>
        </div><!-- /.login-box-body -->
      </div><!-- /.login-box -->

        <script src="<?php echo URL::asset('/'); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/jquery.gritter.min.js" type="text/javascript"></script>

        <script src="<?php echo URL::asset('/'); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

        <script src="<?php echo URL::asset('/'); ?>assets/js/schoex.js" type="text/javascript"></script>
    </body>
</html>
