<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style>
table {
    border-collapse: collapse;
	padding:10px;
}

th, td {

	padding: 4px;
}

td{
	font-size:12px;
}
.white {
   color: white;
   }

</style>
<!-- PRINT OUT SET MARGIN "TOP:26mm","BOTTOM,LEFT,RIGHT:0mm"-->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BMT EL-SEJAHTERA</title>
</head>
<center>
<!--<h2><b><i>Unit Jasa Keuangan Syariah</i></b></h2>-->
<table width="532.9px" border="0px">
<!-- <tr>
    <th style="width:71,8px;" rowspan="2" >Tanggal</th>
    <th style="width:49,1px;"rowspan="2">Kode</th>
    <th style="width:204,08px;" colspan="2">MUTASI</th>
    <th style="width:120,38px;" rowspan="2">Saldo</th>
    <th style="width:90.7px; font-size:x-small;" rowspan="2" >Pengesahan</th>
  </tr>
   <tr>
    <th>Masuk (D)</th>
    <th>Keluar (K)</th>
  </tr>-->
<?php  
$no = 0;
//print_r($tab);
foreach($tab as $res){
    $no = $no + 1;
    
    ?>
      <tr>
<!--         <td style="width:71.8px;" align="left"><?php  echo $no;?></td>-->
          <td style="width:71.8px;"><?php  echo $res->Tanggal;?></td>
        <td style="width:49.1px;" ><?php  echo $res->Sandi;?></td>
        <td style="width:102.4px;" align="right" ><?php  echo number_format($res->Debet,2,",",".");?></td>
        <td style="width:102.04px;" align="right"><?php  echo number_format($res->Kredit    ,2,",",".");?></td>
        <td style="width:113.38px;" align="right"><?php  echo number_format($res->SALDO,2,",",".");?></td>
        <td style="width:90.7px;" ><?php  echo "";?></td>
      </tr>
    <?php
  $no++;
} 
?>
</table>
</center>
<body>
</body>
</html>
