<?php echo validation_errors(); ?>

<div class="row">    
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-success box-solid">
        <div class="box-header with-border">
             <h3 class="box-title">DAFTAR UPLINE</h3>
                    <div class="box-tools pull-right">
                        <a href="#myModal" class="btn btn-success" role="button"  data-toggle="modal"><i class="glyphicon glyphicon-plus"></i>Tambah Baru</a>                        
                    </div>
            </div>
           
    <table cellpadding="0" cellspacing="0" border="0" class="display groceryCrudTable">
	<thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Jumlah Downline</th>
                <th class='actions'>Actions</th>
            </tr>
	</thead>
        <tbody>            
            <?php
            $no = 0;    
                if(!empty($crud_upline)){
                    foreach ($crud_upline as $v) {                      
                        
                        ?>
                            <tr id='row-<?php echo $no;?>'>
                                <td><?php echo $v->parent_id;?></td>
                                <td><?php echo strtolower($v->username);?></td>
                                <td><?php echo ucwords($v->full_name);?></td>
                                <td><span class="label label-default pull-left"><?php echo $v->total;?></span></td>
                                <td class='actions'>
                                    <a href="<?php echo base_url();?>admin/master_marketing/singlelevel/upline_n/<?php echo $v->parent_id;?>" class="btn btn-primary btn-sm" role="button"><i class="glyphicon glyphicon-search"></i></a>
                                    <a href="<?php echo base_url();?>admin/master_marketing/singlelevel/upline_remove/<?php echo $v->parent_id;?>" class="btn btn-danger btn-sm" role="button" onclick="return confirm('Are you sure want to delete this?');"></i><i class="glyphicon glyphicon-remove"></i></a>
                                </td>
                            </tr>
                        <?php                        
                        $no++;
                    }
                }else{
                    ?>
                    <tr id='row-<?php echo $no;?>'>
                        <td colspan="4">no record</td>                
                    </tr>
                            <?php
                }
            ?>
        </tbody>
    </table>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="box box-info box-solid">
        <div class="box-header with-border">
             <h3 class="box-title">DAFTAR DOWNLINE</h3>
                    <div class="box-tools pull-right">
                        <a href="#myModal1" class="btn btn-info" role="button"  data-toggle="modal"><i class="glyphicon glyphicon-plus"></i>Tambah Downline</a>                        
                    </div>
            </div>           
        <?php if ( !empty($crud_note) ) echo "<p>$crud_note</p>"; ?>
<?php if ( !empty($crud_output) ) echo $crud_output; ?>
        </div>
    </div>
    <div class="modal fade" role="dialog" tabindex="-1" id="myModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Daftar User</h4>
                        <ol class="breadcrumb"><li class='active'>Tambah Upline Baru</li></ol>	
                    </div>
                    <div class="modal-body">                        
						<table id="example" class="table table-responsive table-hover table-bordered display groceryCrudTable" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>USERID</th>
                                                            <th>Username</th>
                                                            <th>Nama</th>         
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
     
                                                        <?php
                                                            if($GenUpline):
                                                                foreach ($GenUpline as $v) {                                                                    
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $v->id; ?></td>
                                                                        <td><?php echo $v->username; ?></td>
                                                                        <td><?php echo $v->full_name; ?></td>                                                                        
                                                                        <td>
                                                                            <?php 
                                                                            if(strtolower($v->STATUS) == "terdaftar"){
                                                                                ?>
                                                                                <i class="label label-warning"><?php echo $v->STATUS; ?></i>
                                                                            <?php
                                                                            }else{                                                                                    
                                                                                    ?>
                                                                            <a href="<?php echo base_url();?>admin/master_marketing/singlelevel/upline_add/<?php echo $v->id;?>" class="btn btn-primary btn-sm" role="button"  onclick="return confirm('Are you sure want to added?');"></i><i class="glyphicon glyphicon-plus"></i></a>
                                                                            <?php
                                                                            }
                                                                            ?>
                                    </td>
                                       
                                                                    </tr>
                                                                <?php
                                                                }
                                                                
                                                            else:
                                                                  ?>  
                                                                    <tr>
                                                                        <td colspan="5">empty record</td>
                                                            </tr>
                                                            <?php
                                                                
                                                            endif;
                                                        
                                                        ?>
                                                    </tbody>
                                                </table>
                    </div>
                    <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Close</button></div>
                </div>
            </div>
        </div>
    
    
    <div class="modal fade" role="dialog" tabindex="-1" id="myModal1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Daftar User</h4>
                        <ol class="breadcrumb"><li class='active'>Tambah Downline Baru</li></ol>	
                    </div>
                    <div class="modal-body">                        
						<table id="example" class="table table-responsive table-hover table-bordered display groceryCrudTable" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>USERID</th>
                                                            <th>Username</th>
                                                            <th>Nama</th>         
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
     
                                                        <?php
                                                            if($GenUpline):
                                                                foreach ($GenUpline as $v) {                                                                    
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $v->id; ?></td>
                                                                        <td><?php echo $v->username; ?></td>
                                                                        <td><?php echo $v->full_name; ?></td>                                                                        
                                                                        <td>
                                                                            <?php 
                                                                            if(strtolower($v->STATUS) == "terdaftar"){
                                                                                ?>
                                                                                <i class="label label-warning"><?php echo $v->STATUS; ?></i>
                                                                            <?php
                                                                            }else{                                                                                    
                                                                                    ?>
                                                                            <a href="<?php echo base_url();?>admin/master_marketing/singlelevel/downline_add/<?php echo $GenID."/".$v->id;?>" class="btn btn-primary btn-sm" role="button" onclick="return confirm('Are you sure want to added?');"></i><i class="glyphicon glyphicon-plus"></i></a>
                                                                            <?php
                                                                            }
                                                                            ?>
                                    </td>
                                       
                                                                    </tr>
                                                                <?php
                                                                }
                                                                
                                                            else:
                                                                  ?>  
                                                                    <tr>
                                                                        <td colspan="5">empty record</td>
                                                            </tr>
                                                            <?php
                                                                
                                                            endif;
                                                        
                                                        ?>
                                                    </tbody>
                                                </table>
                    </div>
                    <div class="modal-footer"><button class="btn btn-default" type="button" data-dismiss="modal">Close</button></div>
                </div>
            </div>
        </div>
</div>
          
