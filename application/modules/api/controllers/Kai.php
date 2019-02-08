<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kai
 *
 * @author edisite
 */
class Kai extends API_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    function List_station_get() {
        //$resp = "err_code=00&org=AKB,AEKLOBA#AW,AWIPARI#AWN,ARJAWINANGUN#BAP,BANDARKALIPAH#BKS,BEKASI#BL,BLITAR#CI,CIAMIS#CKP,CIKAMPEK#CLD,CILEDUG#CLG,CILEGON#GMR,GAMBIR#JNG,JATINEGARA#KB,KOTABUMI#KNS,KRENGSENG#KOP,KOTAPADANG#PK,PEKALONGAN#SB,SURABAYAKOTA#SBI,SURABAYAPASARTURI#TPK,TANJUNGPRIUK#YK,YOGYAKARTA#";
        $resp = $this->Hitget(urlcekkereta);
        //$this->resjson($resp);
        //$res = $this->explodeX(array("&"), $resp);
//        list($error_code, $listdata) = $res;
//        $this->error_code   = $this->explodeX(array("="), $error_code);
//        list(,$status_error_code) = $this->error_code;       
//        $this->listdata   = $this->explodeX(array("="), $listdata);
//        list(,$codelistkereta) = $this->listdata;                
//        $this->res_codekereta = $this->list_arr(array("#"),$codelistkereta);
//        
//        $arr_combine =  array();
//        $arr_combine['errcode'] = $status_error_code; 
//        $arr_combine['org'] = $this->res_codekereta;         
//        $this->resjson($arr_combine);
        //print_r($res);
    }
    function explodeX($delimiters,$string)
    {
        $return_array = Array($string); // The array to return
        $d_count = 0;
        while (isset($delimiters[$d_count])) // Loop to loop through all delimiters
        { 
            $new_return_array = Array();
            foreach($return_array as $el_to_split) // Explode all returned elements by the next delimiter
            {
                $put_in_new_return_array = explode($delimiters[$d_count],$el_to_split);
                foreach($put_in_new_return_array  as $substr) // Put all the exploded elements in array to return
                {
                    $new_return_array[] = $substr;
                }
            }
            $return_array = $new_return_array; // Replace the previous return array by the next version
            $d_count++;
        }
        return $return_array; // Return the exploded elements
    }
    
    function list_arr($delimiters,$string) {
        $return_array = Array($string); // The array to return
        $d_count = 0;
        while (isset($delimiters[$d_count])) // Loop to loop through all delimiters
        { 
            $new_return_array = Array();
            foreach($return_array as $el_to_split) // Explode all returned elements by the next delimiter
            {
                $put_in_new_return_array = explode($delimiters[$d_count],$el_to_split);
                foreach($put_in_new_return_array  as $substr) // Put all the exploded elements in array to return
                {
                    if(empty($substr)){                        
                    }else{
                        $res_explode = $this->explodeX(array(","), $substr);
                        if($res_explode){
                            list($code,$name) = $res_explode;
                            $new_return_array[] = array('code' => $code,'nama' => $name);
                        }
                    }
                }
            }
            $return_array = $new_return_array; // Replace the previous return array by the next version
            $d_count++;
        }
        return $return_array; // Return the exploded elements
    }
    function resjson($data) {
        header('Content-Type: application/json');             
        echo json_encode($data);        
    }
    function Hitget($url)    {
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output=curl_exec($ch);
        curl_close($ch);
        echo $output;
    }
}
//=HYPERLINK("http://202.43.173.13/API_BMT_NEW/10_list_stasiun_kai.php","/API_BMT_NEW/10_list_stasiun_kai.php")
define('urlcekkereta','http://202.43.173.13/API_BMT_NEW/10_list_stasiun_kai.php');