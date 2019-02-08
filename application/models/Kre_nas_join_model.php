<?php
class Kre_nas_join_model extends Grocery_crud_model
{
   function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "{$this->table_name}.*";
        $select .= ", nasabah.NAMA_NASABAH,nasabah.ALAMAT,";
    	
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
    	
        // ADD YOUR JOIN HERE for example: <------------------------------------------------------
        // $this->db->join('user_log','user_log.user_id = users.id');
        $this->db->join('nasabah','nasabah.NASABAH_ID = '.$this->table_name.'.NASABAH_ID');
//        $this->db->where($this->table_name.'.verifikasi','1');
//        $this->db->where('('.$this->table_name.'.status','0');
//        $this->db->or_where($this->table_name.'.status is null');
//        $this->db->or_where('(('.$this->table_name.'.type_kredit','500');
//        $this->db->or_where($this->table_name.'.type_kredit','510)');
//        $this->db->where($this->table_name.'.pokok_saldo_akhir < '.$this->table_name.'.jml_pinjaman');
//        $this->db->where($this->table_name.'.tgl_jatuh_tempo >= '.date('Y-m-d').')))');
    	$results = $this->db->get($this->table_name)->result();    	
    	return $results;
    }
}