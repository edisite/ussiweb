<?php

/**
 * Base Controller for Admin module
 */
class Admin_Controller extends MY_Controller {

	protected $mLoginUrl = 'admin/login';
	protected $mUsefulLinks = array();

	// Grocery CRUD or Image CRUD
	protected $mCrud;
	protected $mCrudUnsetFields;

	// Constructor
	public function __construct()
	{
		parent::__construct();

		// only login users can access Admin Panel
		$this->verify_login();

		// store site config values
		$this->mUsefulLinks = $this->mSiteConfig['useful_links'];
                //date_default_timezone_set('Asia/Jakarta');
	}

	// Render template (override parent)
	protected function render($view_file, $layout = 'default')
	{
		// load skin according to user role
		$config = $this->mSiteConfig['adminlte'][$this->mUserMainGroup];
		$this->mBodyClass = $config['skin'];
		
		// additional view data
		$this->mViewData['useful_links'] = $this->mUsefulLinks;

		parent::render($view_file);
	}

	// Initialize CRUD table via Grocery CRUD library
	// Reference: http://www.grocerycrud.com/
	protected function generate_crud($table, $subject = '')
	{
		// create CRUD object
		$this->load->library('Grocery_CRUD');
		$crud = new grocery_CRUD();
		$crud->set_table($table);

		// auto-generate subject
		if ( empty($subject) )
		{
			$crud->set_subject(humanize(singular($table)));
		}

		// load settings from: application/config/grocery_crud.php
		$this->load->config('grocery_crud');
		$this->mCrudUnsetFields = $this->config->item('grocery_crud_unset_fields');

		if ($this->config->item('grocery_crud_unset_jquery'))
			$crud->unset_jquery();

		if ($this->config->item('grocery_crud_unset_jquery_ui'))
			$crud->unset_jquery_ui();

		if ($this->config->item('grocery_crud_unset_print'))
			$crud->unset_print();

		if ($this->config->item('grocery_crud_unset_export'))
			$crud->unset_export();

		if ($this->config->item('grocery_crud_unset_read'))
			$crud->unset_read();

		foreach ($this->config->item('grocery_crud_display_as') as $key => $value)
			$crud->display_as($key, $value);

		// other custom logic to be done outside
		$this->mCrud = $crud;
		return $crud;
	}
	
	// Set field(s) to color picker
	protected function set_crud_color_picker()
	{
		$args = func_get_args();
		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}
		foreach ($args as $field)
		{
			$this->mCrud->callback_field($field, array($this, 'callback_color_picker'));
		}
	}

	public function callback_color_picker($value = '', $primary_key = NULL, $field = NULL)
	{
		$name = $field->name;
		return "<input type='color' name='$name' value='$value' style='width:80px' />";
	}

	// Append additional fields to unset from CRUD
	protected function unset_crud_fields()
	{
		$args = func_get_args();
		if(isset($args[0]) && is_array($args[0]))
		{
			$args = $args[0];
		}
		$this->mCrudUnsetFields = array_merge($this->mCrudUnsetFields, $args);
	}

	// Initialize CRUD album via Image CRUD library
	// Reference: http://www.grocerycrud.com/image-crud
	protected function generate_image_crud($table, $url_field, $upload_path, $order_field = 'pos', $title_field = '')
	{
		// create CRUD object
		$this->load->library('Image_crud');
		$crud = new image_CRUD();
		$crud->set_table($table);
		$crud->set_url_field($url_field);
		$crud->set_image_path($upload_path);

		// [Optional] field name of image order (e.g. "pos")
		if ( !empty($order_field) )
		{
			$crud->set_ordering_field($order_field);
		}

		// [Optional] field name of image caption (e.g. "caption")
		if ( !empty($title_field) )
		{
			$crud->set_title_field($title_field);
		}

		// other custom logic to be done outside
		$this->mCrud = $crud;
		return $crud;
	}

	// Render CRUD
	protected function render_crud()
	{
		// logic specific for Grocery CRUD only
		$crud_obj_name = strtolower(get_class($this->mCrud));
		if ($crud_obj_name==='grocery_crud')
		{
			$this->mCrud->unset_fields($this->mCrudUnsetFields);	
		}

		// render CRUD
		$crud_data = $this->mCrud->render();

		// append scripts
		$this->add_stylesheet($crud_data->css_files, FALSE);
		$this->add_script($crud_data->js_files, TRUE, 'head');

		// display view
		$this->mViewData['crud_output'] = $crud_data->output;
		$this->render('crud');
	}
        function Hitget($url)
        {
            $ch = curl_init();  
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
            return $output;
        }
        function Hitpost($url= '',$postText='') {
            $ch=curl_init($url); 
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postText);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt( $ch, CURLOPT_HTTPHEADER, 
            array( 'XAPIKEY : gwpulsa3lsejaht3r4',
                'KEY_SES : s3s_gwpulsa',
                'Content-Type' => 'application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 100);          
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                $sfd = "Failed : ".curl_error($ch);
                return $sfd;
            } else {
                return $data;
            }
            curl_close($ch);  
        }
        
        function LogAction($strAction,$trace_id = '', array $arrData = NULL, $message = NULL) {
            if(empty($trace_id)){
                $trace_id = $this->logid();
            }
            $strMessage = '';
            $strMessage .= 'Action: ' . $strAction . ' |';
            $strMessage .= 'WEB|';
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
                'method' => '',
                //'params' => $this->_args ? ($this->config->item('rest_logs_json_params') === TRUE ? json_encode($this->_args) : serialize($this->_args)) : NULL,
                'params' => '',
                'api_key' => '',
                'key_session'    => '',
                'time' => time());
        $this->logAction('receiver', $trace_id, $datalog, '');
        }
        function Logbegin($trace_id) {
            $strMessage = '';
            $strMessage .= 'Action: begin';
            $strMessage .= '|WEB|';
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
        function Response($arr = array()) {
            echo json_encode($arr);
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
               return $hp;
           }
           return $nohp;       
        } 

        
}