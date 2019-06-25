<?php
class RecycleTrans_model extends Grocery_crud_model
{
    
    function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "a.*,a.atdtm as waktu, b.*";
        //$select .= ", recycle_bin_transaksi_detail.* ";
    	
		// ADD YOUR SELECT FROM JOIN HERE <------------------------------------------------------
		// for example $select .= ", user_log.created_date, user_log.update_date";
		
    	if(!empty($this->relation))
    		foreach($this->relation as $relation)
    		{
    			list($field_name , $related_table , $related_field_title) = $relation;
    			$unique_join_name = $this->_unique_join_name($field_name);
    			$unique_field_name = $this->_unique_field_name($field_name);
    			
				if(strstr($related_field_title,'{'))
    				$select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE({$unique_join_name}.",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $unique_field_name";
    			else    			
    				$select .= ", $unique_join_name.$related_field_title as $unique_field_name";
    			
    			if($this->field_exists($related_field_title))
    				$select .= ", {$this->table_name}.$related_field_title as '{$this->table_name}.$related_field_title'";
    		}
    		
    	$this->db->select($select, false);
        $this->db->from($this->table_name.' a, recycle_bin_transaksi_detail b');
        $this->db->where('b.MASTER_ID=a.TRANS_ID');           
    	$results = $this->db->get()->result();
    	
    	return $results;
        
        //select tabtrans.*, nasabah.nama_nasabah, tabung.kode_integrasi, tabung.kode_produk, 
    //tabung.kode_kantor  from (tabtrans left join tabung on tabtrans.no_rekening=tabung.no_rekening)  
    //left join nasabah on tabung.nasabah_id=nasabah.nasabah_id where (tgl_trans>='2016-08-01' 
    //and tgl_trans<='2016-08-19' ) and my_kode_trans<>300 and my_kode_trans<>400 
    //and (upper(tabung.no_rekening) like '%%' OR upper(nama_nasabah) like '%%' 
    //OR NAMA_NASABAH IS NULL)
    
    }
}