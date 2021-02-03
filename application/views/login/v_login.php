<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ITECA-Servicios Academicos</title>
  <!-- Global stylesheets -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/css/bootstrap.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/css/core.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/css/components.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url();?>assets/css/colors.css" rel="stylesheet" type="text/css">
  <!-- /global stylesheets -->

  <!-- Core JS files -->
  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/pace.min.js"></script> -->
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/jquery.min.js"></script>
  <script type="text/javascript" src="<?php echo base_url();?>assets/js/core/libraries/bootstrap.min.js"></script>
  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/loaders/blockui.min.js"></script> -->
  <!-- /core JS files -->

  <!-- Theme JS files -->
  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/validation/validate.min.js"></script> -->
  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/forms/styling/uniform.min.js"></script> -->

  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/core/app.js"></script> -->
  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/pages/login_validation.js"></script> -->

  <!-- <script type="text/javascript" src="<?php echo base_url();?>assets/js/plugins/ui/ripple.min.js"></script> -->
  <!-- /theme JS files -->

</head>

<body class="login-container login-cover">

  <!-- Page container -->
  <div class="page-container">

    <!-- Page content -->
    <div class="page-content">

      <!-- Main content -->
      <div class="content-wrapper">

        <!-- Content area -->
        <div class="content pb-20">


          <form  class="form-validate" id="frm_login">
            <div class="panel panel-body login-form">
              <div class="text-center">
                <div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
                <h5 class="content-group">Ingrese a su cuenta <small class="display-block">usuario y contrase&ntilde;a</small></h5>
              </div>

              

              <div class="form-group has-feedback has-feedback-left">
                <input type="email" class="form-control" placeholder="Email" name="email" id="email" value="<?php echo set_value('email'); ?>">
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i> 
                </div>
                <!-- <label id="email-error" class="validation-error-email" for="email"></label> -->
                <span class="help-block"></span>
              </div>

              <div class="form-group has-feedback has-feedback-left">
                <input type="password" class="form-control" placeholder="Password" name="password" id="password">
                <div class="form-control-feedback">
                  <i class="icon-lock2 text-muted"></i>
                </div>
                <!-- <label id="email-error" class="validation-error-email" for="email"></label> -->
                <span class="help-block"></span>
              </div>











<!--               <div class="form-group login-options">
                <div class="row">
                  <div class="col-sm-6">
                    <label class="checkbox-inline">
                      <input type="checkbox" class="styled" checked="checked">
                      Remember
                    </label>
                  </div>

                  <div class="col-sm-6 text-right">
                    <a href="login_password_recover.html">Forgot password?</a>
                  </div>
                </div>
              </div> -->

              <div class="form-group">
                <button type="submit" class="btn bg-pink-400 btn-block">Ingresar <i class="icon-arrow-right14 position-right"></i></button>
              </div>


              <?php if ($this->session->mark_as_flash('login_flash')): ?>
                <div id="error" class="form-group">
                  <div class="alert alert-danger text-center" role="alert">
                    <?php echo $_SESSION['login_flash']; ?>
                  </div>
                </div>
              <?php endif ?>

              
            </div>
          </form>
          















          <!-- /form with validation -->

        </div>
        <!-- /content area -->

      </div>
      <!-- /main content -->

    </div>
    <!-- /page content -->

  </div>
  <!-- /page container -->
<!-- <script>
  $(function(){
    // $('input').iCheck({
    //   checkboxClass: 'icheckbox_square-blue',
    //   radioClass: 'iradio_square-blue',
    //   increaseArea: '20%' /* optional */
    // });
    $("#frm_logins").submit(function (e){
      e.preventDefault();
      var url = $(this).attr('action');
      var method = $(this).attr('method');
      var data = $(this).serialize();
      $.ajax({
        url:url,
        type:method,
        data:data,
        success: function(data){
          if(data !=='')
          {
              $("#response").show('fast');
              // $("#response").effect( "shake" );
              $('#frm_login')[0].reset();
              // console.log('datos incorrectos');
          }
          else
          {
              // window.location.href='<?php echo base_url() ?>/principal';
              // window.location.href='/principal';
              window.open('<?php echo base_url() ?>Login/login');
              // throw new Error('go');
          }
        }
      });
    });
  });
</script> -->

<script>
  var CFG = {
    url: '<?php echo $this->config->item("base_url");?>',
    name: '<?php echo $this->security->get_csrf_token_name();?>',
    token: '<?php echo $this->security->get_csrf_hash();?>'
  };

  (function ($) {
    $("input").change(function(){
        $(this).parent().removeClass('has-error');
        $(this).next().next().empty();
        $('#error').remove();
    });

    $("#frm_login").submit(function(ev){
      
      var formData = new FormData($('#frm_login')[0]);
      formData.append('csrf_test_name', CFG.token);

      $.ajax({
        url: CFG.url +'login/login',
        type: "POST",
        dataType: 'JSON',
        data: formData,
        contentType: false,
        processData: false,
        success: function(data){
          // console.log(data.status);
          if(data.status)
          {
            window.location.replace('<?php echo base_url();?>'+data.url);
          } else if (data.error) {
            window.location.replace('<?php echo base_url();?>');
          } else {
            for (var i = 0; i < data.inputerror.length; i++) 
            {
              $('[name="'+data.inputerror[i]+'"]').parent().addClass('has-error');
              $('[name="'+data.inputerror[i]+'"]').next().next().text(data.error_string[i]);
            }
          }
        },
        error: function(){

        }
      });
      ev.preventDefault();
    });
  })(jQuery)
</script>
</body>
</html>
