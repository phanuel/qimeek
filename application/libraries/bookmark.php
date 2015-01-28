<?php

/**
 * Description of Bookmark
 *
 * @author phanu
 */
class Bookmark {
    private $_id;
    private $_directoryId;
    private $_url;
    private $_title;
    private $_dateAdded;
    private $_userId;

    private $_directoriesTable;
    
    public function __construct($row = null) {
        $this->CI =& get_instance();
        
        if ($row != null) {
            $this->_id = $row->id;
            $this->_directoryId = $row->directory_id;
            $this->_url = $row->url;
            $this->_title = $row->title;
            $this->_dateAdded = $row->date_added;
            $this->_userId = $row->user_id;
        }
        
        $this->CI->load->model('directories_table');
        $this->CI->load->model('bookmarks_table');
        $this->_directoriesTable = new Directories_table();
    }
    
    public function GetId() {
        return $this->_id;
    }
    
    public function GetDirectory() {
        try {
            return $this->_directoriesTable->getDirectoryById($this->_directoryId);
        }catch (Exception $ex) {
            throw($ex);
        }
    }
    
    public function GetUrl() {
        return $this->_url;
    }
    
    public function GetTitle() {
        return $this->_title;
    }
    
    public function GetDateAdded() {
        return $this->_dateAdded;
    }
    
    public function GetUserId() {
        return $this->_userId;
    }
    
    public function SetUrl($url) {
        $this->_url = $url;
    }
    
    public function SetTitle($title) {
        $this->_title = $title;
    }
}

?>
