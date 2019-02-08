<?php
$this->load->view('_layout/head');        
?>  
<body class="ecommerce">

        <!-- BEGIN HEADER -->
    <div class="header">
      <div class="container">
          <div class="col-md-3 col-sm-12 col-xs-12 col-sm-offset-5">
        <a class="site-logo" href=""><img src="<?php echo base_url(); ?>assets/images/logo.png" alt="BMT ELSEJAHTERA" width="200px" height="90px"></a>
          </div>
          </div>        
    </div>
        <div class="main">
            <br>
          <div class="col-md-3 col-sm-12 col-xs-12 col-sm-offset-5">
            <div class="panel panel-default">                
              <div class="panel-heading">
                <h3 class="panel-title">
                  Login Agent
                </h3>
              </div>
                <?php if($this->session->userdata('infologin'))
                {
                    ?>
                        <div class="alert isa_warning">
                         <button type="button" class="close" data-dismiss="alert">×</button>
                         <h4>
                           Perhatian!
                         </h4>
                            <?php echo $this->session->userdata('infologin');?>
                         </div>
                <?php
                    
                }
                ?>
              <div class="panel-body">
                  <form accept-charset="UTF-8" role="form" action="<?php echo base_url(); ?>agen/login/verify" method="post">
                  <fieldset>
                    <div class="form-group">
                      <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-fw fa-envelope"></i></span>
                        <input type="text" class="form-control" placeholder="Username" name="username">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="input-group input-group-lg">
                        <span class="input-group-addon"><i class="fa fa-fw fa-lock"></i></span>
                        <input type="password" class="form-control" placeholder="Password" name="password">
                      </div>
                    </div>
                    <div class="checkbox">
                      <label>
                        <input name="remember" type="checkbox" value="Remember Me">
                        Remember Me 
                      </label>
                    </div>
                    <input class="btn btn-lg btn-default btn-block" type="submit" value="Login">
                  </fieldset>
                </form>
                <p class="m-b-0 m-t">Not signed up? <a href="#">Sign up here</a>.</p>
              </div>
            </div>
          </div>
        </div>
        <!-- /row -->
      </div>
<!--    </div>-->
   <?php
$this->load->view('_layout/foot');        
?>  
