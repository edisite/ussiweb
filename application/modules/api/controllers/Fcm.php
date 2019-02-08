<?php
//angga mode
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fcm
 *
 * @author edisite
 */
class Fcm extends API_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
        $this->load->model('DbOperation');
    }
    public function Registerdevice_post() {
        $this->token = $this->input->post('token') ?: '';
        $this->email = $this->input->post('email') ?: '';
        $response = array();  
        if(empty($this->token)){
            $response['error'] = true;
            $response['message']='Invalid Request...';
            $this->response($response);
        }
        if(empty($this->email)){
            $response['error'] = true;
            $response['message']='Invalid Request...';
            $this->response($response);
        }
              
        $res_register = $this->DbOperation->RegisterDevice($this->email,$this->token);
            if($res_register == 0){
                $response['error'] = false; 
                $response['message'] = 'Device registered successfully';
            }elseif($res_register == 1){
                $response['error'] = true; 
		$response['message'] = 'Device not registered';
            }elseif($res_register == 2){
                $response['error'] = true; 
		$response['message'] = 'Device already registered';
            }else{
                $response['error'] = true;
		$response['message']='Invalid Request...';
            }
        $this->response($response);
    }
    public function Removedevice_post() {
        $this->email = $this->input->post('email') ?: '';
        $response = array();
        $res_remove = $this->DbOperation->RemoveDevice($this->email);
        if($res_remove == 0){
            $response['error'] = false; 
            $response['message'] = 'Device remove successfully';
        }elseif($res_remove == 1){
            $response['error'] = true;
            $response['message']='Device not registered';
        }elseif($res_remove == 2){
            $response['error'] = true; 
            $response['message'] = 'Device doesnt exist';
        }else{
            $response['error'] = true;
            $response['message']='Invalid Request...';
        }
        $this->response($response);
    }
    public function SendSinglePush_post() {
       
        $trace_id = $this->input->post('tid') ?: $this->Logid();
        $this->logAction('fcm', $trace_id, array(), 'begin');
        $this->title    = $this->input->post('title') ?: '';
        $this->message  = $this->input->post('message') ?: '';
        $this->email    = $this->input->post('email') ?: '';
        $this->image    = $this->input->post('image') ?: NULL;
        $response = array();
        if(empty($this->title) || empty($this->message) || empty($this->email)){
            $response['error']=true;
            $response['message']='Parameters missing';            
            $this->response($response);
        }
        //setting parameter push
        
        $res_email = $this->DbOperation->Check_devices($this->email);
        if($res_email){
            foreach ($res_email as $v) {
                $this->token = $v->token;
            }
        }else{
            	$response['error']=true;
                $response['message']='Invalid request';
                $this->response($response);
        }
        if(empty($this->token)){
            $response['error']=true;
            $response['message']='Invalid request';
            $this->response($response);
        }

        $res_push = $this->push($this->title,$this->message,$this->image);
        echo $this->send($this->token,$res_push); 
    }
    function SendSinglePush_priv($trace_id,$mail,$title,$message,$image = NULL) {
       
        $trace_id =  $trace_id ?: $this->Logid();
        $this->logAction('fcm', $trace_id, array(), 'begin');
        $this->logAction('fcm', $trace_id, array(), $trace_id);
        $this->logAction('fcm', $trace_id, array(), $mail);
        $this->logAction('fcm', $trace_id, array(), $title);
        $this->logAction('fcm', $trace_id, array(), $message);
        $this->title    = $title ?: '';
        $this->message  = $message ?: '';
        $this->email    = $mail ?: '';
        $this->image    = $image ?: NULL;
        
        $response = array();
        if(empty($this->title) || empty($this->message) || empty($this->email)){
            $response['error']=true;
            $response['message']='Parameters missing';    
            $this->logAction('fcm', $trace_id, $response, 'response');
            return $response;
        }
        //setting parameter push
        
        $res_email = $this->DbOperation->Check_devices($this->email);
        if($res_email){
            foreach ($res_email as $v) {
                $this->token = $v->token;
            }
        }else{
            	$response['error']=true;
                $response['message']='Invalid request';
                $this->logAction('fcm', $trace_id, $response, 'response');
                return $response;
        }
        if(empty($this->token)){
            $response['error']=true;
            $response['message']='Invalid request';
            $this->logAction('fcm', $trace_id, $response, 'response');
            return $response;
        }

        $res_push = $this->push($this->title,$this->message,$this->image);
        $this->logAction('fcm', $trace_id,$res_push , 'response');
        return $this->send($this->token,$res_push); 
    }
    function Push($title = '',$message = '',$image = '') {
        $this->title    = $title;
        $this->message  = $message;
        $this->image    = $image;
        $res = array();
        $res['data']['title']   = $this->title;
        $res['data']['message'] = $this->message;
        $res['data']['image']   = $this->image;
        return $res;
    }
    function send($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
    private function sendPushNotification($fields) {
        //firebase
        //importing the constant files
        //require_once 'Config.php';
        ignore_user_abort();
        ob_start();

        //firebase server url to send the curl request
        $url = 'https://fcm.googleapis.com/fcm/send';
 
        //building headers for the request
        $headers = array(
            'Authorization: key=' . FIREBASE_API_KEY,
            'Content-Type: application/json'
        );

        //Initializing curl to open a connection
        $ch = curl_init();
 
        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);
        
        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        //adding the fields in json format 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        //finally executing the curl request 
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
 
        //Now close the connection
        curl_close($ch);
 
        //and return the result 
        return $result;
        ob_flush();
    }
}

define('FIREBASE_API_KEY', 'AAAATA7wEZM:APA91bHOSu_OU5u07Vj5-zpL2m4Wm1LjjykuMwH_ACCo6j3NbHRVTO-tHCqfxB6PJAIT3i0bNceo8k8tEcdW0PIy7bjAF9ieEr6UR-Zhc9detAXwnBR559zk-nMvgrH5TkOHt2I-xDYc');
