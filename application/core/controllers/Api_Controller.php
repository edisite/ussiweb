<?php
define('APISESSION', true);
define('APIINTERVAL', 1440);//20170711 req pak sap 30menit.dirubah lagi ke 1jam .20171123back 5menit -> 24 jam
require_once(APPPATH.'third_party/rest_server/libraries/REST_Controller.php');

/**
 * Base Controller for API module
 */
class API_Controller extends REST_Controller {

	// API Key object to represent identity consuming the API endpoint
	protected $mApiKey = NULL;
        public $model_com      = "COM";
        public $model_pay      = "PAY";

	// Constructor
	public function __construct()	{
		parent::__construct();	
     
		// send PHP headers when necessary (e.g. enable CORS)
		$config = $this->config->item('ci_bootstrap');
		$headers = empty($config['headers']) ? array() : $config['headers'];
		foreach ($headers as $header)
		{
			header($header);
		}            
                
                
		$this->verify_token();
                $this->kunci_interval();  
                date_default_timezone_set('Asia/Jakarta');
	}
        protected function kunci_interval() {
                    if((APISESSION == true)){                                     
                    if($this->uri->uri_string() == 'api/auth/cek' 
                            || $this->uri->uri_string() == 'api/auth/last_status'
                            || $this->uri->uri_string() == 'api/auth/login_nasabah')
                    {}
                    elseif($this->uri->uri_string() == 'api/fcm/removedevice' 
                            || $this->uri->uri_string() == 'api/fcm/sendsinglepush' 
                            || $this->uri->uri_string() == 'api/fcm/registerdevice'
                            || $this->uri->uri_string() == 'api/tfp/inq'
                            || $this->uri->uri_string() == 'api/tfp/paynotif')
                    {}
                    elseif($this->input->server('HTTP_HOST') == "localhost") {
                        if($this->input->get_request_header('key_ses') == "akumumet"){
                            $this->error_unauthorized_ses1();  
                            return;
                        }
                    }elseif(($this->uri->uri_string() == 'api/commerce/pulsa_sms' || $this->uri->uri_string() == 'api/auth/apptypemodel' || $this->uri->uri_string() == 'api/commerce/pln_token_sms')) {
                        $this->getidsession = $this->input->get_request_header('key_ses');                            
                        if($this->getidsession == "s3s_gwpulsa"){                                                           
                        }else{
                            $this->error_unauthorized_ses1();  
                            return;
                        }                      
                    }else{
                        $key_session    = $this->input->get_request_header('key_ses');
                        $ip_addrs       = $this->input->ip_address();                        
                        
                        $find_key = $this->Kunci_model->Find_key($ip_addrs,$key_session);                        
                        if(!$find_key){
                            $this->error_unauthorized_ses2();  
                            return;
                        }
                        foreach($find_key as $val){
                            $waktu = $val->waktu ?: '';
                            $agent = $val->agent_id ?: '';
                        }
                        
                        if($waktu >= APIINTERVAL){
                            $this->Kunci_model->Del_key($ip_addrs,$key_session);
                            $this->Kunci_model->Del_by_agent($agent ?: '');
                            $this->error_unauthorized_ses2();  
                            return;
                        }
                        $res_upd = $this->Kunci_model->Upd_key($key_session);
                        if($res_upd){                            
                        }else{ 
                            $this->error_unauthorized_ses1();  
                            return;
                        }
                    }
                }
        }


	// Verify access token (e.g. API Key, JSON Web Token)
	protected function verify_token()
	{
		// lookup API Key record by value from HTTP header
		$key = $this->input->get_request_header('APIKEY');
		$this->mApiKey = $this->api_keys->get_by('key', $key);

		if ( !empty($this->mApiKey) )
		{
			$this->mUser = $this->users->get($this->mApiKey->user_id);

			// only when the API Key represents a user
			if ( !empty($this->mUser) )
			{
				$this->mUserGroups = $this->ion_auth->get_users_groups($this->mUser->id)->result();

				// TODO: get group with most permissions (instead of getting first group)
				$this->mUserMainGroup = $this->mUserGroups[0]->name;	
			}
			else
			{
				// anonymous access via API Key
				$this->mUserMainGroup = 'anonymous';
			}
		}
	}
	
