<?php

class Directories extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->check_isvalidated();
        
        $this->load->model('directories_table');
    }
    
    private function check_isvalidated(){
        if(! $this->session->userdata('validated')){
            redirect('login');
        }
    }
    
    public function index($directoryId = null) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            if ($directoryId == null) {
                $directory = $this->directories_table->GetHomeDirectory();
            }else {
                $directory = $this->directories_table->GetDirectoryById($directoryId);
            }
            
            $data['directory'] = $directory;
            $data['subdirectories'] = $directory->GetChildren();
            $data['bookmarks'] = $directory->GetBookmarks();

            $layoutData['content'] = $this->load->view('directories/index', $data, true);

            $this->load->view('layouts/layout', $layoutData);
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }
    
    public function add($currentDirectoryId) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $newDirectoryName = $this->security->xss_clean($this->input->post('directoryName'));
            
            if ($newDirectoryName != false) {
                $this->directories_table->addDirectory($currentDirectoryId, $newDirectoryName);
                
                redirect("directories/index/".$currentDirectoryId);
            }

            $layoutData['content'] = $this->load->view('directories/add', null, true);

            $this->load->view('layouts/layout', $layoutData);
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }
    
    public function edit($directoryId) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $directory = $this->directories_table->getDirectoryById($directoryId);
            
            if ($directory->GetParent() == null) {
                throw new Exception("Le répertoire racine ne peut pas être modifié.");
            }else {
                $newDirectoryName = $this->security->xss_clean($this->input->post('directoryName'));

                if ($newDirectoryName != false) {
                    $directory->SetName($newDirectoryName);

                    $this->directories_table->editDirectory($directory);

                    redirect("directories/index/".$directory->GetParent()->GetId());
                }
                
                $data['directory'] = $directory;
                $layoutData['content'] = $this->load->view('directories/edit', $data, true);

                $this->load->view('layouts/layout', $layoutData);
            }
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }
    
    public function delete($directoryId) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $directory = $this->directories_table->getDirectoryById($directoryId);

            if ($directory->GetParent() == null) {
                throw new Exception("Le répertoire racine ne peut pas être supprimé.");
            }else {
                $this->directories_table->deleteDirectory($directory);
                
                redirect("directories/index/".$directory->GetParent()->GetId());
            }
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }

}

?>
