<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bookmarks_table extends CI_Model{
    function __construct(){
        parent::__construct();
        
        $this->load->library('bookmark');
    }
    
    private $_tableName = "bookmarks";
    
    public function getBookmarkById($id){
        $sql = "SELECT * "
              ."FROM $this->_tableName "
              ."WHERE "
                ."id = ? "
                ."AND user_id = ?";
        
        $params = Array($id, $this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params)->result();

        if (count($results) > 0) {
            $bookmark = new Bookmark($results[0]);
        }else {
            throw new Exception("Bookmark not found.");
        }
        
        return $bookmark;
    }
    
    public function getBookmarks(Bkm_directory $parentDirectory){
        $sql = "SELECT * "
              ."FROM $this->_tableName "
              ."WHERE "
                ."directory_id = ? "
                ."AND user_id = ? "
                ."ORDER BY LOWER(title)";
        
        $params = Array($parentDirectory->GetId(), $this->session->userdata('id'));

        $results = $this->db->query($sql, $params)->result();
        
        $bookmarks = Array();
        foreach($results as $result) {
            $bookmark = new Bookmark($result);
            array_push($bookmarks, $bookmark);
        }
        
        return $bookmarks;
    }
    
    public function searchBookmarks($input) {
        $this->load->helper("text");
        
        $input = clean_query_text($input, false);
        $input = accents_insensitive_pattern($input);
        
        $sql = "SELECT * "
                ."FROM $this->_tableName "
                ."WHERE "
                  ."("
                    // search by words
                    ."CONVERT(LOWER(url) USING latin1) REGEXP CONCAT('^.*([[:<:]]', ?, '[[:>:]].*$)') "
                    ."OR CONVERT(LOWER(CONCAT(title, '.')) USING latin1) REGEXP CONCAT('^.*([[:<:]]', ?, '[[:>:]].*$)') " // Why "CONCAT(title, '.')"? Because adding a point at the end of the field's content makes the regexp to work also for the last word of the string (title often ends without ponctuation) and for single word strings.
                  .") "
                  ."AND user_id = ?";
        
        $params = Array($input, $input, $this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params)->result();
        
        $bookmarks = Array();
        foreach($results as $result) {
            $bookmark = new Bookmark($result);
            array_push($bookmarks, $bookmark);
        }
        
        return $bookmarks;
    }
    
    public function addBookmark($directoryId, $url, $title) {
        $data = Array(
            "id" => "",
            "directory_id" => $directoryId,
            "url" => $url,
            "title" => $title,
            "date_added" => date("Y-m-d H:i:s"),
            "user_id" => $this->session->userdata('id')
        );
        
        $this->db->insert($this->_tableName, $data);
    }
    
    public function editBookmark($bookmark) {
        $sql = "UPDATE "
              ."$this->_tableName "
              ."SET "
                ."directory_id = ?, "
                ."url = ?, "
                ."title = ?"
              ."WHERE "
                ."id = ? "
                ."AND user_id = ? ";
        
        $params = Array($bookmark->GetDirectory()->GetId(), $bookmark->GetUrl(), $bookmark->GetTitle(), $bookmark->GetId(), $this->session->userdata('id'));
        
        $results = $this->db->query($sql, $params);
    }
    
    public function deleteBookmark($bookmark) {
        $sql = "DELETE "
              ."FROM $this->_tableName "
              ."WHERE "
                ."id = ? "
                ."AND user_id = ? ";
        
        $params = Array($bookmark->GetId(), $this->session->userdata('id'));

        $results = $this->db->query($sql, $params);
    }
}
?>