<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Transfer_agen_model
 *
 * @author edisite
 */
class Transfer_agen_model extends Grocery_crud_model{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "{$this->table_name}.*";
        $select .= ",transfer_daftar_bank.nama_bank  ,";
    	
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
        $this->db->from($this->table_name.',transfer_daftar_bank');
        $this->db->where('transfer_daftar_bank.kode_bank='.$this->table_name.'.kode_transfer');  
        //$this->db->where('transfer_daftar_bank.status','1');
    	$results = $this->db->get()->result();
    	
    	return $results;
    }
}
