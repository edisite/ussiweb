<?php
    $this->load->view('report/tgl_view');
?>
<?php if ( !empty($crud_note) ) echo "<p>//$crud_note</p>"; ?>

<?php if ( !empty($crud_output) ) echo $crud_output; ?>