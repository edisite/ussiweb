<?php
class Kretrans_nasabah_model extends Grocery_crud_model
{
    
    function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "{$this->table_name}.*";
        $select .= ", NASABAH.NAMA_NASABAH,KODE_PERK_OB";
        //$select .= ", kredit.NO_REKENING_TABUNGAN,KODE_PERK_OB";
    	
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
        $this->db->from($this->table_name.',kredit,nasabah');
        $this->db->where($this->table_name.'.no_rekening=kredit.no_rekening');
        $this->db->where('kredit.NASABAH_ID=nasabah.NASABAH_ID');
        $this->db->where('(my_kode_trans','100');
        $this->db->or_where('my_kode_trans','300');
        $this->db->or_where('my_kode_trans','400');
        $this->db->or_where('my_kode_trans','500');
        $this->db->or_where('my_kode_trans=900)');
        $this->db->where('(kode_trans<>101');
        $this->db->where('kode_trans<>301');
        $this->db->where('kode_trans<>330)');
        $this->db->where('(upper(kredit.no_rekening) like "%%"');
        $this->db->or_where('upper(nama_nasabah) like "%%"');
        $this->db->or_where('NAMA_NASABAH IS NULL)');              
    	$results = $this->db->get()->result();
    	
    	return $results;
        //  select kretrans.*, kredit.nasabah_id, nasabah.nama_nasabah  from 
        //  (kretrans left join kredit on kretrans.no_rekening=kredit.no_rekening)  
        //  left join nasabah on kredit.nasabah_id=nasabah.nasabah_id  where 
        //  (tgl_trans>='2016-08-01' and tgl_trans<='2016-08-23' ) and  
        //  (my_kode_trans=100 or my_kode_trans=300 or my_kode_trans=400 or my_kode_trans=500 
        //  or my_kode_trans=900) and (kode_trans<>"101" AND kode_trans<>"301" and kode_trans<>"330" )  
        //  and (upper(kredit.no_rekening) like '%%' OR upper(nama_nasabah) like '%%' 
        //  OR NAMA_NASABAH IS NULL)    
    }
}