<div class="box">
	<div class="box-header">
            <h2 class="box-title">Mutasi Kas Teller</b></h2>
               
	</div>
        
	<div class="box-body">
            <div class="col-md-6">
                <table class="table table-striped  table-bordered">
                    <tbody>
                        <tr>
                            <th>An <?php echo strtoupper($pengguna);?> - Per tanggal <?php echo $tgl; ?></th>
                        </tr>
                    </tbody>
                </table>
		<?php /* Backup button */ ?>
		<p>
			<a href="report/mutasi_keuangan_bmt/preview" class="btn btn-primary">Kembali</a>
			
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