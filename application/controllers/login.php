<?php

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index($msg = NULL) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $data['msg'] = $msg;
            $layoutData['content'] = $this->load->view('login/index', $data, true);

            $this->load->view('layouts/layout', $layoutData);
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('login/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }
    
    public function process(){
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        $this->load->model('login_model');

        $result = $this->login_model->validate();

        if(!$result){
            $msg = '<font color=red>Invalid username and/or password.</font><br />';
            $this->index($msg);
        }else{
            redirect('directories/index');
        }        
    }
    
    public function do_logout(){
        $this->session->sess_destroy();
        redirect('index/index');
    }

}

?>
