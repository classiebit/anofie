<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Manage_acl Model
 *
 * This model handles testimonials module data
 *
 * @package     anofie
 * @author      classiebit
*/

class Manage_acl_model extends CI_Model {

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
    private $table = 'permissions';

    
    /**
     * get_controllers
     */
    public function get_controllers()
    {
        return $this->db->order_by('id', 'ASC')
                        ->get('controllers')
                        ->result_array();
    }


    /**
     * get_group_permissions
     */
    public function get_group_permissions($groups_id = 0)
    {
        return $this->db->where(array('groups_id'=>$groups_id))
                        ->get($this->table)
                        ->result_array();
    }

    /**
     * save_permissions
     *
     * @return array
     * 
     **/
    public function save_permissions($data = array(), $groups_id = NULL)
    {
        if(!empty($data) && $groups_id) // update
        {
            $group = $this->db->where(array('groups_id'=>$groups_id))
                              ->count_all_results($this->table);

            if($group) // if permissions already inserted then update
            {
                foreach($data as $val)
                    $this->db->where(array('groups_id'=>$groups_id, 'controllers_id'=>$val['controllers_id']))
                             ->update($this->table, $val);

                return $groups_id;    
            }

            $this->db->insert_batch($this->table, $data);
        }
        
        return TRUE;
    }
    
}

/* Manage_acl model ends*/