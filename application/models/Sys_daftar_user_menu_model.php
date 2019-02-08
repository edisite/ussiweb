<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sys_daftar_user_menu_model
 *
 * @author edisite
 */
class Sys_daftar_user_menu_model extends MY_Model{
    //put your code here
    public function get_id($id)
    {
        $data = array();
        $datamenu = array();
        $datagrop = array();                       
           foreach ($this->menu_group($id) as $rw){
                   $menug =  $rw->menu_group;          
                   $urutg =  $rw->urutan_group;   
                   $uname =  $rw->USER_NAME;
                                if($this->menu_prompt($uname,$urutg)){
                                    $datamenu = '';
                                    foreach ($this->menu_prompt($uname, $urutg) as $pal) {                                                     
                                                  $datamenu[$pal->menu_prompt] = $pal->submenu;                                    
                                             }
                                    $datagrop['children'] = $datamenu;
                                }
                     $data[$menug] = $datagrop;    
                   }
                   return $data;                
    }
    public function get_menu($id)
    {
        $tb = '';
           $colspan = 1;
           foreach ($this->menu_group($id) as $rw){

                        $menug =  $rw->menu_group;          
                        $urutg =  $rw->urutan_group;   
                        $uname =  $rw->USER_NAME;

                            $menu_prompt = $this->menu_prompt($uname,$urutg);  
                            $subtb = '';
                            $rowspan = 1;
                            foreach ($menu_prompt as $pal) {    
                                            //data-id="'.$rowspan.'"
                                          $subtb .= '<li class="dd-item" data-menuid="'.$pal->menu_id.'"><div class="dd-handle">'.$pal->submenu.'</div></li>';
                                          $rowspan ++;

                            }
                        $tb .= '';    
                        //data-id="'.$urutg.'"
                            $tb .= '<li class="dd-item" data-name="'.$menug.'">'
                                    . '<div class="dd-handle">'.$menug.'</div>';
                            $tb .= '<ol class="dd-list">';
                                $tb .= $subtb;       
                            $tb .='</ol>';
                        $tb .='</li>';
                        $colspan ++;
                   }
                   if(empty($tb)){
                       $tb .= '<li class="dd-item" data-id=""><div class="dd-handle">Kosong</div></li>';
                   }
                   return $tb;                
    }
    public function get_menu_src($userpegawai) {
        $grp = '';
        foreach ($this->menu_src($userpegawai) as $rw){
             $grp .= '<li class="dd-item"  data-menuid="'.$rw->MENU_ID.'"><div class="dd-handle">'.$rw->MENU_ID.'-'.$rw->MENU_PROMPT.'</div></li>';
        }
        //data-id="'.$rw->MENU_ID.'"
        return $grp;

    }
    public function get_menu_mobile_src($userpegawai) {
        $grp = '';
        foreach ($this->menu_mobile_src($userpegawai) as $rw){
             $grp .= '<li class="dd-item"  data-menuid="'.$rw->MENU_ID.'"><div class="dd-handle">'.$rw->MENU_ID.'-'.$rw->MENU_PROMPT.'</div></li>';
        }
        //data-id="'.$rw->MENU_ID.'"
        if(empty($tb)){
            $grp .= '<li class="dd-item" data-menuid=""><div class="dd-handle">Kosong</div></li>';
        }
        return $grp;

    }
    public function get_menu_mobile($userpegawai) {
        $tb = '';
        foreach ($this->menu_prompt_mobile($userpegawai) as $rw){
             $tb .= '<li class="dd-item"  data-menuid="'.$rw->menu_id.'"><div class="dd-handle">'.$rw->menu_id.'-'.$rw->menu_prompt.'</div></li>';
        }
        //data-id="'.$rw->MENU_ID.'"
        if(empty($tb)){
            $tb .= '<li class="dd-item" data-menuid=""><div class="dd-handle">Kosong</div></li>';
        }
        return $tb;        
    }
    protected function menu_prompt($uname, $urutangrp) {
         $this->uname = $uname;
         $this->urut  = $urutangrp;
        $sql = 'SELECT a.menu_id, b.menu_prompt,concat("[",a.MENU_ID,"]",menu_prompt) as submenu,menu_form FROM sys_user_menu_def a,sys_daftar_menu b where a.user_name ="'.$this->uname.'" and a.menu_id =b.menu_id and urutan_group = "'.$this->urut.' and sys_user_menu_def.flag=1"
ORDER BY a.URUTAN_MENU ASC';
        $qsub = $this->db->query($sql);
        //echo $sql."qsub";
        return $qsub->result();
    }
    public function menu_prompt_mobile($uname) {
        $this->uname = $uname;
        $sql = 'SELECT a.menu_id, b.menu_prompt,concat("[",a.MENU_ID,"]",menu_prompt) as submenu,menu_form FROM sys_user_menu_def_mobile a,sys_daftar_menu_mobile b where a.user_name ="'.$this->uname.'" and a.menu_id =b.menu_id and b.flag=1 ORDER BY a.URUTAN_MENU ASC';
        $qsub = $this->db->query($sql);
        //echo $sql."qsub";
        return $qsub->result();
    }

