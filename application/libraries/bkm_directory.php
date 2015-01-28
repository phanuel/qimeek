<?php

/**
 * Description of Bkm_directory
 *
 * @author phanu
 */
class Bkm_directory {
    private $_id;
    private $_parentId;
    private $_name;
    private $_dateAdded;
    private $_userId;
    
    private $_children;
    private $_parent;
    private $_parents;
    private $_bookmarks;
    
    private $_directoriesTable;
    private $_bookmarksTable;
    
    public function __construct($row = null) {
        $this->CI =& get_instance();
        
        if ($row != null) {
            $this->_id = $row->id;
            $this->_parentId = $row->parent_id;
            $this->_name = $row->name;
            $this->_dateAdded = $row->date_added;
            $this->_userId = $row->user_id;
        }
        
        $this->CI->load->model('directories_table');
        $this->CI->load->model('bookmarks_table');
        $this->_directoriesTable = new Directories_table();
        $this->_bookmarksTable = new Bookmarks_table();
    }
    
    public function GetId() {
        return $this->_id;
    }
    
    public function GetParent() {
        try {
            if ($this->_parentId != null) {
                if (!isset($this->_parent)) {
                    $this->_parent = $this->_directoriesTable->getDirectoryById($this->_parentId);
                }
                
                return $this->_parent;
            }else {
                return null;
            }
        }catch (Exception $ex) {
            throw($ex);
        }
    }
    
    public function GetName() {
        return $this->_name;
    }
    
    public function GetDateAdded() {
        return $this->_dateAdded;
    }
    
    public function GetUserId() {
        return $this->_userId;
    }
    
    public function GetChildren() {
        if (!isset($this->_children)) {
            $this->_children = $this->_directoriesTable->getSubDirectories($this);
        }
        
        return $this->_children;
    }
    
    public function GetParents() {
        if (!isset($this->_parents)) {
            $parentDirectory = $this->GetParent();
            $this->_parents = Array();
            $e = null;
            
            while ($parentDirectory != null) {
                array_push($this->_parents, $parentDirectory);
                $parentDirectory = $parentDirectory->GetParent();
            }
            
            return array_reverse($this->_parents);
        }
        
        return $this->_parents;
    }
    
    public function GetBookmarks() {
        if (!isset($this->_bookmarks)) {
            $this->_bookmarks = $this->_bookmarksTable->getBookmarks($this);
        }
        
        return $this->_bookmarks;
    }
    
    public function SetName($name) {
        $this->_name = $name;
    }
}

?>
