<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Dblib
 *
 * @author edisite
 */
class Dblib {
    //put your code here
    private $CI, $Data, $mysqli, $ResultSet;
    function __construct()
    {
            $this->CI =& get_instance();
            $this->Data = '';
            $this->ResultSet = array();
            $this->mysqli = $this->CI->db->conn_id;
    }
    public function GetMultiResults($SqlCommand)
    {
            /* execute multi query */
            if (mysqli_multi_query($this->mysqli, $SqlCommand)) {
                $i=0;
                do
                {

                     if ($result = $this->mysqli->store_result()) 
                     {
                        while ($row = $result->fetch_assoc())
                        {
                            $this->Data[$i][] = $row;
                        }
                        mysqli_free_result($result);
                     }
                    $i++; 
                }
                while ($this->mysqli->next_result());
            }
            return $this->Data;

    }   
}
