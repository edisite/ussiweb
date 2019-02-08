<?php echo validation_errors(); ?>
<script>

    $(document).ready(function () {
        var availableTags = ["Kucing", "Anjing"];
        var result = [];
        var codeTransfer;
        $("#target").click(function () {
            var rekPengirim = $("#value-rek-pengirim").val();
            //alert($("#value-rek-pengirim").val());
            var rekPenerima = $("#value-rek-penerima").val()
            var nominalTransfer = $("#nominal-transfer").val();

            if (rekPengirim == "" || rekPenerima == "" || nominalTransfer == "")
            {
                    alert("Masih ada Field Yang Kosong,\nMohon Dilengkapi ");
            } else
            {

                console.log(rekPenerima + "|" + rekPengirim + "|" + nominalTransfer);
                $.post(
                        "<?php echo base_url(); ?>admin/trf/AntarBank/checkRekening",
                        {rekPengirim: rekPengirim, rekPenerima: rekPenerima, nominalTransfer: nominalTransfer, kodeTransfer: 100},
                function (data) {
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
                            "Continue Transfer ? ": function () {
                                $.ajax({
                                    type: "POST",
                                    url: "<?php echo base_url(); ?>admin/trf/antarbank/Sent_trf",
                                    data: {code: codeTransfer},
                                    dataType: "text",
                                    timeout: 500000, // in milliseconds
                                    success: function (data) {
                                        // process data here
                                        console.log(data);
                                        alert("Transfer Complete");

                                    },
                                    error: function (request, status, err) {
                                        if (status == "timeout") {
                                            console.log(err + "|" + status);
                                            alert(err);
                                        }
                                    }
                                });

                            },
                            Cancel: function () {
                                $(this).dialog("close");
                            }
                        }
                    });


                }
                );
            }
        });
      
        $("#rek-pengirim").autocomplete({
            source: '<?php echo base_url(); ?>admin/trf/antarBank/GetRekeningByNameRek',
            minLength: 1,
            select: function (event, ui) {
                event.preventDefault();
                $("#rek-pengirim").val(ui.item.label);
                $("#value-rek-pengirim").val(ui.item.value);
            },
            focus: function (event, ui) {
                event.preventDefault();
                $("#rek-pengirim").val(ui.item.value);
            }
        });


        $("#rek-penerima").autocomplete({
            source: '<?php echo base_url(); ?>admin/trf/antarBank/GetRekeningByNameRek',
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
                    <h3 class="box-title">Transfer Antar Rekening</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">

                    <div class="col-xs-5" >
                        <form>
                            <label>NAMA/NO.REKENING PENGIRIM </label>
                            <div class="ui-widget">
                                <input id="rek-pengirim" type="text" class="form-control input-sm"> 
                                <input type="hidden" id="value-rek-pengirim" name="user" value="" />
                            </div>
                            <label>NAMA/NO.REKENING PENERIMA</label>
                            <input id="rek-penerima"  type="text" class="form-control input-sm">
                            <input type="hidden" id="value-rek-penerima" name="user" value="" />
                            <label>NOMINAL TRANSFER</label>
                            <input id="nominal-transfer" type="text" class="form-control input-sm">
                            <div class="box-footer">
                                <input id="target" type="button" class="btn btn-primary" value="Submit">
                            </div>
                        </form>
                    </div>
                        <!-- /.box -->
                    </div>
                    <!-- /.col -->
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
                        </p>
                    </div>