<div class="row">
<div class="col-md-6">
<div class="box box-primary">
	<div class="box-header with-border">
          <h3 class="box-title">Jenis Transaksi</h3>
        </div>
        
	<div class="box-body">
            <div class="col-md-11">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>An <?php echo strtoupper($pengguna);?> - Per tanggal <?php echo $tgl; ?></th>
                        </tr>
                    </tbody>
                </table>
		<?php /* Backup button */ ?>
		<p>
			<a href="report/saldo_kas/cek" class="btn btn-primary">Kembali</a>
			
		</p>

		<?php /* List out stored versions */ ?>
		<table class="table table-bordered">
			<tbody>
				<tr>
					<th>Deskripsi</th>
					<th>Nominal</th>
				</tr>
				<tr>
					<td>Droping Kas</td>
					<td><?php echo $dropkas; ?></td>
				</tr>
                                <tr>
					<td>Penerimaan</td>
					<td><?php echo $terima; ?></td>
				</tr>
                                <tr>
					<td>Pengeluaran</td>
					<td><?php echo $keluar; ?></td>
				</tr>
                                <tr>
					<td>Saldo Akhir</td>
					<td><?php echo $saldo; ?></td>
				</tr>
			</tbody>
		</table>
                </div>
	</div>
</div>
</div>  
</div>