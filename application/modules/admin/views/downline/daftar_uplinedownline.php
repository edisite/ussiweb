<?php echo validation_errors(); ?>

<div class="row">    
    <div class="col-md-5 col-sm-12 col-xs-12">
        <div class="box box-success box-solid">
        <div class="box-header with-border">
             <h3 class="box-title">DAFTAR UPLINE</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-danger">
                            Tambah Baru
                        </button>
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
                                <td><?php echo $v->username;?></td>
                                <td><?php echo $v->full_name;?></td>
                                <td><span class="btn btn-danger pull-right fullwidth"><?php echo $v->total;?></span></td>
                                <td class='actions'>
                                    <a href="<?php echo base_url();?>admin/master_marketing/singlelevel/upline_n/<?php echo $v->parent_id;?>" class="edit_button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" role="button">
                                    <span class="ui-button-icon-primary ui-icon ui-icon-document"></span>
                                    <span class="ui-button-text ">Show Downline</span>
                                </a>
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
            
            <br>
        </div>
    </div>
</div>
          
