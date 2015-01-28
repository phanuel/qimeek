<?php

class Bookmarks extends CI_Controller {
    
    function __construct(){
        parent::__construct();
        $this->check_isvalidated();
        
        $this->load->model('bookmarks_table');
    }
    
    private function check_isvalidated(){
        if(! $this->session->userdata('validated')){
            redirect('login');
        }
    }
    
    public function search() {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $this->load->model('directories_table');
            
            $input = $this->security->xss_clean($this->input->post('input'));
            
            if ($input != false) {
                $directories = $this->directories_table->searchDirectories($input);
                $bookmarks = $this->bookmarks_table->searchBookmarks($input);

                $data['input'] = $input;
                $data['subdirectories'] = $directories;
                $data['bookmarks'] = $bookmarks;
                
                $layoutData['content'] = $this->load->view('directories/index', $data, true);

                $this->load->view('layouts/layout', $layoutData);
            }
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
            $url = $this->security->xss_clean($this->input->post('url'));
            $title = $this->security->xss_clean($this->input->post('title'));
            
            if ($url != false && $title != false) {
                $this->bookmarks_table->addBookmark($currentDirectoryId, $url, $title);
                
                redirect("directories/index/".$currentDirectoryId);
            }

            $layoutData['content'] = $this->load->view('bookmarks/add', null, true);

            $this->load->view('layouts/layout', $layoutData);
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }
    
    public function edit($bookmarkId) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $bookmark = $this->bookmarks_table->getBookmarkById($bookmarkId);

            $newBookmarkUrl = $this->security->xss_clean($this->input->post('url'));
            $newBookmarkTitle = $this->security->xss_clean($this->input->post('title'));

            if ($newBookmarkUrl != false && $newBookmarkTitle != false) {
                $bookmark->SetUrl($newBookmarkUrl);
                $bookmark->SetTitle($newBookmarkTitle);

                $this->bookmarks_table->editBookmark($bookmark);

                redirect("directories/index/".$bookmark->GetDirectory()->GetId());
            }

            $data['bookmark'] = $bookmark;
            $layoutData['content'] = $this->load->view('bookmarks/edit', $data, true);

            $this->load->view('layouts/layout', $layoutData);
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }
    
    public function delete($bookmarkId) {
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(TRUE); // in dev: enabling profiling
        }
        
        try {
            $bookmark = $this->bookmarks_table->getBookmarkById($bookmarkId);

            $this->bookmarks_table->deleteBookmark($bookmark);

            redirect("directories/index/".$bookmark->GetDirectory()->GetId());
        }catch (Exception $e) {
            $data['exception'] = $e->getMessage();
            $layoutData['content'] = $this->load->view('directories/index', $data, true);
            $this->load->view('layouts/layout', $layoutData);
        }
    }

}

?>