	// Verify authentication (by user group, or by "anonymous")
	// $group parameter can be name, ID, name array, ID array, or mixed array
	// Reference: http://benedmunds.com/ion_auth/#in_group
	protected function verify_auth($groups = 'members')
	{
		$groups = is_string($groups) ? array($groups) : $groups;

		if ( empty($this->mUser) )
		{
			// anonymous access
			if ( !in_array($this->mUserMainGroup, $groups) )
				$this->error_unauthorized();
		}
		else
		{
			// user groups not match with requirement
			if ( !$this->ion_auth->in_group($groups, $this->mUser->id) )
				$this->error_unauthorized();
		}
	}
	
	// Shortcut functions following REST_Controller convention
	protected function success($msg = NULL)
	{
		$data = array('status' => TRUE);
		if ( !empty($msg) ) $data['message'] = $msg;
		$this->response($data, REST_Controller::HTTP_OK);
	}

	protected function created($msg = NULL)
	{
		$data = array('status' => TRUE);
		if ( !empty($msg) ) $data['message'] = $msg;
		$this->response($data, REST_Controller::HTTP_CREATED);
	}
	
	protected function accepted($msg = NULL)
	{
		$data = array('status' => TRUE);
		if ( !empty($msg) ) $data['message'] = $msg;
		$this->response($data, REST_Controller::HTTP_ACCEPTED);
	}

	protected function error($msg = 'An error occurs', $code = REST_Controller::HTTP_OK, $additional_data = array())
	{
		$data = array('status' => FALSE, 'error' => $msg);

		// (optional) append additional data
		if (!empty($additional_data))
			$data['data'] = $additional_data;

		$this->response($data, $code);
	}
	
	protected function error_bad_request()
	{
		$data = array('status' => FALSE);
		$this->response($data, REST_Controller::HTTP_BAD_REQUEST);
	}
	
	protected function error_unauthorized()
	{
		$data = array('status' => FALSE);
		$this->response($data, REST_Controller::HTTP_UNAUTHORIZED);
	}
        function error_unauthorized_ses()
	{
		$data = array('status' => FALSE,'message' => 'expired');
		$this->response($data, REST_Controller::HTTP_UNAUTHORIZED);
	}
        function error_unauthorized_ses1()
	{
		$data = array('status' => FALSE,'message' => 'expired');
		$this->response($data, REST_Controller::HTTP_UNAUTHORIZED);
	}
        function error_unauthorized_ses2()
	{
		$data = array('status' => FALSE,'message' => 'expired');
		$this->response($data, REST_Controller::HTTP_UNAUTHORIZED);
	}
	
	protected function error_forbidden()
	{
		$data = array('status' => FALSE);
		$this->response($data, REST_Controller::HTTP_FORBIDDEN);
	}
	
	protected function error_not_found()
	{
		$data = array('status' => FALSE);
		$this->response($data, REST_Controller::HTTP_NOT_FOUND);
	}
	
	protected function error_method_not_allowed()
	{
		$data = array('status' => FALSE);
		$this->response($data, REST_Controller::HTTP_METHOD_NOT_ALLOWED);
	}

