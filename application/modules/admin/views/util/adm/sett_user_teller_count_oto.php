<?php echo validation_errors(); ?>

<div class="row">

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Update Hak Akses Teller</h3>
			</div>
			<div class="box-body">
				<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="" method="post">
					<table class="table table-bordered">
						<tr>
							<th style="width:120px">Username </th>
							<td><?php echo $target->username; ?></td>
						</tr>
						<tr>
							<th>Nama </th>
							<td><?php echo $target->first_name. ' '.$target->last_name; ?></td>
						</tr> 
						<tr>
							<th>Jabatan </th>
                                                        <td>
                                                            <input type="text" name="tjabatan" value="<?php echo $JABATAN;?>" id="hutanganda"  class="form-control" />
                                                        </td>
						</tr>
                                                <tr>
							<th>Kode Kantor</th>
							<td>
                                                            <div>
                                                                  <select class="form-control" name="tunitkerja">                                                                     
                                                                        <?php foreach ($kdkantor as $subkdkantr) {
                                                                              if($UNIT_KERJA == $subkdkantr->KODE_KANTOR){
                                                                                  $inj_selected = "selected";
                                                                              }else{
                                                                                  $inj_selected = "";
                                                                              }  
                                                                          echo "<option value=".$subkdkantr->KODE_KANTOR." ".$inj_selected.">".$subkdkantr->KODE_KANTOR." - ".$subkdkantr->NAMA_KANTOR."</option>";
                                                                      }?>                          
                                                                  </select>
                                                            </div>
                                                        </td>
						</tr>
					</table>
                                        <table class="table table-responsive">
                                            <tr>
                                                <td>
                                                    <div><label for="tes">Max Penerimaan(T)</label><input onkeyup="convertToRupiah(this)" type="text" name="tterima" value="<?php echo $PENERIMAAN;?>" id="hutanganda"  class="form-control" />
                                                </td>
                                                <td>
                                                    <div><label for="tes">Max Pengeluaran(T)</label><input onkeyup="convertToRupiah(this)" type="text" name="tkeluar" value="<?php echo $PENGELUARAN;?>" id="hutanganda"  class="form-control" />
                                                
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div><label for="tes">Max Penerimaan(OB)</label><input onkeyup="convertToRupiah(this)" type="text" name="tterimaob" value="<?php echo $PENERIMAAN_OB;?>" id="hutanganda"  class="form-control" />
                                                
                                                </td>
                                                <td>

                                                    <div><label for="tes">Max Pengeluaran(OB)</label><input onkeyup="convertToRupiah(this)" type="text" name="tkeluarob" value="<?php echo $PENGELUARAN_OB;?>" id="hutanganda"  class="form-control" />
                                                
                                                </td>
                                            </tr>
                                           
                                            <tr>

                                                <td colspan="2">
                                                    <div class="form-group">
                                                        <label for="groups">Setting User</label>
                                                        <div>
                                                            <div class="form-group">
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tusercode" value="0" <?php if($USER_CODE == "0" || $USER_CODE == ""){ echo " checked"; }?>> None
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tusercode" value="1"<?php if($USER_CODE == "1"){ echo " checked"; }?>> Teller
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tusercode" value="2"<?php if($USER_CODE == "2"){ echo " checked"; }?>> Otorisator
                                                                </label>
                                                                <label class="checkbox-inline">
                                                                    <input type="radio" name="tusercode" value="3"<?php if($USER_CODE == "3"){ echo " checked"; }?>> Counter Sign
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <td colspan="2">
                                                <div>
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>  
                                            </td>
                                        </table>
                                        
                                     </form>
			</div>
                       
		</div>
	</div>
	
</div>

<script> 
    function convertToRupiah (objek) { 
        separator = "."; 
        a = objek.value; 
        b = a.replace(/[^\d]/g,""); 
        c = ""; 
        panjang = b.length; 
        j = 0; for (i = panjang; i > 0; i--) { 
        j = j + 1; if (((j % 3) == 1) && (j != 1)) { 
        c = b.substr(i-1,1) + separator + c; } else { 
        c = b.substr(i-1,1) + c; } } objek.value = c; 
    } 

    function convertToRupiahhh(angka){
       var rupiah = '';
       var angkarev = angka.toString().split('').reverse().join('');
       for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
       return rupiah.split('',rupiah.length-1).reverse().join('');
    }	
    function rupiah(){
        var nominal= document.getElementById("hutanganda").value;
        var rupiah = convertToRupiahhh(nominal);
        document.getElementById("hutanganda").value = rupiah;
    }
 </script>
 