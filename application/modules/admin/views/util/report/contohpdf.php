<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  
  <!-- Bootstrap 3.3.6 -->
  <!--<link rel="stylesheet" href="http://localhost/ci/ussiweb/assets/report/bootstrap/css/bootstrap.min.css">-->
  <!-- Font Awesome -->
  <!-- <link rel="stylesheet" href="../../bootstrap/css/font-awesome.min.css"> -->
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="../../bootstrap/css/ionicons.min.css"> -->
  <!-- DataTables -->
  <!-- <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="http://localhost/ci/ussiweb/assets/report/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <!-- <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css"> -->
</head>
<body>

             <div class="box-header">
			  <h5>
				<b>BAITUL MAAL WAT-TAMWIL<br>
				BMT EL-SEJAHTERA<br>
				KANTOR PUSAT<br>
				Jl.Jendral Ahmad Yani No. 35 Cipari</b>
			  </h5>
			</div>
			<hr size="10px" color="black">
            <div class="box-header">
              <center>
				  <div style="font-weight: bold;">LAPORAN TELLER : USSI</div>
				  <div style="font-weight: bold;">Transaksi Simpanan <br> </div>
				  <div>Periode 01 Juli 2016 s/d 31 Juli 2016</div>
			  </center>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
                
              <table class="table table-bordered table-hover" width="1000px">
                <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Uraian</th>
                  <th>No Bukti</th>
                  <th>Penerimaan</th>
                  <th>Pengeluaran</th>
                </tr>
                <?php
                                        $no = 1;
                                        foreach ($laporan as $sublap) {                                        
                                        
                                            ?>  <tr>
                                            <td><?php echo $no; ?></td>
                                            <td><?php echo $sublap->tgl_trans; ?></td>
                                            <td><?php echo $sublap->keterangan; ?></td>
                                            <td><?php echo $sublap->kuitansi; ?></td>
                                            <td align='right'><?php echo $sublap->setoran; ?></td>
                                            <td align='right'><?php echo $sublap->penarikan; ?></td>
                                            </tr>
                                        <?php
                                        $no = $no + 1;
                                        }
                                        ?>
                </thead>
                
              </table>
            </div>
            <!-- /.box-body -->
          
</body>
</html>
