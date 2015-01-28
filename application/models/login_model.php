<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }
    
    private $_tableName = "users";
    
    public function validate(){
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));
        
        $this->db->where('login', $username);
        $this->db->where('password', md5($password));

        $query = $this->db->get('users');

        if($query->num_rows == 1)
        {
            $row = $query->row();
            $data = array(
                'id' => $row->id,
                'email' => $row->email,
                'login' => $row->login,
                'register_date' => $row->register_date,
                'last_connection' => $row->last_connection,
                'validated' => true
            );
            $this->session->set_userdata($data);
            
            // update last_connection value
            $sql = "UPDATE $this->_tableName "
                    ."SET last_connection = NOW() "
                    ."WHERE "
                       ."id = ? ";
        
            $params = Array($this->session->userdata('id'));
            $this->db->query($sql, $params);
            
            return true;
        }

        return false;
    }
}
?>