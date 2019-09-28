<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users Model
 *
 * This model handles users module data
 *
 * @package     anofie
 * @author      classiebit
*/

class Users_model extends CI_Model {

    /**
     * @vars
     */
    private $table          = 'users';
    private $select         = [];
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->select   = [
            "$this->table.id",
            "$this->table.fullname",
            "$this->table.username",
            "$this->table.email",
            "$this->table.ip_address",
            "$this->table.image",
            "$this->table.active",
            "$this->table.is_deleted",
            "$this->table.notifications",
            "$this->table.last_login",
            "$this->table.date_added",
            "$this->table.date_updated",
        ];
    }

    /**
     * count_users
     */
    public function count_users($role = NULL)
    {
        if($role)
            $this->db->where(array('role'=>$role));

        return $this->db->count_all_results($this->table);
    }

    /**
     * get_recent_users
     *
     * @return array
     * 
     **/
    public function get_recent_users()
    {
        $this->db->select($this->select)
                ->where(array('active'=>1, 'users.is_deleted'=>0));

        if(DEMO_MODE)
            $this->db->order_by('id', 'ASC');
        else
            $this->db->order_by('id', 'DESC');
                
        $this->db->limit(13);

        return  $this->db->get('users')->result();
    }

    /**
     * get_search_users
     *
     * @return array
     * 
     **/
    public function get_search_users($search = NULL, $user_id = NULL)
    {
        $this->db->select($this->select);

        $this->db->where('id !=', $this->user['id']);

        $this->db->where(array('users.active'=>1, 'users.is_deleted'=>0))
                    ->group_start()
                    ->or_like('users.fullname', $search, 'both')
                    ->or_like('users.username', $search, 'right')
                    ->or_like('users.email', $search, 'right')
                    ->group_end();

        return $this->db->order_by('users.date_updated DESC')
                        ->limit(10)
                        ->get('users')
                        ->result();
    }

    /**
     * get_users_by_username
     *
     * @return array
     * 
     **/
    public function get_users_by_username($username = NULL)
    {
        $this->db->select($this->select)
                ->where(array('active'=>1, 'username'=>$username, 'users.is_deleted'=>0));

        return  $this->db->get('users')->row();
    }

    /**
     * get_users_by_id
     *
     * @return array
     * 
     **/
    public function get_users_by_id($id = FALSE, $is_array = FALSE, $check_disabled = FALSE)
    {
        $this->db->select($this->select)
                ->where(array('id'=>$id));

        if($is_array)
        {
            // skip deleted and inactive accounts
            $this->db->where(['active' => 1, 'is_deleted'=>0]);
            return $this->db->get($this->table)->row_array();
        }
        
        return $this->db->get($this->table)->row();
    }

    public function get_group_name($g_id = NULL)
    {
        if($g_id)
            return $this->db->where('id', $g_id)
                            ->get('groups')
                            ->row();

        return FALSE;
    }

    /**
     * save_users
     *
     * @return array
     * 
     **/
    public function save_users($data = array(), $id = FALSE, $email = FALSE)
    {
        if($id) // update
        {
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);
            return $id;
        }
        else if($email)
        {
            $this->db->where(array('email'=>$email))
                     ->update($this->table, $data);
            return $email;   
        }
        else // insert
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        
    }

    
    /**
     * Check to see if a username already exists
     *
     * @param  string $username
     * @return boolean
     */
    function username_exists($username = null)
    {
        $query  = $this->db->select(array('id'))
                    ->where(['username'=>$username])
                    ->limit(1)
                    ->get($this->table);
        
        if ($query->num_rows())
            return TRUE;

        return FALSE;
    }

   /**
     * Check to see if an email already exists
     *
     * @param  string $email
     * @return boolean
     */
    function email_exists($email = null)
    {
        $query  = $this->db->select(array('id'))
                    ->where(['email'=>$email])
                    ->limit(1)
                    ->get($this->table);
        
        if ($query->num_rows())
            return TRUE;

        return FALSE;
    }



}

/*Users model ends*/