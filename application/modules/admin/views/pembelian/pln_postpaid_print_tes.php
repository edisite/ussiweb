<?php
 // Download printer driver for dot matrix printer ex. 1979 Dot Matrix      Regular or Consola

?>
<html>
<head>
<title>PHP to Dot Matrix Printer</title>

<style>
@font-face { font-family: kitfont; src: url('1979 Dot Matrix Regular.TTF'); } 

.customFont { /*  <div class="customFont" /> */
font-style: kitfont;
font-size:10;
}
#mainDiv {
height: 324px; /* height of receipt 4.5 inches*/
width: 618px;  /* weight of receipt 8.6 inches*/
position:relative; /* positioned relative to its normal position */
}
#cqm { /*  <img id="cqm" /> */
top: 10px; /* top is distance from top (x axis)*/
left: 105px; /* left is distance from left (y axis)*/
position:absolute; /* position absolute based on "top" and "left"    parameters x and y  */
}

#or_mto { 
position: absolute;
left: 0px;
top: 0px;
z-index: -1; /*image */
}

    #arpno {
top: 80px;
left: 10px;
position:absolute;
}
#payee {
top: 80px;
left: 200px;
position:absolute;
}
#credit {
top: 80px;
right: 30px; /*   distance from right */
position:absolute;
}
#paydate {
top: 57px;
right: 120px;
position:absolute;
}
 </style>

</head>
<body>
<?php
//sample data

$arpno   = 1234567;
$payee   = "Juan dela Cruz";
$credit  = 10000;
$paydate = "Dec. 6, 2015" ;


?>
<div id="mainDiv"> <!--  invisible space -->
<div id="cqm" class="customFont">ABC TRADING</div>
<div id="arpno" class="customFont"><?php echo $arpno; ?></div>
<div id="payee" class="customFont"><?php echo $payee; ?></div>
<div id="credit" class="customFont"><?php echo $credit; ?></div>
<div id="paydate" class="customFont"><?php echo $paydate; ?></div>
<img id="or_mto" src="<?php echo base_url(); ?>assets/images/logo_bmt.jpg" /> <!---- sample for logo  ---->
</div>
<div id="mainDiv"> <!--  invisible space -->
<div id="cqm" class="customFont">ABC TRADING</div>
<div id="arpno" class="customFont"><?php echo $arpno; ?></div>
<div id="payee" class="customFont"><?php echo $payee; ?></div>
<div id="credit" class="customFont"><?php echo $credit; ?></div>
<div id="paydate" class="customFont"><?php echo $paydate; ?></div>
<img id="or_mto" src="<?php echo base_url(); ?>assets/images/logo_bmt.jpg" /> <!---- sample for logo  ---->
</div>