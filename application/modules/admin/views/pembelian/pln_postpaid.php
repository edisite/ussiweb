<?php echo validation_errors(); ?>

<div class="row">
    <!-- left column -->
    <div class="col-md-12">
   
      <!-- general form elements -->

      <div class="box box-info box-solid">
        <div class="box-header box-danger with-border box-solid">
                          <h3 class="box-title">Form Pembayaran Tagihan PLN Postpaid</h3>
        </div>
          <form role="form" method="post" action="tpembelian/com/pln_postpaid">
        <!-- form start -->
         <div class="box-body">          
             <div class="form-group">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <p class="input-medium">NO Meter / ID PLN</p>
                </div>
             </div>
            <div class="form-group">
                <div class="col-md-4 col-sm-12 col-xs-12" >
                    <input type="text" name="nometer" id="cc" class="form-control input-medium" value="<?php if(empty($nometer)): echo ''; else: echo $nometer; endif; ?>" placeholder="Masukan disini..."> 
               </div>
            </div>
             <div class="form-group">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <button type="submit" class="btn btn-primary input-medium">Inquiry</button>  
                </div>
             </div>
         </div>
            </form>
        
    <?php
    if($data):
        foreach ($data as $val) {        
            $res_meterid    = $val['meterid'];
            $res_name       = $val['cust_name'];
            $res_daya       = $val['daya'];
            $res_tagihan    = $val['tagihan'];
            $res_periode    = $val['periode'];
            $res_adm        = $val['adm'];
            $res_total      = $val['total'];
            $res_code       = $val['code'];

    ?>
    <!--<div class="col-md-6">-->
        <div class="box">         
        <div class="box-body">
            <h4 class="box-title"><strong>DATA TAGIHAN NASABAH</strong></h4>
            <div class="table">
                <table class="table table-striped">
                <tr>
                    <td width='30%'>METERID</td>
                    <td><?php echo $res_meterid; ?></td>
                </tr>
                <tr>
                    <td>NAMA</td>
                    <td><?php echo $res_name; ?></td>
                </tr>
                <tr>
                    <td>DAYA</td>
                    <td><?php echo $res_daya; ?></td>
                </tr>
                <tr>
                    <td>PERIODE TAGIHAN</td>
                    <td><?php echo $res_periode; ?></td>
                </tr>
                <tr>
                    <td>TAGIHAN</td>
                    <td><?php echo $res_tagihan; ?></td>
                </tr>
                <tr>
                    <td>ADM</td>
                    <td><?php echo $res_adm; ?></td>
                </tr>
                <tr>
                    <td>TOTAL</td>
                    <td><strong><?php echo $res_total; ?></strong></td>
                </tr>
                <tr>
                    <td>TICKET ID</td>
                    <td><?php echo $res_code; ?></td>
                </tr>
                </table>
            </div>
        </div>
            <div class="box box-footer">
                <a href="tpembelian/com/pln_postpaid_pay/ticketid/<?php echo $res_code; ?>/<?php echo $res_meterid; ?>" target="_blank" class="btn btn-default pull-right input-medium">Langkah Berikutnya - Pembayaran</a>
            </div>
        </div>
        
<!--    </div>
</div>-->
<?php



        }
    endif;
?>

              </div>
                    <!-- /.input group -->

    </div>