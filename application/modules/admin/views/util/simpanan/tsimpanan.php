<!--        $this->mViewData['SETNOREK']         = $out_norek;
        $this->mViewData['SETNSBID']         = $out_nasabah_id;
        $this->mViewData['SETNAMAP']         = $out_nama_nasabah;
        $this->mViewData['SETADDRS']         = $out_alamat;
        $this->mViewData['SETPRODK']         = $out_des_produk;
        $this->mViewData['SETTGLMU']         = $out_tgl_mulai;
        $this->mViewData['SETTGLJT']         = $out_tgl_jt;
        $this->mViewData['SETNOMIN']         = $out_nominal;
        $this->mViewData['SETDESCR']         = "Setoran Tabungan tunai an: ".$out_norek."[".$out_nama_nasabah."]";-->
<script type="text/javascript">
function enabledisabletext()
{
  if(document.simpanan.kode_trans.value=='100')
  {
  document.simpanan.jml_setor.disabled=false;
  document.simpanan.jml_tarik.disabled=true;
  }
  if(document.simpanan.kode_trans.value=='200')
  {
  document.simpanan.jml_tarik.disabled=false;
  document.simpanan.jml_setor.disabled=true;
  }
}

function startCalc()
{
  interval = setInterval("calc()",1);
}
function calc()
{
  one = document.simpanan.jml_setor.value;
  two = document.simpanan.jml_tarik.value;
  three = document.simpanan.adm_tutup.value;
  document.simpanan.total_diterima.value = (one * 1) + (two * 1) - (three * 1);

  four = document.simpanan.saldo.value;
  five = document.simpanan.total_diterima.value;
  document.simpanan.saldo_tab_trans.value = (four * 1) + (five * 1);
}
function stopCalc()
{
  clearInterval(interval);
}
</script>

<div class="row">
   <!-- left column -->
   <div class="col-md-8">