    protected function menu_group($id) {
        $q = $this->db->query('SELECT DISTINCT (urutan_group),menu_group,USER_NAME FROM sys_user_menu_def a, admin_users b WHERE a.USER_NAME =  b.username AND a.flag = "1" and b.id = "'.$id.'"ORDER BY URUTAN_GROUP ASC');
        return $q->result();
        //print_r($q);
    }
    protected function menu_src($username = '') {
        if(empty($username)){
            return FALSE;
        }
        $SQL = "SELECT MENU_ID, MENU_PROMPT FROM sejahtera.sys_daftar_menu WHERE flag ='1' "
                . "AND menu_id NOT IN (SELECT MENU_ID FROM sejahtera.sys_user_menu_def "
                . "WHERE USER_NAME = '".$username."') ORDER BY MENU_ID ASC";
        $q = $this->db->query($SQL);
        return $q->result();
        //print_r($q);
    }
    protected function menu_mobile_src($username = '') {
        if(empty($username)){
            return FALSE;
        }
        $SQL = "SELECT MENU_ID, MENU_PROMPT FROM sejahtera.sys_daftar_menu_mobile WHERE flag ='1' "
                . "AND menu_id NOT IN (SELECT MENU_ID FROM sejahtera.sys_user_menu_def_mobile "
                . "WHERE USER_NAME = '".$username."') ORDER BY MENU_ID ASC";
        $q = $this->db->query($SQL);
        return $q->result();
        //print_r($q);
    }
    public function menu_register($username = '') {
        if(empty($username)){
            redirect();
        }
        if($this->menu_del($username)){               
            $sql = 'INSERT INTO sys_user_menu_def(USER_NAME, URUTAN_GROUP, MENU_GROUP, URUTAN_MENU, MENU_ID, flag) SELECT "'.strtoupper($username).'", URUTAN_GROUP, MENU_GROUP, URUTAN_MENU, MENU_ID, "0" FROM sys_user_menu_def WHERE user_name ="ussi"';
            $query = $this->db->query($sql);
        return $query;
        }

    }
    public function getmenu_by_id($menuid = '') {
        if(empty($menuid)){
            return false;
        }             
        //$sql    = 'SELECT MENU_ID, MENU_PROMPT,UPPER(CONCAT("[",MENU_ID,"]",menu_prompt)) AS submenu FROM sys_daftar_menu WHERE MENU_ID ="'.$menuid.'" LIMIT 1';
        $sql    = 'SELECT MENU_ID, MENU_PROMPT,UPPER(menu_prompt) AS submenu FROM sys_daftar_menu WHERE MENU_ID ="'.$menuid.'" LIMIT 1';
        $query  = $this->db->query($sql);
        return $query->result(); 
    }
    protected function menu_del($username = '') {
        if(empty($username)){
            return FALSE;
        }
        $this->db->where('user_name', $username);
        $this->db->delete('sys_user_menu_def');
        return TRUE;
    }
    public function menu_duplicat($username_ori = '',$username_cpy ='') {
        if(empty($username_ori) || empty($username_cpy)){
            return FALSE;
        }
        if($this->menu_del($username_ori)){               
            $sql = 'INSERT INTO sys_user_menu_def(USER_NAME, URUTAN_GROUP, MENU_GROUP, URUTAN_MENU, MENU_ID, flag) SELECT "'.strtoupper($username_ori).'", URUTAN_GROUP, MENU_GROUP, URUTAN_MENU, MENU_ID, flag FROM sys_user_menu_def WHERE user_name ="'.$username_cpy.'"';
            $query = $this->db->query($sql);
            //return $query;
            return true;
        }
        return FALSE;

    }
    public function del_menu_def($in_user_id = '') {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            return FALSE;
        } 
        if(empty($in_user_id)){
            return FALSE;
        }
        $query = $this->db->delete('sys_user_menu_def', array('USER_NAME' => $in_user_id)); 
        return $query;
    }
    public function del_menu_def_mobile($in_user_id = '') {
        if($this->session->userdata('user_id') == "" || $this->session->userdata('username') == "")
        {
            return FALSE;
        } 
        if(empty($in_user_id)){
            return FALSE;
        }
        $query = $this->db->delete('sys_user_menu_def_mobile', array('USER_NAME' => $in_user_id)); 
        return $query;
    }
}
