<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Control extends Admin_Controller{
    public function __construct()
    {
            parent::__construct();
            $this->load->library('form_builder');
            $this->mTitle = 'Admin Control - ';
    }        
    public function Control_list() 
    {
            
            $crud = $this->generate_crud('handle_apps');
            $crud->columns('cpid', 'cpname', 'dbhost', 'dbuser', 'dbpass','dbname','dbconn');
            $crud->display_as('cpid','CP ID');
            $crud->display_as('cpname','CP NAME');
            $crud->display_as('dbhost','HOST');
            $crud->display_as('dbuser','USER');
            $crud->display_as('dbpass','PSWD');
            $crud->display_as('dbname','DB');
            $crud->display_as('dbconn','CONN');
            
            $crud->set_subject('Connection');
            $crud->unset_delete();
            $this->mViewData['groups'] = 'TES';
            $this->mTitle.= 'Conection';
            $this->render_crud();
    }
}
