<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $panelInit->settingsArray['siteTitle'] . " | " . $panelInit->language['registerNewAccount']; ?></title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
       	<link href="<?php echo URL::asset('/'); ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/datepicker3.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/jquery.gritter.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/fullcalendar.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/intlTelInput.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URL::asset('/'); ?>assets/css/schoex.css" rel="stylesheet" type="text/css" />
        <?php if($panelInit->isRTL == "rtl"){ ?>
            <link href="<?php echo URL::asset('/'); ?>/assets/css/rtl.css" rel="stylesheet" type="text/css" />
        <?php } ?>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="hold-transition login-page rtlPage" ng-app="schoex" ng-controller="registeration">
      <div class="login-box">
        <div class="login-logo"><style>img {max-width: 215px;max-height: 215px;}</style>
            <?php
            if($panelInit->settingsArray['siteLogo'] == "siteName"){
                echo $panelInit->settingsArray['siteTitle'];
            }elseif($panelInit->settingsArray['siteLogo'] == "text"){
                echo $panelInit->settingsArray['siteLogoAdditional'];
            }elseif($panelInit->settingsArray['siteLogo'] == "image"){
                echo "<img src='".URL::asset('assets/img/logo.png')."'/>";
            }
            ?>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
          <h3 class="login-box-msg"><?php echo $panelInit->language['registerAcc']; ?></h3>
          <form ng-submit="tryRegister()" method="post" role="form" name="registerationForm" novalidate>
              <section class="content" ng-show="views.register">
                  <?php
                  if($errors->any()){
                     ?>
                     <span style='color:red;'>{{$errors->first()}}</span><br/>
                     <?php
                  }
                  ?>
                  <div class="form-group">
                      <div class="row">
                          <div class="col-md-4"><label><input type="radio" style="margin-right:0px !important" name="role" value="teacher" ng-model="form.role" /> <?php echo $panelInit->language['teacher']; ?> </label></div>
                          <div class="col-md-4"><label><input type="radio" style="margin-right:0px !important" name="role" value="student" ng-model="form.role"/> <?php echo $panelInit->language['student']; ?> </label></div>
                          <div class="col-md-4"><label><input type="radio" style="margin-right:0px !important" name="role" value="parent" ng-model="form.role"/> <?php echo $panelInit->language['parent']; ?> </label></div>
                      </div>
                  </div>
                  <div class="form-group" ng-class="{'has-error': registerationForm.username.$invalid}">
                      <input type="text" name="username" class="form-control" ng-model="form.username" required placeholder="<?php echo $panelInit->language['username']; ?>"/>
                  </div>
                  <div class="form-group" ng-class="{'has-error': registerationForm.email.$invalid}">
                      <input type="text" name="email" class="form-control" placeholder="<?php echo $panelInit->language['email']; ?>" ng-model="form.email" ng-required="form.role != 'student' || ( form.role == 'student' && <?php if($panelInit->settingsArray['emailIsMandatory'] == true){ echo 'true'; }else{ echo 'false'; } ; ?>)" ng-pattern="/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/"/>
                  </div>
                  <div class="form-group" ng-class="{'has-error': registerationForm.password.$invalid}">
                      <input type="password" name="password" class="form-control" required placeholder="<?php echo $panelInit->language['password']; ?>" ng-model="form.password" required/>
                  </div>
                  <div class="form-group" ng-class="{'has-error': registerationForm.fullName.$invalid}">
                      <input type="text" name="fullName" class="form-control" required placeholder="<?php echo $panelInit->language['FullName']; ?>" ng-model="form.fullName"/>
                  </div>

                  <div class="form-group" ng-show="form.role == 'parent'">
                      <input type="text" name="parentProfession" class="form-control" placeholder="<?php echo $panelInit->language['Profession']; ?>" ng-model="form.parentProfession"/>
                  </div>

                  <div class="form-group" ng-show="form.role == 'student'">
                      <input type="text" name="studentRollId" class="form-control" placeholder="<?php echo $panelInit->language['rollid']; ?>" ng-model="form.studentRollId"/>
                  </div>
                  <div class="form-group" ng-show="form.role == 'student'">
                      <select class="form-control" name="studentClass" ng-model="form.studentClass" ng-change="subjectList()">
                        <option ng-repeat="class in classes" value="{{class.id}}">{{class.className}}</option>
                      </select>
                  </div>
                  <?php if($panelInit->settingsArray['enableSections'] == true){ ?>
                      <div class="form-group" ng-show="form.role == 'student'">
                          <select class="form-control" name="studentSection" ng-model="form.studentSection" >
                              <option ng-repeat="section in sections" value="{{section.id}}"> {{section.sectionName}} - {{section.sectionTitle}}</option>
                          </select>
                      </div>
                  <?php } ?>

                  <div class="form-group">
                      <input type="text" name="birthday" class="form-control datemask" placeholder="<?php echo $panelInit->language['Birthday']; ?> (25/12/1979)" ng-model="form.birthday"/>
                  </div>
                  <div class="form-group">
                      <div class="row">
                          <div class="col-md-4"><input type="radio" name="gender" value="masculino" ng-model="form.gender"/> <?php echo $panelInit->language['Male']; ?> </div>
                          <div class="col-md-4"><input type="radio" name="gender" value="feminino" ng-model="form.gender"/> <?php echo $panelInit->language['Female']; ?> </div>
                      </div>
                  </div>
                  <div class="form-group">
                      <input type="text" name="address" class="form-control" placeholder="<?php echo $panelInit->language['Address']; ?>" ng-model="form.address"/>
                  </div>
                  <div class="form-group">
                      <input type="number" name="phoneNo" class="form-control" placeholder="Telefone fixo" ng-model="form.phoneNo"/>
					  <!--<input type="text" name="phoneNo" class="form-control" placeholder="<?php echo $panelInit->language['phoneNo']; ?>" ng-model="form.phoneNo"/>-->
                  </div>
                  <div class="form-group" ng-class="{'has-error': registerationForm.mobileNo.$invalid}">
                      <input type="text" name="mobileNo" class="form-control" placeholder="Celular com DDD" ng-model="form.mobileNo"/>
					  <!--<input type="text" mobile-number name="mobileNo" class="form-control" placeholder="<?php echo $panelInit->language['mobileNo']; ?>" ng-model="form.mobileNo"/>-->
                  </div>
                  <div class="form-group" ng-show="form.role == 'parent'">

                      <div class="row">
                          <label for="inputPassword3" class="col-sm-6"><?php echo $panelInit->language['studentDetails']; ?></label>
                          <div class="col-sm-6">
                              <a type="button" ng-click="linkStudent()" class="btn btn-danger btn-flat">Vincular estudante</a>
                          </div>
                      </div>
                      <div class="row" ng-repeat="studentOne in form.studentInfo track by $index" style="padding-top:5px;">
                          <div class="col-xs-4"><input type="text" class="form-control" disabled="disabled" ng-model="studentOne.student"></div>
                          <div class="col-xs-4"><input type="text" class="form-control" ng-model="studentOne.relation" placeholder="{{phrase.Relation}}"></div>
                          <a type="button" ng-click="removeStudent(studentOne.id)" class="btn btn-danger btn-flat"><i class="fa fa-trash-o"></i></a></li>
                      </div>

                  </div>

                  <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                  <button type="submit" ng-disabled="registerationForm.$invalid" class="btn bg-olive btn-block"><?php echo $panelInit->language['registerNewAccount']; ?></button><br/>
                  <a href="<?php echo URL::to('/forgetpwd'); ?>"><?php echo $panelInit->language['restorePwd']; ?></a>
              </section>
              <section class="content" ng-show="views.thanks">
                  <center><?php echo $panelInit->language['thankReg']; ?><br/></center>
                  <center>Por favor AGUARDE!</center>
              </section>
          </form>
        </div><!-- /.login-box-body -->
      </div><!-- /.login-box -->

        <modal visible="showModalLink" style="color:#000;">
          <div class="row">
              <div class="col-sm-9">
                <input type="text" class="form-control" id="searchLink" placeholder="nome do estudante / nome de usuario / email">
              </div>
              <div class="col-sm-2">
                <a type="button" ng-click="linkStudentButton()" class="btn btn-danger btn-flat">Pesquisar</a>
              </div>
          </div>
          <div class="row">
            <div class="col-xs-12" style="padding-top:10px;">
              <div class="box-body table-responsive">
                <table class="table table-bordered">
                  <tbody>
                  <tr ng-repeat="studentOne in searchResults">
                      <td>{{studentOne.name}}</td>
                      <td>{{studentOne.email}}</td>
                      <td class="no-print">
                       <a type="button" ng-click="linkStudentFinish(studentOne)" class="btn btn-success btn-flat">Vincular</a>
                      </td>
                  </tr>
                </tbody></table>
              </div>
            </div>
          </div>
        </modal>
        <input type="hidden" id="dateformatVal" value="<?php echo $panelInit->settingsArray['dateformat']; ?>">
        <input type="hidden" id="utilsScript" value="<?php echo URL::asset('/'); ?>assets/js/utils.js"/>
        <script src="<?php echo URL::asset('/'); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/moment.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/fullcalendar.min.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/jquery.gritter.min.js" type="text/javascript"></script>

        <script src="<?php echo URL::asset('/'); ?>assets/js/Angular/angular.min.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/Angular/AngularModules.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/Angular/app.js"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/Angular/routes.js" type="text/javascript"></script>

        <script src="<?php echo URL::asset('/'); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/bootstrap-datepicker.js" type="text/javascript"></script>
        <script src="<?php echo URL::asset('/'); ?>assets/js/intlTelInput.min.js"></script>

        <script src="<?php echo URL::asset('/'); ?>assets/js/schoex.js" type="text/javascript"></script>
    </body>
</html>
