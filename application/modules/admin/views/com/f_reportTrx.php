<?php echo validation_errors(); ?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="box box-primary">
            <div class="box-header with-border">
                    <h3 class="box-title">Pilih Periode</h3>
                    <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse">
                <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                <i class="fa fa-times"></i></button>
            </div>
            </div>
          
          <div class="box-body">
              <div class="col-sm-12 invoice-col">
                <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
               <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12">Dari Tanggal</label>
                    <div class="col-md-8 col-sm-3 col-xs-12">
                        <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo $tgl_fr_t; ?>">
                    </div> 
               </div>
               <div class="form-group">
                   <label class="control-label col-md-4 col-sm-4 col-xs-12">Sampai Tanggal</label>
                    <div class="col-md-8 col-sm-3 col-xs-12">
                        <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo $tgl_to_t; ?>">
                    </div>
                </div>
               <div class="form-group">
                   <label class="control-label col-md-4 col-sm-4 col-xs-12">Produk Pembayaran</label>
                   <div class="col-md-8 col-sm-3 col-xs-12">
                    <select class="form-control" name="produklist">
                      <option value="">*Semua</option>
                      <option value="5002">[5002]PLN POSTPAID</option>
                      <option value="9001">[9001]BPJS</option>
                    </select>
                   </div>
                </div>
                <div class="ln_solid"></div>
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-4 col-xs-12"></label>
                  <div class="col-md-8 col-sm-4 col-xs-12">
                    <button type="submit" class="btn btn-primary pull-left">Submit</button>
                  </div>
                    
                </div>                
                </form>
             </div>             
          </div>        
      </div>
    </div>
</div>
    

    <script src="../js/moment/moment.min.js"></script>
    <script src="../js/datepicker/daterangepicker.js"></script>

    <script>
        
     
    
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
      $(document).ready(function() {
        $('#tgl_tagihan').daterangepicker({
          
          singleDatePicker: true,
          //showDropdowns: true
          calender_style: "picker_4"
          
        }, function(start, end, label) {
          console.log(start.toISOString(), end.toISOString(), label);
        });
      });
      
      /* Tanpa Rupiah */
	var tanpa_rupiah = document.getElementById('tanpa-rupiah');
	tanpa_rupiah.addEventListener('keyup', function(e)
	{
		tanpa_rupiah.value = formatRupiah(this.value);
	});
	var basilsetoran = document.getElementById('basilsetoran');
	basilsetoran.addEventListener('keyup', function(e)
	{
		basilsetoran.value = formatRupiah(this.value);
	});
        var tanpa_rupiah = document.getElementById('tanpa-rupiah');
	tanpa_rupiah.addEventListener('keyup', function(e)
	{
		tanpaaja.value = convert_to_number(this.value);
	});

	
	/* Dengan Rupiah */
	var dengan_rupiah = document.getElementById('dengan-rupiah');
	dengan_rupiah.addEventListener('keyup', function(e)
	{
		dengan_rupiah.value = formatRupiah(this.value, 'Rp. ');
	});
        
        
	
	/* Fungsi */
	function formatRupiah(angka, prefix)
	{
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}
        function convertToAngka(rupiah)
        {
                return parseInt(rupiah.replace(/,.*|[^0-9]/g, ''), 10);
        }
        function convert_to_number(rupiah)
	{
		return intval(preg_replace(/,.*|[^0-9]/, '', rupiah));
	}
    </script>