<form name="simpanan" action="tsimpanan/simpanan/simpan_post" method="post" accept-charset="utf-8">
     <!-- general form elements -->
     <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Jenis Transaksi</h3>
        </div>
        <div class="box-body">
          <div class="form-group col-md-6">
            <label>Kode Transaksi</label>
            <select class="form-control" name="kode_trans" id="kode_trans" onChange="enabledisabletext()">
              <option value="#">--- PILIH ---</option>
              <option value="100">100 - Setoran Tabungan Tunai</option>
              <option value="200">200 - Pengambilan Tabungan Tunai </option>
            </select>
          </div>
        </div>
     <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Data Rekening</h3>
        </div>
        <div class="box-body"> 
                <div class="form-group">
                         <div class="col-xs-4" >
                           <label>No Rekening</label>
                           <input type="text" name="no_rekening" class="form-control input-sm" value="<?php echo $SETNOREK; ?>" placeholder="">
                         </div>
                         <div class="col-xs-4">
                           <label>&nbsp;</label>
                           <input type="text" name="produk" class="form-control input-sm" value="<?php echo $SETPRODK; ?>" placeholder="">
                         </div>
                         <div class="col-xs-4">
                           <label>No Alternatif</label>
                           <input type="text" name="nomor_alternatif" class="form-control input-sm" value="<?php echo $SETALTER; ?>" placeholder="">
                         </div>
                 </div>
                    <div class="form-group">
                            <div class="col-xs-4">
                              <label>Nasabah ID</label>
                              <input type="text" name="nasabah_id" class="form-control input-sm" value="<?php echo $SETNSBID; ?>" placeholder="">
                            </div>
                            <div class="col-xs-4">
                              <label>&nbsp;</label>
                              <input type="text" name="nama_nasabah" class="form-control input-sm" value="<?php echo $SETNAMAP; ?>" placeholder="">
                            </div>
                            <div class="col-xs-4">
                              <label for="exampleInputFile"></label>
                              <input type="file" id="exampleInputFile">
                              <p class="help-block">Photo dan Tanda Tangan</p>
                            </div>
                    </div>
                <div class="form-group">
                            <div class="col-xs-12" >
                                <label>Alamat</label>
                              <input type="text" name="alamat" class="form-control input-sm" value="<?php echo $SETADDRS; ?>" placeholder="">
                            </div>
                            
                </div>
                    <div class="form-group">
                            <div class="col-xs-4" >
                                <label>Tanggal Register:</label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" name="tgl_reg" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLMU; ?>">
                                </div>
                            </div>
                            <div class="col-xs-4" >
                                <label>Jatuh Tempo:</label>
                                <div class="input-group">
                                  <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                  </div>
                                  <input type="text" name="jatuh_tempo" class="form-control input-sm" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask value="<?php echo $SETTGLJT; ?>">
                                </div>
                            </div>
                            <div class="col-xs-4">
                              <label>Saldo Tabungan</label>
                              <input type="text" name="saldo" class="form-control input-lg" value="<?php echo $SETNOMIN; ?>" placeholder="" id="saldo" onFocus=”startCalc();”>
                            </div>
                    </div>
            </div>

   <div class="box box-primary">
       <div class="box-header with-border">
         <h3 class="box-title">Data Transaksi</h3>
       </div>
       <!-- /.box-header -->

       <!-- form start -->
       <div class="box-body">
                    <div class="form-group">
                        <div class="col-xs-6" >
                                <label>Tanggal Transaksi:</label>
                                <div class="input-group date">
                                        <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                        </div>
                                  <input type="text" name="tgl_transaksi" class="form-control input-sm" id="datepicker">
                                </div>
                        </div>
                        <div class="col-xs-6" >
                                <label>Sandi</label>
                            <select class="form-control" name="sandi">
                                    <?php foreach ($SETSANDT as $sub_san_res) {
                                        echo "<option value=".$sub_san_res->sandi_kode.">".$sub_san_res->sandi_kode." - ".$sub_san_res->sandi_deskripsi."</option>";
                                     }?>
                            </select>
                        </div>
                    </div>
           <div class="form-group">
                        <div class="col-xs-6">
                                  <label>No Kwitansi</label>
                                  <input type="text" name="no_kwitansi" class="form-control input-sm" placeholder="Isi No Kwitansi">
                        </div>
                        <div class="col-xs-6">
                                <label>Kolektor</label>
                            <select class="form-control" name="kolektor">
                                    <?php foreach ($SETKOLEK as $sub_kolek_res) {
                                        echo "<option value=".$sub_kolek_res->kode.">".$sub_kolek_res->kode." - ".$sub_kolek_res->deskripsi."</option>";
                                     }?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                            <div class="col-xs-6">
                                    <label>Jumlah Setoran</label>
                                    <input type="text" name="jml_setor" class="form-control input-sm" id="jml_setor" placeholder="0.00" onFocus="startCalc();" onBlur="stopCalc();">                                   
                            </div>
                        
                        <div class="col-xs-6">                                   
                                    <label>Jumlah Penarikan</label>
                                    <input type="text" name="jml_tarik" class="form-control input-sm" id="jml_tarik" placeholder="0.00" onFocus="startCalc();" onBlur="stopCalc();">         
                            </div>
                        <div class="col-xs-6">
                                    <label>Adm. Penutupan</label>
                              <input type="text" name="adm_tutup" class="form-control input-sm" id="adm_tutup" placeholder="0.00" onFocus="startCalc();" onBlur="stopCalc();">
                            </div>
                    </div>  
                    <div class="form-group">
                            <div class="col-xs-6" >
                              <label>Total Diterima</label>
                              <input style="font-weight: bold; font-size: 15px;" type="text" name="total_diterima" class="form-control input-sm"  placeholder="0.00" id="total_diterima" onchange='tryNumberFormat(this.form.thirdBox);' readonly>
                            </div>
                            <div class="col-xs-6" >
                              <label>Pajak</label>
                              <input style="font-weight: bold; font-size: 15px;" type="text" id="pajak" name="pajak" class="form-control input-sm"  placeholder="0.00" disabled="true">
                            </div>
                            <div class="col-xs-6">
                              <label>Keterangan</label>
                                    <textarea class="form-control" rows="2" id="keterangan" name="keterangan"><?php echo $SETDESCR; ?></textarea>
                            </div>
                            <div class="col-xs-6"><br><br>
                              <label>Status : Aktif</label>
                            </div>
                            <div class="col-xs-6">
                              <label>&nbsp;</label>
                            </div>
                            <div class="col-xs-4">
                              <label>Saldo Tabungan Setelah Transaksi</label>
                              <input style="font-size: 15px;" type="text" class="form-control " id="saldo_tab_trans" placeholder="0.00 " name="saldo_tab_trans" onchange='tryNumberFormat(this.form.thirdBox);' readonly>
                            </div>
                    </div>
       </div>
                <!-- /.input group -->
    </div>
    </div>
     <!-- /.box -->
         <div class="box-footer">
           <button type="submit" class="btn btn-primary">Submit</button>
         </div>
                   </div>
                   </div>
    </div>
 <!-- /.tab-pane -->
     </form>
</div>
<script src="../plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="../js/moment/moment.min.js"></script>


<script>
  $(function () {    //Initialize Select2 Elements
    //Date picker
    $('#datepicker').datepicker({
           format:'yyyy-mm-dd',
      autoclose: true
    });
  });

</script>   