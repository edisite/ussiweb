<!--<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>-->
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<form action="<?php echo base_url().'admin/report/com/tgl'; ?>" method="get">
<div class="row">
    <div class="box box-primary">
        <div class="box-body">            
            <div class="col-md-6 col-sm-12 col-lg-3">
                <input type="text" class="form-control" name="datefilter" value="<?php echo $tgl; ?>" />   
                <input type="hidden" class="form-control" name="pathye" value="<?php echo $pathe; ?>" />
            </div>   
            <button type="submit" id="saveBtn" class="btn btn-primary"><i class="fa fa-search"> Search</i></button>
            <a href="<?php echo base_url().'admin/report/com/Report_agent_commerce_per_mount'; ?>" class="btn btn-danger pull-right"><i class="fa fa-reply"> Back </i></a>
        </div>
    </div>
</div>
</form>

<script type="text/javascript">
$(function() {

  $('input[name="datefilter"]').daterangepicker({
      autoUpdateInput: false,
      locale: {
          cancelLabel: 'Clear'
      }
  });

  $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' sd ' + picker.endDate.format('YYYY-MM-DD'));
  });

  $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });

});
</script>