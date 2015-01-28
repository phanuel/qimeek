<?php

class Index extends CI_Controller {
    
    function __construct(){
        parent::__construct();
    }

    public function index() {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $layoutData['content'] = $this->load->view('index/index', null, true);

            $this->load->view('layouts/layout', $layoutData);
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('index/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }

}

?>
