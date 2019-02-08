<?php echo validation_errors(); ?>
<?php 
session_commit();
if($this->session->flashdata('msg')): ?>
    <p><?php echo $this->session->flashdata('msg'); ?></p>
<?php endif; ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header">
                    <h3 class="box-title">Laporan Teller</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
               <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">User Name</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="usrid">
                            <?php foreach ($datauser as $useradm) {
                              echo "<option value=".$useradm->id.">".$useradm->id." - ".$useradm->first_name." - ".$useradm->last_name."</option>";
                          }?>
                      </select>
                    </div>                          
                </div>
               <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Kode Perk Teller</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdperkiraan">
                           <?php foreach ($kodeperk as $subperk) {
                              echo "<option value=".$subperk->kode_perk.">".$subperk->kode_perk." - ".$subperk->nama_perk."</option>";
                          }?>                            
                      </select>
                    </div>                            
                </div> 
            <div class="ln_solid"></div>
            <div class="form-group">
              <div class="col-md-3 col-sm-3 col-xs-12 col-md-offset-3">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

