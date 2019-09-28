<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Acl
 *
 * This class handles ACL related functionality
 *
 * @package     anofie
 * @author      classiebit
**/
class Acl
{
    var $CI_LIB;

    function __construct()
    {
        $this->CI_LIB =& get_instance();
    }

    /**
    * check_access
    * check particular access and redirect automatically
    *
    * @param    integer  $groups_id  user group id
    * @param    string   $c_name     controller name
    * @param    string   $column     column name
    * @return   boolean  permission
    */
    public function check_access($controller = 'reports', $permission = 'p_view', $is_json = FALSE, $return_bool = FALSE)
    {
        if(!$this->get_method_permission($this->CI_LIB->session->userdata('groups_id'), $controller, $permission))
        {
            if($return_bool)
                return FALSE;

            $error = sprintf(lang('manage_acl_permission_no'), lang("menu_$controller").' '.lang("manage_acl_$permission"));
            if($is_json)
                $this->CI_LIB->json_response([], $error);
            
            $this->CI_LIB->session->set_flashdata('error', $error);
            redirect('admin/dashboard');
        }

        return TRUE;
    }

    /**
    * get_method_permission
    *
    * @param    integer  $groups_id  user group id
    * @param    string   $c_name     controller name
    * @param    string   $column     column name
    * @return   boolean  permission
    */
    public function get_method_permission($groups_id = 0, $c_name = '', $column = '')
    {
        if($column != 'p_view' && $column != 'p_add' && $column != 'p_edit' && $column != 'p_delete')
            return FALSE;

        if($groups_id == 1)
            return TRUE;

        if($groups_id == 3)
            return FALSE;

        $row = $this->CI_LIB->db->select("p.$column")
                                ->join('controllers c', 'c.id = p.controllers_id', 'left')
                                ->where(array('p.groups_id'=>$groups_id, 'c.name'=>$c_name, "p.$column"=>1))
                                ->get('permissions p')
                                ->row();

        if(!empty($row))
            return $row->{$column};
        else
            return FALSE;
    }

}

/*Ends Acl Library*/