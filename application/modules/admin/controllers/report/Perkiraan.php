<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Perkiraan
 *
 * @author edisite
 */
class Perkiraan extends Admin_Controller{
    //put your code here
    public function __construct() {
        parent::__construct();
    }
    public function Preview() {
            $res_anggaran_basil     = $this->Perk_model->perk();
            $this->mViewData['mutasi']          = $res_anggaran_basil;
            //$this->mTitle   = "[8550]Lap. Daftar Perkiraan";
            $this->mMenuID ='8350';
            $this->render('report/perkiraan');
    }
}
