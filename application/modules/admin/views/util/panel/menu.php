
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
                    <div class="box-header with-border">
                            <h3 class="box-title">Setting Menu</h3>
                    </div>
			<div class="box-body">
                            <div class="col-md-8">
                                <?php
                                //print_r($username);
                                ?>
				<table class="table table-bordered">
                                    <tr>
                                            <th style="width:300px">Username </th>
                                            <td><?php echo $userkey; ?></td>
                                    </tr>
                                    <tr>
                                            <th>First Name </th>
                                            <td><?php echo $employe; ?></td>
                                    </tr>
                                    <tr>
                                            <th>Jabatan</th>
                                            <td><?php echo ''; ?></td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="col-md-12">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Duplikasi kewenagan dari user lain <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <?php 
                                            foreach ($username as $val) {
                                                ?>
                                                    <li><a href="panel/menu_duplicat/<?php echo $userkey.'/'.strtolower($val->username);?>"><?php echo '['.strtolower($val->id).'] '.strtolower($val->username); ?> </a></li>
                                                <?php
                                            }
                                         
                                            ?>                                          
                                          <li role="separator" class="divider"></li>
                                          <li><a href="#">Default</a></li>
                                        </ul>
                                      </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          Config<span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">  
                                          <li><a href="">Edit</a></li>
                                          <li role="separator" class="divider"></li>
                                          <li><a href="">Default</a></li>
                                        </ul>
                                      </div>
                            </div>
                            <div class="col-md-12">
                                <h3 class="box-title">List Menu</h3>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-hover">
                                    <tr>
                                            <th>No.</th>
                                            <th>Group Menu</th>
                                            <th colspan="2" align="center">Menu</th>
                                    </tr>                                  
                                            <?php print_r($datamenu);
                                            ?>                         
                                </table>
                            </div>
                            <div>
                                <div class="col-md-12 panel-group" id="accordion">
    <div class="panel panel-default" id="panel1">
        <div class="panel-heading">
             <h4 class="panel-title">
        <a data-toggle="collapse" data-target="#collapseOne" 
           href="#collapseOne">
          Collapsible Group Item #1
        </a>
      </h4>

        </div>
        <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
        </div>
    </div>
    <div class="panel panel-default" id="panel2">
        <div class="panel-heading">
             <h4 class="panel-title">
        <a data-toggle="collapse" data-target="#collapseTwo" 
           href="#collapseTwo" class="collapsed">
          Collapsible Group Item #2
        </a>
      </h4>

        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
        </div>
    </div>
    <div class="panel panel-default" id="panel3">
        <div class="panel-heading">
             <h4 class="panel-title">
        <a data-toggle="collapse" data-target="#collapseThree"
           href="#collapseThree" class="collapsed">
          Collapsible Group Item #3
        </a>
      </h4>

        </div>
        <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
        </div>
    </div>
</div>
<!-- Post Info -->
		</div>
	</div>
	
</div>
            
            
<!-- jQuery 2.2.3 -->
<script src="<?php echo base_url(); ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url(); ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!--<script src="<?php echo base_url(); ?>dist/js/demo.js"></script>-->
 <script src="../js/moment/moment.min.js"></script>
    <script src="../js/datepicker/daterangepicker.js"></script>

    <script>
      $(document).ready(function() {
        $('#tgl_fr').daterangepicker({
         
        singleDatePicker: true,
        locale: {
      format: 'YYYY-MM-DD'
    }
        });
      });

    </script>
<!--    	<script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.min.js">
	</script>-->
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/media/js/jquery.dataTables.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions\Buttons/js/dataTables.buttons.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions\Buttons/js/buttons.flash.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js">
	</script>
	<script type="text/javascript" language="javascript" src="//cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions/Buttons/js/buttons.html5.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/extensions/Buttons/js/buttons.print.js">
	</script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/examples/resources/syntax/shCore.js">
	</script>
<!--	<script type="text/javascript" language="javascript" src="<?php echo base_url();?>assets/third/examples/resources/demo.js">
	</script>-->
	<script type="text/javascript" language="javascript" class="init">
        $(document).ready(function() {
                $('#report121').DataTable( {
                        dom: 'Bfrtip',
                        paging: true,  
                        bFilter: true,
                        ordering: true,
                        searching: true,
                        //"scrollY":"1000px",
                        //"scrollCollapse": true,
                        header: true,
                            title: 'My Table Title',
                            orientation: 'landscape',
                            customize: function(doc) {
                               doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            },
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
                $('#report_kre').DataTable( {
                        dom: 'Bfrtip',
                        paging: true,  
                        bFilter: true,
                        ordering: true,
                        searching: true,
                        //"scrollY":"1000px",
                        //"scrollCollapse": true,
                        header: true,
                            title: 'My Table Title',
                            orientation: 'landscape',
                            customize: function(doc) {
                               doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            },
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
                $('#report_dep').DataTable( {
                        dom: 'Bfrtip',
                        paging: true,  
                        bFilter: true,
                        ordering: true,
                        searching: true,
                        //"scrollY":"1000px",
                        //"scrollCollapse": true,
                        header: true,
                            title: 'My Table Title',
                            orientation: 'landscape',
                            customize: function(doc) {
                               doc.defaultStyle.fontSize = 8; //<-- set fontsize to 16 instead of 10 
                            },
                        buttons: [
                                'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                } );
        } );    
        </script>
