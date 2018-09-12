<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Instalação</title>
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
          <p class="login-box-msg">Instalação</p>
          <form action="{{URL::to('/install')}}" method="post">
              <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
              <div class="body">
                  @if($errors->any())
                     <h4 style='color:red;'>{{$errors->first()}}</h4>
                  @endif

                  @if($currStep == "welcome")
                      <center>You are seeing this message because you did something wrong
                      <br/>
                      or because you are not connected to the database.</center>
                      <br/>
                      <input type="hidden" name="nextStep" value="1">
                      <button type="submit" class="btn btn-primary btn-block btn-flat">Installation process</button>
                  @endif
                  @if($currStep == "1")
                      <div class="form-group">
                          <center><b>Database connection</b></center>
                      </div>

                      <?php
                          if(isset($dbError)){
                              echo '<div class="form-group" style="color:red;">Connection FAILED</div>';
                          }else{
                              echo '<div class="form-group" style="color:green;">Connection OK</div>';
                          }
                      ?>

                      <div class="form-group">
                          <center><b>Folder Permission</b></center>
                      </div>

                      <div class="form-group" style="color:green;">
                          <?php
                              if(isset($success)){
                                  while (list($key, $value) = each($success)) {
                                      echo $value.": <b>Sucesso</b> <br/>";
                                  }
                              }
                          ?>
                      </div>
                      <div class="form-group" style="color:red;">
                          <?php
                              if(isset($perrors)){
                                  while (list($key, $value) = each($perrors)) {
                                      echo $value.": <b>Falhou</b> <br/>";
                                  }
                              }
                          ?>
                      </div>
                      @if($nextStep == "1")
                          <input type="hidden" name="nextStep" value="1">
                          <button type="submit" class="btn bg-olive btn-block">Try again</button>
                      @endif
                      @if($nextStep == "2")
                          <input type="hidden" name="nextStep" value="2">
                          <button type="submit" class="btn bg-olive btn-block">Next</button>
                      @endif
                  @endif

                  @if($currStep == "2")
                      <div class="form-group">
                          <center><b>Administrative access</b></center>
                      </div>

                      <?php
                      if(isset($installErrors)){
                          echo '<div class="form-group" style="color:red;">';
                          while (list($key, $value) = each($installErrors)) {
                              echo $value."<br/>";
                          }
                          echo '</div>';
                      }
                      ?>
                      <div class="form-group">
                          <input type="text" name="fullName" class="form-control" placeholder="Full name *"/>
                      </div>
                      <div class="form-group">
                          <input type="text" name="username" class="form-control" placeholder="User name *"/>
                      </div>
                      <div class="form-group">
                          <input type="text" name="email" class="form-control" placeholder="E-mail *"/>
                      </div>
                      <div class="form-group">
                          <input type="password" name="password" class="form-control" placeholder="password *"/>
                      </div>
                      <div class="form-group">
                          <input type="password" name="repassword" class="form-control" placeholder="Repeat password *"/>
                      </div>

                      <div class="form-group">
                          <center><b>Configuration</b></center>
                      </div>

                      <div class="form-group">
                          <input type="text" name="siteTitle" class="form-control" placeholder="Name of the course / school *"/>
                      </div>
                      <div class="form-group">
                          <input type="text" name="systemEmail" class="form-control" placeholder="Email pattern *"/>
                      </div>

                      <div class="form-group">
                          <center><b>Current year(4 digits)</b></center>
                      </div>

                      <div class="form-group">
                          <input type="text" name="yearTitle" class="form-control" placeholder="Ex.: 2018 *"/>
                      </div>

                      <div class="form-group">
                          <center><b>Activation code</b></center>
                      </div>

                      <div class="form-group">
                          <input type="text" name="cpc" class="form-control" placeholder="Activation code - Koceno *"/>
                      </div>

                      <input type="hidden" name="nextStep" value="3">
                      <button type="submit" class="btn bg-olive btn-block">Next</button>
                  @endif

                  @if($currStep == "3")
                      <div class="form-group">
                          <center><b>Installation completed successfully!</b></center>
                      </div>
                      <a href="<?php echo URL::to('/'); ?>" class="btn bg-olive btn-block">Use your system</a>
                  @endif
              </div>
              <div class="form-group has-feedback">
                  <br/><a href="https://koceno.com.br" target="_BLANK" class="btn bg-olive btn-block">Koceno</a>
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
