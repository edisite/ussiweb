<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">	
	<base href="http://localhost/ci/ussiweb/admin/" />

	<title>[8403]Lap. Transaksi Teller</title>

	<meta name='author' content='Edi Supriyanto'>
<meta name='description' content='Application Banking'>
<meta name='description2' content='BMT MOBILE'>
<link href='http://localhost/ci/ussiweb/vendors/bootstrap/dist/css/bootstrap.min.css' rel='stylesheet' media='mpdf'>


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
<div class="box">
	<div class="box-header">
            <h2 class="box-title">LAPORAN</h2>
               
	</div>
        
	<div class="box-body">
		<?php /* List out stored versions */ ?>
		<table class="table table-striped table-bordered">
			<tbody>
				<tr>
					<th>No.</th>
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
                
			</tbody>
		</table>
	</div>
</div>