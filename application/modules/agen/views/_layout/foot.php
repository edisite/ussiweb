

<!-- BEGIN FOOTER -->
    <div class="footer">
      <div class="container">
        <div class="row">
          <!-- BEGIN COPYRIGHT -->
          <div class="col-md-4 col-sm-4 padding-top-10">
            2017 © bmtelsejahtera. ALL Rights Reserved. 
          </div>
          <!-- END COPYRIGHT -->
          <!-- BEGIN POWERED -->
<!--          <div class="col-md-4 col-sm-4 text-right">
            <p class="powered">Powered by: <a href="http://sisintegrasi.co.id/">Indosis</a></p>
          </div>-->
          <!-- END POWERED -->
        </div>
      </div>
    </div>
    <!-- END FOOTER -->

    <!-- Load javascripts at bottom, this will reduce page load time -->
    <!-- BEGIN CORE PLUGINS(REQUIRED FOR ALL PAGES) -->
    <!--[if lt IE 9]>
    <script src="assets/plugins/respond.min.js"></script>  
    <![endif]-->  
    <script src="<?php echo base_url(); ?>assets/fend/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/fend/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/fend/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="<?php echo base_url(); ?>assets/fend/corporate/scripts/back-to-top.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/fend/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="<?php echo base_url(); ?>assets/fend/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
    <script src="<?php echo base_url(); ?>assets/fend/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script><!-- slider for products -->

    <script src="<?php echo base_url(); ?>assets/fend/corporate/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            Layout.initTwitter();
        });
    </script>
        <script>
      $(document).ready(function() {
        $('#tgl_fr').daterangepicker({
         
        singleDatePicker: true,
        locale: {
      format: 'YYYY-MM-DD'
    }
        });
      });

     $(document).ready(function() {
        $('#tgl_to').daterangepicker({
         
        singleDatePicker: true,
        locale: {
      format: 'YYYY-MM-DD'
    }
        });
      });
    </script>
    
    <script src="../../js/moment/moment.min.js"></script>
    <script src="../../js/datepicker/daterangepicker.js"></script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>