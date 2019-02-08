<div class="row" id="dashboardProduksi">
</div>
<div class="row">
</div>

<script type="text/javascript">
      $(function(){
          var start   = "<?php echo $this->input->post("start")?>";
          var end     = "<?php echo $this->input->post("end")?>";
          var label   = "<?php echo $this->input->post("label")?>";
          // alert(start);
          window.dashboardProduksi(start, end, label);
      });
</script>