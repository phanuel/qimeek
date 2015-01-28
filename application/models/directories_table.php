<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Directories_table extends CI_Model{
    function __construct(){
        parent::__construct();
        
        $this->load->library('bkm_directory');
    }
    
    private $_tableName = "directories";
    
    public function getHomeDirectory() {
        $sql = "SELECT * "
              ."FROM $this->_tableName "
              ."WHERE "
                ."parent_id IS NULL "
                ."AND user_id = ?";
        
        $params = Array($this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params)->result();

        $directory = new Bkm_directory($results[0]);
        
        return $directory;
    }
    
    public function getDirectoryById($id) {
        $sql = "SELECT * "
              ."FROM $this->_tableName "
              ."WHERE "
                ."id = ? "
                ."AND user_id = ?";
        
        $params = Array($id, $this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params)->result();

        if (count($results) > 0) {
            $directory = new Bkm_directory($results[0]);
        }else {
            throw new Exception("Directory not found.");
        }
        
        return $directory;
    }
    
    public function searchDirectories($input) {
        $this->load->helper("text");
        
        $input = clean_query_text($input, false);
        $input = accents_insensitive_pattern($input);
        
        $sql = "SELECT * "
                ."FROM $this->_tableName "
                ."WHERE "
                  ."("
                    // search by words
                    ."CONVERT(LOWER(CONCAT(name, '.')) USING latin1) REGEXP CONCAT('^.*([[:<:]]', ?, '[[:>:]].*$)') " // Why "CONCAT(title, '.')"? Because adding a point at the end of the field's content makes the regexp to work also for the last word of the string (name often ends without ponctuation) and for single word strings.
                  .") "
                  ."AND user_id = ?";
        
        $params = Array($input, $this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params)->result();
        
        $directories = Array();
        foreach($results as $result) {
            $directory = new Bkm_directory($result);
            array_push($directories, $directory);
        }
        
        return $directories;
    }
    
    public function getSubDirectories(Bkm_directory $directory) {
        $sql = "SELECT * "
              ."FROM $this->_tableName "
              ."WHERE "
                ."parent_id = ? "
                ."AND user_id = ? "
                ."ORDER BY LOWER(name)";
        
        $params = Array($directory->GetId(), $this->session->userdata('id'));

        $results = $this->db->query($sql, $params)->result();
        
        $subDirectories = Array();
        foreach($results as $result) {
            $subDirectory = new Bkm_directory($result);
            array_push($subDirectories, $subDirectory);
        }
        
        return $subDirectories;
    }
    
    public function addDirectory($parentDirectoryId, $directoryName) {
        $data = Array(
            "id" => "",
            "parent_id" => $parentDirectoryId,
            "name" => $directoryName,
            "date_added" => date("Y-m-d H:i:s"),
            "user_id" => $this->session->userdata('id')
        );
        
        $this->db->insert($this->_tableName, $data);
    }
    
    public function editDirectory($directory) {
        $sql = "UPDATE "
              ."$this->_tableName "
              ."SET "
                ."parent_id = ?, "
                ."name = ? "
              ."WHERE "
                ."id = ? "
                ."AND user_id = ? ";
        
        $params = Array($directory->GetParent()->GetId(), $directory->GetName(), $directory->GetId(), $this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params);
    }
    
    public function deleteDirectory($directory) {
        // delete subdirectories recursively
        foreach ($directory->GetChildren() as $child) {
            $this->deleteDirectory($child);
        }
        
        // delete bookmarks
        $this->load->model('bookmarks_table');
        foreach ($directory->GetBookmarks() as $bookmark) {
            $this->bookmarks_table->deleteBookmark($bookmark);
        }
        
        // delete directory
        $sql = "DELETE "
              ."FROM $this->_tableName "
              ."WHERE "
                ."id = ? "
                ."AND user_id = ? ";
        
        $params = Array($directory->GetId(), $this->session->userdata('id'));

        $results = $this->db->query($sql, $params);
    }

}
?>