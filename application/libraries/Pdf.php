<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once (dirname(__FILE__).'/tcpdf/tcpdf.php');
require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pdf
 *
 * @author edisite
 */

class Pdf extends TCPDF{
        function __construct()
        {
            parent::__construct();
        }
        
    }
    /*Author:Tutsway.com */  
    /* End of file Pdf.php */
    /* Location: ./application/libraries/Pdf.php */