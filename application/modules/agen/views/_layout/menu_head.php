<?php
$this->load->view('_layout/head');        
?>  
<body class="ecommerce">
        <!-- BEGIN TOP BAR -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-6 col-sm-6 additional-shop-info">
                  
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">
                    <ul class="list-unstyled list-inline pull-right">
                        <li><a href="#">My Account</a></li>
                        <li><a href="#"><?php echo $this->session->userdata('agn_nameagen');?></a></li>
                        
                        <li><a href="<?php echo base_url(); ?>agen/login/logout">Log out</a></li>
                    </ul>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
        <!-- BEGIN HEADER -->
    <div class="header">
      <div class="container">
          <a class="site-logo" href="<?php echo base_url();?>agen/home"><img src="<?php echo base_url(); ?>assets/images/logo.png" alt="BMT ELSEJAHTERA" width="140px" height="40px"></a>

        <a href="javascript:void(0);" class="mobi-toggler"><i class="fa fa-bars"></i></a>
      
        <!-- BEGIN NAVIGATION -->
        <div class="header-navigation">
          <ul>
              <li class="dropdown dropdown-megamenu">
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="<?php echo base_url();?>agen/home"">
                HOME
                
              </a>
              </li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                Product                
              </a>
                
              <!-- BEGIN DROPDOWN MENU -->
              <ul class="dropdown-menu">
                <li class="dropdown-submenu">
                  <a href="#">PULSA <i class="fa fa-angle-right"></i></a>                  
                </li>
                <li><a href="#">PLN <i class="fa fa-angle-right"></i></a></li>
                <li><a href="#">FINANCE <i class="fa fa-angle-right"></i></a></li>
              </ul>
              <!-- END DROPDOWN MENU -->
            </li>
            <li class="dropdown dropdown-megamenu">
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                Report                
              </a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo base_url();?>agen/report/trx_perday">TRANSAKSI PER HARI</a></li>
                <li><a href="<?php echo base_url();?>agen/report/transaksi">TAGIHAN</a></li>
                </ul>
            </li>
            <li class="dropdown dropdown-megamenu">
              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="javascript:;">
                POIN                
              </a>
            </li>
          </ul>
        </div>
        <!-- END NAVIGATION -->
      </div>
    </div>
 