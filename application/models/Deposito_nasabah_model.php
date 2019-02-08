<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Deposito_nasabah_model
 *
 * @author edisite
 */
class Deposito_nasabah_model extends Grocery_crud_model 
{
    //put your code here
    function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "{$this->table_name}.*";
        $select .= ",  deposito.no_rekening, nasabah.NAMA_NASABAH, nasabah.ALAMAT,";
    	
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
    	$results = $this->db->get($this->table_name)->result();
    	
    	return $results;
    }
}