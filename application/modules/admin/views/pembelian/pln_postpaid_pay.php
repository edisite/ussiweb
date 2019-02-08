<?php echo validation_errors(); ?>

<div class="row">
    <!-- left column -->
    <div class="col-md-12">  
      <!-- general form elements -->
            <div class="box box-info box-solid">
                <div class="box-header box-info with-border box-solid">
                                  <h3 class="box-title">Langkah 2 - Pembayaran</h3>
                </div>
                <form role="form" method="post" action="tpembelian/com/pln_postpaid_pay">
                    <!-- form start -->
                    <div class="box-body"> 
                        <div class="col-xs-2">
                            <div class="form-group">
                                  <label class="input-medium">METODE BAYAR</label>
                            </div>
                        </div>
                        <div class="col-xs-4" >  
                            <div class="form-group">  
                                <table class="table table-striped">
                                   <tr>                        
                                       <td><input type="radio" name="paytype" id="optionsRadios1" value="agent" checked> Cash</td>
                                   </tr>
                                   <tr>                        
                                       <td><input type="radio" name="paytype" id="optionsRadios2" value="nasabah" disabled=""> Debit Tabungan BMT</td>
                                   </tr>
                                   <tr>                        
                                       <td><button type="submit" class="btn btn-primary pull-right input-medium">Proses Pembayaran</button></td>
                                   </tr>
                                </table>
                           </div>
                        </div>
                        <div class="col-xs-4">
                                <div class="alert alert-warning">
                                    <strong>Info!</strong> Metode pembayaran via debit tabungan belum aktive! </div>

                        </div>
                    </div>  
                    <input type="hidden" name="sticket" value="<?php echo $ticket; ?>">
                    <input type="hidden" name="scode" value="<?php echo $code; ?>">
                    <input type="hidden" name="smeterid" value="<?php echo $meterid; ?>">
                </form>
            </div>
    </div>
</div>
    
    
   