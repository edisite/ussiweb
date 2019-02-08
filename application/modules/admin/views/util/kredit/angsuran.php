<script>
document.getElementById("demo").innerHTML = Date();

function startCalc()
{
  interval = setInterval("calc()",1);
}
function calc()
{
  setor = document.angsuran.jml_setoran.value;
  document.angsuran.pokok_transaksi.value = setor *1;


  four = document.angsuran.saldo.value;
  five = document.angsuran.total_diterima.value;
  document.angsuran.saldo_tab_trans.value = (four * 1) + (five * 1);
}
function stopCalc()
{
  clearInterval(interval);
}


function enabledisable()
{
  document.getElementById("button").disabled = false;
}



</script>

<div class="row">
<!-- left column -->
<div class="col-md-8">
  <!-- general form elements -->
<div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Jenis Transaksi</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
	<form name="angsuran" action="tpembiayaan/angsuran/payment_proces" method="post" accept-charset="utf-8">
      <div class="box-body">
		  <div class="form-group">
			<div class="col-xs-5" >
			  <label>Kode Transaksi</label>
			  <select class="form-control" name="kode_trans" id="kode_trans" readonly>
				<option value="300">300 - Angsuran Kredit Tunai </option>
			  </select>
			</div>
		  </div>
      </div>

		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Data Rekening <?php echo '<i style="color:red;font-size:25px;font-family:calibri ;">['.$SETSTATUS.']</i>'; ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
					<div class="form-group col-md-12">
						<div class="col-xs-4" >
						  <label>No Rekening</label>
						  <input type="text" class="form-control input-sm" name="no_rekening" value="<?php echo $SETNOREK; ?>">
						</div>
						<div class="col-xs-8" >
						  <label>&nbsp;</label>
						  <input type="text" class="form-control input-sm" name="produk" value="<?php echo $SETPRODK; ?>">
						</div>
						<div class="col-xs-4">
						  <label>Nasabah ID</label>
						  <input type="text" class="form-control input-sm" name="nasabah_id" value="<?php echo $SETNSBID; ?>">
						</div>
						<div class="col-xs-8">
						  <label>&nbsp;</label>
						  <input type="text" class="form-control input-sm" name="nama_nasabah" value="<?php echo $SETNAMAP; ?>">
						</div>
						<div class="col-xs-4">
						  <label>Jumlah Pinjaman</label>
						  <input type="text" class="form-control input-sm" name="jml_pinjam" value="<?php echo $SETJMLPIN; ?>">
						</div>
						<div class="col-xs-4">
						  <label>Nisbah</label>
						  <input type="text" class="form-control input-sm" name="nisbah" value="<?php echo $SETNISBAH; ?>">
						</div>
						<div class="col-xs-4">
						  <label>&nbsp;</label>
						  <input type="text" class="form-control input-sm" name="keterangan" value="<?php echo $SETKET; ?>">
						</div>
						<div class="col-xs-4">
						  <label>Tgl Pencarian</label>
						    <div class="input-group">
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input id="tgl_pencarian" name="tgl_pencarian" type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLREAL; ?>">
							</div>
						</div>
					   <div class="col-xs-4">
						  <label>Jatuh Tempo</label>
							<div class="input-group">
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <input id="jatuh_tempo" name="tgl_tempo" type="text" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGTEMP; ?>">
							</div>
						</div>
						<div class="col-xs-4">
						  <label>Sisa Pinjaman</label>
						  <input type="text" class="form-control input-sm" name="sisa_pinjaman" value="<?php echo $SETSISA; ?>">
						</div>
						<div class="col-xs-4">
						  <label>Kolek Saat Ini</label>
						  <input type="text" name="kolek" class="form-control input-sm">
						</div>
					   <div class="col-xs-4">
						  <label>&nbsp;</label>
						</div>
						<div class="col-xs-4">
						  <label>Bunga YAD</label>
						  <input type="text" name="bunga_yad" class="form-control input-sm" value="<?php echo $SETBUYAD; ?>">
						</div>
					</div>
				</div>
			</div>

		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $SETMESSAGE; ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
					<div class="form-group col-md-12">
					<?php foreach ($SETTUNGGAKAN as $sub_tunggakan) 
					{
				        $saldo_pokok  = $sub_tunggakan->TUNGGAKAN_POKOK;
                 		$saldo_bunga  = $sub_tunggakan->TUNGGAKAN_BUNGA;
                 		$saldo_denda  = $sub_tunggakan->DENDA; 
                 		$saldo_adm    = $sub_tunggakan->ADM_LAINNYA;

				        if($saldo_pokok     > 0){}else{ $saldo_pokok        = 0;}
				        if($saldo_bunga     > 0){}else{ $saldo_bunga        = 0;}
				        if($saldo_denda     > 0){}else{ $saldo_denda        = 0;}
				        if($saldo_adm       > 0){}else{ $saldo_adm          = 0;}
					?>
						<div class="col-xs-3" >
						  <label>Pokok</label>
						  <input type="text" id="pokok_tunggakan" name="pokok_tunggakan" class="form-control input-sm" placeholder="0.00" value="<?php echo $saldo_pokok; ?>" readonly>
						</div>
						<div class="col-xs-3" >
						  <label>Bunga</label>
						  <input type="text" id="bunga_tunggakan" name="bunga_tunggakan" class="form-control input-sm" placeholder="0.00" value="<?php  echo $saldo_bunga;  ?>" readonly>
						</div>
						<div class="col-xs-3">
						  <label>Denda</label>
						  <input type="text" id="denda_tunggakan" name="denda_tunggakan" class="form-control input-sm" placeholder="0.00" value="<?php echo $saldo_denda;  ?>" readonly>
						</div>
						<div class="col-xs-3">
						  <label>Administrasi</label>
						  <input type="text" id="adm_tunggakan" name="adm_tunggakan" class="form-control input-sm" placeholder="0.00" value="<?php echo $saldo_adm; ?>" readonly>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		
		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $SETMESSAGE2; ?></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
					<div class="form-group col-md-12">
					<?php foreach ($SETTAGIHAN as $sub_tagihan) {
						  $tag_pokok = $sub_tagihan->TAG_POKOK;
                		  $tag_bunga = $sub_tagihan->TAG_BUNGA;
                		  $tag_denda = $sub_tagihan->TAG_DENDA;
                          $tag_adm   = $sub_tagihan->TAG_ADM_LAINNYA;

                          if($tag_pokok > 0)  {}else{$tag_pokok = 0; }
            			  if($tag_bunga > 0)  {}else{$tag_bunga = 0; }
                          if($tag_denda > 0)  {}else{$tag_denda = 0; }
                          if($tag_adm > 0)    {}else{$tag_adm   = 0; }
					?>
						<div class="col-xs-3" >
						  <label>Pokok</label>
						  <input type="text" id="pokok_tagihan" name="pokok_tagihan" class="form-control input-sm" placeholder="0.00" value="<?php echo $tag_pokok; ?>" readonly>
						</div>
						<div class="col-xs-3" >
						  <label>Basil</label>
						  <input type="text" id="basil_tagihan" name="basil_tagihan" class="form-control input-sm" placeholder="0.00" value="<?php echo $tag_bunga; ?>" readonly>
						</div>
						<div class="col-xs-3">
						  <label>Denda</label>
						  <input type="text" id="denda_tagihan" name="denda_tagihan" class="form-control input-sm" placeholder="0.00" value="<?php echo $tag_denda; ?>" readonly>
						</div>
						<div class="col-xs-3">
						  <label>Administrasi</label>
						  <input type="text" id="adm_tagihan" name="adm_tagihan" class="form-control input-sm" placeholder="0.00" value="<?php echo $tag_adm; ?>" readonly>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		
		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Info Pelunasan</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
					<div class="form-group col-md-10">
						<div class="col-xs-3" >
						  <label>Pokok</label>
						  <input type="text" id="pokok_info" name="pokok_info" class="form-control input-sm" placeholder="0.00" readonly>
						</div>
						<div class="col-xs-3" >
						  <label>Basil</label>
						  <input type="text" id="basil_info" name="basil_info" class="form-control input-sm" placeholder="0.00" readonly>
						</div>
						<div class="col-xs-3">
						  <label>Denda</label>
						  <input type="text" id="denda_info" name="denda_info" class="form-control input-sm" placeholder="0.00" readonly>
						</div>
						<div class="col-xs-3">
						  <label>Administrasi</label>
						  <input type="text" id="adm_info" name="adm_info" class="form-control input-sm" placeholder="0.00" readonly>
						</div>
					</div>
				</div>
			</div>
		
		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Jumlah Setoran</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
					<div class="form-group col-md-10">
					<?php foreach ($SETTUNGGAKAN as $sub_tunggakan) 
					{
                 		$saldo_bunga  = $sub_tunggakan->TUNGGAKAN_BUNGA;
				        if($saldo_bunga     > 0){}else{ $saldo_bunga        = 0;}

					?>
						<div class="col-xs-4" >
						  <label>Jumlah Setoran</label>
						  <input type="text" id="jml_setoran" name="jml_setoran" class="form-control input-sm" placeholder="0.00" onFocus="startCalc();" onBlur="stopCalc();" required value="<?php  echo round($saldo_bunga);  ?>" onclick="enabledisable()">
						</div>
						<div class="col-xs-4" >
						  <label>Saldo Setelah Transaksi</label>
						  <input type="text" id="saldo_trans" name="saldo_trans" class="form-control input-sm" placeholder="0.00">
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		
		<div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Rincian Transaksi</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <div class="row">
					<div class="form-group col-md-10">
						<div class="col-xs-4" >
						  <label>Tgl Trans</label>
						    <div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
							  <input type="text" id="tgl_transaksi" name="tgl_transaksi" class="form-control input-sm" id="datepicker" value="<?php echo date("Y-m-d")?>">
							</div>
						  <label>Pokok</label>
						    <input type="text" id="pokok_transaksi" name="pokok_transaksi" class="form-control input-sm" placeholder="0.00" onchange='tryNumberFormat(this.form.thirdBox);'>
						  <label>Basil</label>
						    <input type="text" id="basil_transaksi" name="basil_transaksi" class="form-control input-sm" placeholder="0.00">
						  <label>Discount</label>
						    <input type="text" id="diskon_transaksi" name="diskon_transaksi" class="form-control input-sm" placeholder="0.00">
						</div>
						<div class="col-xs-4" >
						  <label>No.Kwitansi</label>  
						  <input type="text" id="no_kwitansi" name="no_kwitansi" class="form-control input-sm" value="<?php echo $SETKWITANSI; ?>">
						  <label>Denda</label>
						  <input type="text" id="denda" name="denda" class="form-control input-sm" placeholder="0.00">
						  <label>Finalty</label>
						  <input type="text" id="penalty" name="penalty" class="form-control input-sm" placeholder="0.00">
						  <label>Tab</label>
						  <input type="text" id="tab" name="tab" class="form-control input-sm" placeholder="0.00">
						</div>
						<div class="col-xs-2" >
						  <label>Angsuran</label>
						  <select class="form-control">
                                    <?php foreach ($SETANGS as $sub_san_res) {
                                    	$angsur = $sub_san_res->ANGSURAN_KE;
                                     }

                                     $x = 1;
                                     do {
										  echo  "<option selected=".$angsur." value=''>".$x."</option>";
										    $x++;
										} while ($x <= $angsur);
                                     ?>
					  </select>
						</div>
						<div class="col-xs-9" >
						  <label>Keterangan</label>
						  <input type="text" name="keterangan_transaksi" value="<?php echo $SETMESSAGE3; ?>" class="form-control input-sm"></input>
						</div>
					</div>
                    		<input type="hidden" id="due_date" name="due_date" class="form-control input-sm" value="<?php echo $SETTGTEMP; ?>">
				</div>
			</div>
          <!-- /.box -->

          <!-- general form elements -->
        <div class="box box-primary">
                          <!-- /.box-body -->

              <!-- general form elements -->
        <div class="box box-primary">
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <!-- /.box-body -->
			<div class="box-footer">
				<button type="submit" id="button" name="button" class="btn btn-primary" disabled>Submit</button>
			</div>
		</div>
	</div>
          <!-- /.form-group -->
    </form>
    </div>
      <!-- /.tab-pane -->
    </div>
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

