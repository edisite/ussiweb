<?php echo validation_errors(); ?>
<script>

    $(document).ready(function () {
        
        var result = [];
        var codeTransfer;
        var listBank=[];
        $("#target").click(function () {
            var rekPenerima = $("#value-rek-penerima").val();
            //alert($("#value-rek-pengirim").val());
            var namaPengirim = $("#nama-pengirim").val()
            var rekPengirim =$("#rek-pengirim").val()
            var nominalTransfer = $("#nominal-transfer").val();
            var kode_bank = $("#kode_bank").val();
            var alamatPengirim =$("#alamat-pengirim").val();
            
              if (rekPenerima == "" || nominalTransfer == "" || alamatPengirim == "" || rekPenerima == "" || namaPengirim == "" ) {
                alert("Masih ada Field Yang Kosong,\nMohon Dilengkapi ");
            }else{
            console.log(rekPenerima + "|" + rekPengirim + "|" + nominalTransfer+"|"+kode_bank);
            $.ajax({
                type: "POST",
                //http://localhost/ussiweb_old/admin/trf/AntarBank/checkRekening
                url: "<?php echo base_url(); ?>admin/trf/AntarBank/checkRekening",
                data: {rekPengirim: rekPengirim, 
                    rekPenerima : rekPenerima, 
                    nominalTransfer : nominalTransfer ,
                    nama_nasabah_sender:namaPengirim,
                    alamat_sender : alamatPengirim,
                    kode_bank_sender : kode_bank,
                    kodeTransfer : 102,},
                dataType: "text",
                timeout: 5000, // in milliseconds
                success: function (data) {
                    var response = $.parseJSON(data);
                    console.log(data);
                    codeTransfer = response["code"];
                    console.log(codeTransfer);

                    console.log(response["sender_nama_nasabah"] + ", " + response["nominal"]);
                    $("#sender-name").text(response["sender_nama_nasabah"]);
                    $("#sender-rek").text(response["sender_no_rekening"]);
                    $("#receiver-rek").text(response["receiver_no_rekening"]);
                    $("#receiver-name").text(response["receiver_nama_nasabah"]);

                    $("#nominal").text(response["nominal"]);
                    $("#biaya-adm").text(response["adm"]);
                    $("#total").text(response["total"]);

                    $("#dialog-confirm").dialog({
                        resizable: false,
                        height: "auto",
                        width: 400,
                        modal: true,
                        buttons: {
                            OK: function () {
                                $(this).dialog("close");
                            }
                        }
                    });

                },
                error: function (request, status, err) {
                    if (status == "timeout") {
                        console.log(err + "|" + status);
                        alert(err);
                    }
                }
            });
        }
        });
        // $.post(
        //     //http://localhost/ussiweb_old/admin/trf/AntarBank/getRekeningByNameRek
        //         "http://localhost/ussiweb_old/admin/trf/AntarBank/getRekeningByNameRek",
        //         {keyword: 'put'},
        // function (data) {
        //     var response = $.parseJSON(data);

        //     for (var key in response) {
        //         if (response.hasOwnProperty(key)) {
        //             console.log(response[key]["nama"] + ", " + response[key]["rekening"]);
        //             result.push({label: response[key]["nama"] + ", " + response[key]["rekening"], value: response[key]["rekening"]});


        //         }
        //     }

        //     console.log(result);

        // }
        // );

 
        $("#rek-penerima").autocomplete({
            source: '<?php echo base_url(); ?>admin/trf/AntarBank/getRekeningByNameRek',
            minLength: 1,
            select: function (event, ui) {
                event.preventDefault();
                $("#rek-penerima").val(ui.item.label);
                $("#value-rek-penerima").val(ui.item.value);
            },
            focus: function (event, ui) {
                event.preventDefault();
                $("#rek-penerima").val(ui.item.value);
            }
        });



    });


</script>
<div class="row">
    <!-- left column -->
    <div class="col-md-8">
        <form role="form" data-parsley-validate>
            <!-- general form elements -->

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Transfer Antar Bank BMT Ke Bank Lain</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <div class="col-xs-5" >
                        <label>NAMA REKENING PENGIRIM</label>
                        <input id="nama-pengirim" type="text" class="form-control input-sm">
                         <label>NO.REKENING PENGIRIM</label>
                        <input id="rek-pengirim" type="text" class="form-control input-sm"/>
                         <label>ALAMAT REKENING PENGIRIM</label>
                        <textarea id="alamat-pengirim" type="textArea" class="form-control input-sm"></textarea>
                        <label>Bank Tujuan</label>
                        <select id="kode_bank" class="form-control input-sm">
                            <?php 
                                foreach($listBank as $object)
                                {
                                    ?><option value="<?php echo $object->kode_bank ?>"><?php echo $object->nama_bank; ?></option>
                                    <?php 
                                }
                            ?>
                        </select>
                         <label>NAMA PENERIMA</label>
                        <input id="rek-penerima" type="text" class="form-control input-sm"/>
                          <input type="hidden" id="value-rek-penerima" value="" />
                       
                       
                        <label>NOMINAL TRANSFER</label>
                        <input id="nominal-transfer" type="text" class="form-control input-sm"/>
                    </div>
                </div>
            </div>
            <!-- /.input group -->
    </div>
</div>
<div class="box-footer">
    <input id="target" type="button" class="btn btn-primary" value="Submit"/>
</div>
<!-- /.box -->
</div>
<!-- /.col -->
</div>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div id="dialog-confirm" title="Continue Transfer?">

    Rekening Pengirim :<span id="sender-rek"></span><br>
    Nama Pengirim 	:<span id="sender-name"></span>
    <br>
    <br>
    Rekening Tujuan : <span id="receiver-rek"></span><br>
    Nama Tujuan 	  : <span id="receiver-name"></span>
    <br>
    <br>
    Nominal 	: <span id="nominal"></span><br>
    Biaya Adm 	: <span id="biaya-adm"></span><br>
    Total 		: <span id="total"></span><br>

    <br>
    <br>
    <br>
    Transfer Anda Sedang Di Proses
    </p>
</div>