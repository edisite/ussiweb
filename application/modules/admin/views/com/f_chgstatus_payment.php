<?php echo validation_errors(); ?>
<?php if($data){foreach ($data as $sub_san_res) {
    $tanggal            = $sub_san_res->dtm;
    $nominal            = $sub_san_res->nominal;
    $destid             = $sub_san_res->destid;
    $price_tagihan      = $sub_san_res->tagihan;
    $res_code           = $sub_san_res->res_code;
    $produk             = $sub_san_res->product;
    $username           = $sub_san_res->username;    
    $master_id          = $sub_san_res->master_id;    
    $id_com             = $sub_san_res->id;    
    $padm_indosis         = $sub_san_res->adm_indosis;    
    $padm_bmt         = $sub_san_res->adm_bmt;    
    $padm_agen         = $sub_san_res->adm_agen;       
    $userid_com         = $sub_san_res->userid;    
    $padm_provider           = $sub_san_res->adm_provider;    
    
    $totaladm = $padm_indosis + $padm_bmt + $padm_agen + $padm_provider;
}}
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Update status transaksi</h3>
                     <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                          <i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                          <i class="fa fa-times"></i></button>
                      </div>
            </div>
            <div class="box-body">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">   
                    <input type="hidden" name="master_id" class="form-control" value="<?php echo $master_id; ?>">
                    <input type="hidden" name="idtrx" class="form-control" value="<?php echo $id_com; ?>">
                    <input type="hidden" name="useridtrx" class="form-control" value="<?php echo $userid_com; ?>">
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tanggal Transaksi</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" value="<?php echo $tanggal; ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipe</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo $nominal; ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nomor ID</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo $destid; ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Produk ID</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo $produk; ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tagihan</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo number_format($price_tagihan,0,",","."); ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>                     
                     <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Adm</label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo number_format($totaladm,0,",","."); ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Status Transaksi   </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo $message_rescode; ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">AgentID   </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" value="<?php echo $username; ?>" readonly="readonly" placeholder="Read-Only Input">
                        </div>
                      </div>
                     
                      <div class="clearfix"></div>
                      <div class="form-group">
                        <label class="control-label col-md-0 col-sm-3 col-xs-12"></span>
                        </label>
                        
                       <div class="well col-md-12 col-sm-9 col-xs-12 label-info">
                           <h3><i>Anda akan mengupdate transaksi sebagai transaksi sukses<br>Jika sudah yakin <strong>checklist button Perubahan transaksi</strong> dibawah untuk melajutkan Perubahan status transaksi data.</i>
                        </h3>
                        </div>
                      </div>   
                      
                      <div class="form-group">
                          
                        <label class="col-md-3 col-sm-3 col-xs-12 control-label">Perubahan Status Transaksi
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        
                          
                          <div class="checked">
                            <label>
                                <input type="checkbox" class="flat" name="iCheck"> Yakin
                            </label>
                          </div>                    
                        </div>
                      </div> 
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Serial Number(SN)  </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="sn_text" value="" placeholder="Number SN">
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Keterangan <span class="required">*</span>
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <textarea class="form-control" rows="3" name="desc_trx" placeholder='Tolong tuliskan alasan dan cantumkan message yang diterima dari provider(lengkap)'></textarea>
                        </div>
                      </div>
                        <div class="ln_solid"></div>
                        <div class="box-footer">
                            <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                <a href="<?php echo base_url();?>admin/report/com/logtrxpayment"><button type="button" id="tBCancel" class="btn btn-primary" name="tBCancel">Cancel</button></a>
                    <button type="submit" class="btn btn-success">Submit</button>
                        </div>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
    

    <script src="../js/moment/moment.min.js"></script>
    <script src="../js/datepicker/daterangepicker.js"></script>

    <script type="text/javascript">
        
       function tBCancel() {
            window.location = "http://www.somedomain.com/";
          }

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
          //showDropdowns: true
          calender_style: "picker_4"
          
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
    </script>