	protected function error_not_implemented($additional_data = array())
	{
		// show "not implemented" info only during development mode
		if (ENVIRONMENT=='development')
		{
			$trace = debug_backtrace();
			$caller = $trace[1];

			$data = array(
				'url'			=> current_url(),
				'module'		=> $this->router->fetch_module(),
				'controller'	=> $this->router->fetch_class(),
				'action'		=> $this->router->fetch_method(),
			);

			if (!empty($additional_data))
				$data = array_merge($data, $additional_data);

			$this->error('Not implemented', REST_Controller::HTTP_NOT_IMPLEMENTED, $data);
		}
		else
		{
			$this->error_not_found();
		}
	}
        function LogAction($strAction,$trace_id = '', array $arrData = NULL, $message = NULL) {
            if(empty($trace_id)){
                $trace_id = $this->logid();
            }
            $strMessage = '';
            $strMessage .= 'Action: ' . $strAction . ' |';
            $strMessage .= 'API|';
            $strMessage .= $trace_id;
            //$strMessage .= '[' . $this->input->ip_address() . '] '; 
            // add data if provided
            if($message){
                $strMessage .= ' |message: ['.$message.']';
            }
            if ($arrData) {
                $strMessage .= ' ---|data: ' . str_replace(array("\n", "\r", "    "), '', print_r($arrData, true));
            }

            log_message('info', $strMessage);
        }
        function LogException(Exception $oException) {
            $strMessage = '';
            $strMessage .= $oException->getMessage() . ' ';
            $strMessage .= $oException->getCode() . ' ';
            $strMessage .= $oException->getFile() . ' ';
            $strMessage .= $oException->getLine();
            $strMessage .= "\n" .  $oException->getTraceAsString();

            log_message('error', $strMessage);
        }
        function Logid() {
            $randid = random_string('alnum','14');    
            return $randid;
        }
        function Trxid() {
            $randid = date("ymd").random_string('numeric','14');    
            return $randid;
        }
        function Logheader($trace_id = '') {
        $this->Logbegin($trace_id);
        $datalog = array('uri' => $this->uri->uri_string(),
                'method' => $this->request->method,
                'params' => $this->_args ? ($this->config->item('rest_logs_json_params') === TRUE ? json_encode($this->_args) : serialize($this->_args)) : NULL,
                'api_key' => isset($this->rest->key) ? $this->rest->key : '',
                'key_session'    => $this->input->get_request_header('key_ses'),
                'time' => time());
        $this->logAction('receiver', $trace_id, $datalog, '');
        }
        function Logbegin($trace_id) {
            $strMessage = '';
            $strMessage .= 'Action: begin';
            $strMessage .= '|API|';
            $strMessage .= $trace_id;
            $strMessage .= '[' . $this->input->ip_address() . '] ';
            log_message('info', $strMessage);
            
        }
        function Logend($trace_id) {
            $strMessage = '';
            $strMessage .= 'Action: Finish';
            $strMessage .= '|API|';
            $strMessage .= $trace_id;
            log_message('info', $strMessage);
        }
        function Duwet($int = 0,$min = 0,$max = 10000000) {
            if (filter_var($int, FILTER_VALIDATE_INT, array("options" => array("max_range"=>$max))) === false) {
                return FALSE;
            } else {                
                return TRUE;
            }
            
        }
        function Duwet_no_limit($int = 0) {
            if (filter_var($int, FILTER_VALIDATE_INT) === false) {
                return FALSE;
            } else {                
                return TRUE;
            }
            
        }
        function CTStart() {
            $this->session->unset_userdata('session_microtime_start');
            $this->session->set_userdata('session_microtime_start', time(true));
        }
        function CTEnd() {
            $stoptime  = time(true);
            $startime = $this->session->userdata('session_microtime_start');
            $this->session->unset_userdata('session_microtime_start');
            if($startime){
                $counttime = $stoptime - $startime;
                 $status = floor($counttime);
                 return $status;
            }
            return 0;
        }
        
        function HitPost($url = '',$fields = '') {
        //firebase
        //importing the constant files
        //require_once 'Config.php';
        ignore_user_abort();
        ob_start();

        //firebase server url to send the curl request
        $url = base_url();
 
        //building headers for the request
        $headers = array(
            'Authorization: key=',
            'Content-Type: application/json',
            'APIKEY: els3j4ht3r4',
            'key_ses: akumumet',
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
        curl_close($ch);
        return $result;
        ob_flush();
    }
    public function Count_day($tgl_to = '',$tgl_fr = '') {
        if(empty($tgl_fr) || empty($tgl_to)):
            return 0;
        endif;
        $selisih = strtotime($tgl_to) - strtotime($tgl_fr);
        $this->hari = $selisih/(60*60*24);
        $this->hari = $this->hari + 1;
        return $this->hari;
    }
    public function Msisdn_filter($nohp = '') {
        if(empty($nohp)){
            return false;
        }
        // kadang ada penulisan no hp 0811 239 345
        $nohp = str_replace(" ","",$nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace("(","",$nohp);
        // kadang ada penulisan no hp (0274) 778787
        $nohp = str_replace(")","",$nohp);
        // kadang ada penulisan no hp 0811.239.345
        $nohp = str_replace(".","",$nohp);

        // cek apakah no hp mengandung karakter + dan 0-9
        if(!preg_match('/[^+0-9]/',trim($nohp))){
            // cek apakah no hp karakter 1-3 adalah +62
            if(substr(trim($nohp), 0, 1)=='0'){
                $hp = trim($nohp);
            }
            elseif(substr(trim($nohp), 0, 3)=='+62'){                
                $hp = '0'.substr(trim($nohp), 0,3);
            }
            // cek apakah no hp karakter 1 adalah 0            
            elseif(substr(trim($nohp), 0, 2)=='62'){
                $hp = '0'.substr(trim($nohp), 2);
           }
           else{
                $hp = $nohp;
           }
           return $hp;
       }
       return $nohp;       
    } 

}