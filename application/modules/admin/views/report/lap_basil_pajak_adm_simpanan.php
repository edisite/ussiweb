<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Filter Laporan Simpanan</h3>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="report/trans_simpanan/anggaran_basil_pajak_adm" method="post">
               
               <div class="form-group">                    
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Produk</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdproduk">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdprodk as $subkdprodk) {
                              echo "<option value=".$subkdprodk->kode.">".$subkdprodk->kode." - ".$subkdprodk->deskripsi."</option>";
                          }?>                          
                      </select>
                    </div> 
                </div> 
                      
                <div class="form-group">
                     
                    <label class="control-label col-md-2 col-sm-3 col-xs-12">Kode Kantor</label>
                    <div class="col-md-3 col-sm-3 col-xs-12">
                        <select class="form-control" name="kdkantor">
                            <option value="all">* . Semua</option>
                            <?php foreach ($kdkantr as $subkdkantr) {
                              echo "<option value=".$subkdkantr->KODE_KANTOR.">".$subkdkantr->KODE_KANTOR." - ".$subkdkantr->NAMA_KANTOR."</option>";
                          }?>                          
                      </select>
                    </div> 
                </div>
                
            <div class="ln_solid"></div>
            <div class="form-group">
                <label class="control-label col-md-2 col-sm-3 col-xs-12"></label>
              <div class="col-md-1 col-sm-3 col-xs-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>


