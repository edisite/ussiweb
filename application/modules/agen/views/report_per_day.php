<?php
    $this->load->view('_layout/menu_head');
    ?>
        <div class="main">
      <div class="container">
        <ul class="breadcrumb">
            <li><a href="">Home</a></li>
            <li><a href="">Report</a></li>
            <li class="active">Transaksi Per Hari</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row">
          <!-- BEGIN SIDEBAR -->
          
          <div class="col-md-12 col-sm-12">
            <div style="clear:both"></div><br>
            <h3  class="box-title div_preheader2">Transaksi Per Hari</h3>
            <div class="goods-data">
                <table id="datatable" class="table table-hover table-bordered">
                  <thead>
                    <tr bgcolor="A9C5EB">
                      <th width ="2%" style="font-size: 10px">No</th>
                      <th width ="5%" style="font-size: 10px">Type</th>
                      <th width ="10%" style="font-size: 10px">Nomor ID</th>
                      <th width ="10%" style="font-size: 10px">Tanggal</th>                         
                      <th width ="5%" style="font-size: 10px">Produk</th>
                      <th width ="5%" style="font-size: 10px">Harga</th>
                      <th width ="15%" style="font-size: 10px">S/N</th>
                      <th width ="5%" style="font-size: 10px">Status</th>
                      <th width ="1%" style="font-size: 10px">PDF</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php
                      if($reportperday){
                          $no = 1;
                          foreach ($reportperday as $v) {
                                ?>
                                    <tr>
                                        <td style="font-size: 10px"><?php echo $no; ?>.</td>
                                        <td style="font-size: 10px"><?php echo $v->nominal; ?></td>
                                        <td style="font-size: 10px"><?php echo $v->msisdn; ?></td>
                                        <td style="font-size: 10px"><?php echo $v->dtm; ?></td>                                            
                                        <td style="font-size: 10px"><?php echo $v->product; ?></td>
                                        <td align="right"><?php echo number_format($v->price_selling,0,",","."); ?></td>
                                        <td style="font-size: 10px"><?php echo $v->res_sn; ?></td>
                                        
                                        <td style="font-size: 10px"><?php 

                                        if($v->res_code == 00){                                                
                                            echo "<label class='label label-success'>Succes</label>";
                                        }elseif($v->res_code == 68){
                                            echo "<label class='label label-warning'>Pending</label>";                                            
                                        }else{
                                            echo "<label class='label label-danger'>Lain-lain</label>";                                            
                                        }
                                        ?></td>
                                        <td><a href="" class="btn btn-link" role="button"><i class="glyphicon glyphicon-download"></i></a>                        
                        </td>
                                     </tr>
                                <?php
                                $no ++; 
                          }

                      }else{

                          ?>
                                       <tr>
                                           <td colspan="8" align="center" style="font-size: 10px">Tidak ada transaksi hari ini</td>
                                      </tr>  
                                     <?php
                      }
                      ?>


                  </tbody>
                </table>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>
    <?php
    $this->load->view('_layout/foot');        
?> 