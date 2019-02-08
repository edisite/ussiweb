<?php
    $this->load->view('_layout/menu_head');
    ?>
    <div class="main">
      <div class="container">
        <ul class="breadcrumb">
            <li><a href="">Home</a></li>
            <li><a href="">Report</a></li>
            <li class="active">Tagihan</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <h1>Transaksi Per Hari</h1>
        <div class="goods-page">
             <div class="goods-data clearfix">
                    <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="transaksi_proses" method="post">

                        <div class="form-group">
                        <label class="control-label col-md-2 col-sm-3 col-xs-12">Dari Tanggal</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <input id="tgl_fr" name="tgl_fr" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>">
                        </div> 

                       <label class="control-label col-md-2 col-sm-3 col-xs-12">Sampai Tanggal</label>
                        <div class="col-md-3 col-sm-3 col-xs-12">
                            <input id="tgl_to" name="tgl_to" class="date-picker form-control col-md-7 col-xs-12" required="required" type="text" value="<?php echo date('m/d/Y', strtotime(date('Y-m-d'))); ?>">
                        </div>

                       <div class="col-md-1 col-sm-3 col-xs-12">
                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                      </div>
                    </div>                
                    </form>

                <div class="content-page">                    
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p class="lead">Mutasi Transaksi </p>     
                            <?php if($this->session->userdata('errormsg')) : echo $this->session->userdata('errormsg'); endif;?>           
                        <?php
                            if($tgl): echo $tgl;
                            endif;
                        ?> 
                        <p class="lead"><b>Transaksi Penjualan</b></p>
                        <table width="100%" height="100%">
                          <tr>
                          <th style="width:1%">No</th>
                          <th style="width:20%">Tanggal</th>
                          <th style="width:5%">Kode</th>
                          <th style="width:10%">Tipe</th>
                          <th style="width:10%">Produk</th>
                          <th style="width:10%">Harga BMT</th>
                          <th style="width:10%">Harga JUAL</th>
                          <th style="width:10%">Basil</th>

                        </tr>

                        <?php

                            if($data): echo $data;
                            else:
                                echo "<tr><td colspan='9' align='center'>Record is empty</td></tr>";
                            endif;
                        ?>
                      </table>
                        <br>
                        <p class="lead"><b>Transaksi Payment / Pembayaran</b></p>
                        <table>
                          <tr>
                          <th style="width:1%">No</th>
                          <th style="width:10%">Tanggal</th>
                          <th style="width:5%">Kode</th>
                          <th style="width:10%">Tipe</th>
                          <th style="width:10%">Produk</th>
                          <th style="width:10%">NomorID</th>
                          <th style="width:10%">Tagihan</th>
                          <th style="width:10%">Admin</th>
                          <th style="width:10%">Basil Agent</th>

                        </tr>

                        <?php
                            if(empty($datap)): 
                                echo "<tr><td colspan='8' align='center'>Record is empty</td></tr>";
                            else:                                
                                echo $datap;
                            endif;
                        ?>
                      </table>
                    </div>
                  </div>
                <hr>
                <div class="content-page">
                      <div class="col-md-5 col-sm-12 col-xs-12">
                          <small class="note">Note *<br>Kode Trans 101 : Commerce via Cash <br>Kode Trans 102 : Commerce via Debet Tabungan</small>
                      </div>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <hr>
                        <p class="lead">Akutansi Keuntungan Agent</p>
                          <div class="table">
                              <table class="table-wrapper-responsive">                        
                        <?php
                            if($data_profit): echo $data_profit;
                            endif;
                        ?>
                              </table>
                          </div>
                    </div> 
              </div>  
            </div>
        </div>
        </div>
        </div>
        </div>
        </div>

    <?php
    $this->load->view('_layout/foot'); 
    
?> 
    


    
   
    