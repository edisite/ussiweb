<?php echo validation_errors(); ?>

<div class="row">
        <!-- left column -->
        <div class="example-modal">
            <div class="modal modal-primary">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Confirm</h4>
                  </div>
                    <form name="simpanan" action="<?php echo base_url();?>admin/tpembelian/com/pulsa_purchase" method="post" accept-charset="utf-8">
                  <div class="modal-body">
                             <span>Apakah anda yakin akan membeli?</span>
                            <table class="table tab-content">
                              <tr>
                                <td>Produk</td>
                                <td><?php echo $produk.' - '.$alias; ?></td>                             
                              </tr>
                              <tr>
                                <td>Nomor Handphone</td>
                                <td><?php echo $nohandset; ?></td>                             
                              </tr>
                              <tr>
                                <td>Harga</td>
                                <td><?php echo $harga; ?></td>                                
                              </tr>                           
                              <tr>
                                <td>Jenis Pembayaran</td>
                                <td><?php echo $paytype; ?></td>                                
                              </tr>     
                              

                            </table>
                      <input  type="hidden" id="produk" name="produk" class="form-control input-sm" value="<?php echo $produk; ?>">
                            <input  type="hidden" id="meterid" name="msisdn" class="form-control input-sm" value="<?php echo $nohandset; ?>">
                            <input  type="hidden" id="paytype" name="paytype" class="form-control input-sm" value="<?php echo $paytype; ?>">
                            <input  type="hidden" id="catalogid" name="catalogid" class="form-control input-sm" value="<?php echo $catalogid; ?>">
                            
                            
                    </div>
                  <div class="modal-footer">
                    <button onclick="location.href='<?php echo base_url();?>admin/tpembelian/com/pulsa'" type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-outline">YES</button>
                  </div>
                </div>
                <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
            </div>
            <!-- /.modal -->
          </div>
    <!-- /.example-modal -->
</div>
          <!-- /.box -->

        <!-- /.col -->
      <!-- /.row -->

<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../plugins/select2/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- bootstrap datepicker -->
<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Page script -->
<script>
  $(function () {
    //Initialize Select2 Elements
    $(".select2").select2();
    //Datemask dd/mm/yyyy
    $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
    //Money Euro
    $("[data-mask]").inputmask();
    //Date picker
    $('#datepicker').datepicker({
	  format:'dd/mm/yyyy',
      autoclose: true
    });
  });
</script>
  <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }
    .example-modal .modal {
      background: transparent !important;
    }
  </style>
