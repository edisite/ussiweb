<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Com_logtransaction
 *
 * @author edisite
 */
class Com_report_commerce extends Grocery_crud_model 
{
    //put your code here
    function get_list()
    {
    	if($this->table_name === null)
    		return false;
    	
    	$select = "*";
        
    	
	if(!empty($this->relation))
        {
            foreach($this->relation as $relation)
            {
                list($field_name , $related_table , $related_field_title) = $relation;
                $unique_join_name = $this->_unique_join_name($field_name);
                $unique_field_name = $this->_unique_field_name($field_name);

                if(strstr($related_field_title,'{'))
                {
                    $related_field_title = str_replace(" ","&nbsp;",$related_field_title);
                    $select .= ", CONCAT('".str_replace(array('{','}'),array("',COALESCE({$unique_join_name}.",", ''),'"),str_replace("'","\\'",$related_field_title))."') as $unique_field_name";
                }
                else
                {
                    $select .= ", $unique_join_name.$related_field_title AS $unique_field_name";
                }

                if($this->field_exists($related_field_title))
                    $select .= ", `{$this->table_name}`.$related_field_title AS '{$this->table_name}.$related_field_title'";
            }
        }
    		
    	//set_relation_n_n special queries. We prefer sub queries from a simple join for the relation_n_n as it is faster and more stable on big tables.
        if(!empty($this->relation_n_n))
        {
            $select = $this->relation_n_n_queries($select);
        }

        $this->db->select($select, false);
        $this->db->from($this->table_name);

        //if there is data for a join, add a join clause
        if ((!is_null($this->join_tbl)) && (!is_null($this->join_str)))
        {
            $this->db->distinct();
            $this->db->join($this->join_tbl,$this->join_str,'left outer');
        }

        //if there is data for a where, add a where clause
        if (!is_null($this->where_array))
        {
            $this->db->where($this->where_array);
        }



        $results = $this->db->get()->result();
        return $results;     	
    }
    public function set_join_str($join_tbl, $join_str) {

        $this->join_tbl = $join_tbl;
        $this->join_str = $join_str;
    }

    public function set_where_str($array) {

        $this->where_array = $array;
    }
}