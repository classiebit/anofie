<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notifications Model
 *
 * This model handles notifications module data
 *
 * @package     atn
 * @author      prodpk
*/

class Notifications_model extends CI_Model {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @vars
     */
    private $table = 'notifications';

    /**
     * get_notifications
     *
     * @return array
     * 
     **/
    public function get_notifications($users_id = NULL)
    {
        $query = "SELECT `id` FROM $this->table WHERE `users_id` = '1' GROUP BY `n_type`";

        if( $this->db->simple_query($query) )
        {
            // safe mode is off
            $select = array(
                            "$this->table.id",
                            "COUNT($this->table.n_type) as total",
                            "$this->table.n_type",
                            "$this->table.n_content",
                            "$this->table.date_added",
                        );
        }
        else
        {
            // safe mode is on
            $select = array(
                            "ANY_VALUE($this->table.id) as id",
                            "COUNT($this->table.n_type) as total",
                            "$this->table.n_type",
                            "ANY_VALUE($this->table.n_content) as n_content",
                            "ANY_VALUE($this->table.date_added) as date_added",
                        );
        }
        
        $this->db->select($select);

        if($users_id)
            $this->db->where(array("$this->table.users_id"=>$users_id));

        return $this->db->group_by("$this->table.n_type")
                        ->get($this->table)
                        ->result();
    }

    /**
     * save_notifications
     *
     * @return array
     * 
     **/
    public function save_notifications($data = array())
    {
        $data['date_added'] = date('Y-m-d H:i:s');
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * delete_notifications
     *
     * @return array
     * 
     **/
    public function delete_notifications($n_type = NULL, $users_id = NULL)
    {
        if($n_type && $users_id) // update
            $this->db->delete($this->table, array('n_type' => $n_type, 'users_id'=>$users_id)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }
    
}

/* Notifications model ends*/