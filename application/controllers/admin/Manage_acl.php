<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Manage_acl Controller
 *
 * This class handles acl module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Manage_acl extends Admin_Controller {
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        
        $this->load->model('manage_acl_model');

         // Page Title
        $this->set_title(lang('menu_manage_acl'));
    }

    /**
     * index
     */
    function index($group = NULL)
    {
        /* Initialize assets */
        $data           = $this->includes;

        $group               = (int) $group;
        if(!empty($group))
            $result_group    = $this->ion_auth->group($group)->row_array();

        $data['controllers'] = $this->manage_acl_model->get_controllers();
        $data['group_id']    = !empty($result_group) ? $result_group['id'] : 2;
        
        $groups             = $this->ion_auth->groups()->result_array();
        foreach($groups as $val)
            if($val['id'] != 1 && $val['id'] != 3) // skip Members and admin
                $data['group'][$val['id']] = $val['name'];
        
        $data['groups']     = array(
            'name'          => 'groups',
            'id'            => 'groups',
            'class'         => 'form-control show-tick text-capitalize manage_acl_groups',
            'options'       => $data['group'],
            'selected'      => $this->form_validation->set_value('groups', !empty($result_group) ? $result_group['id'] : 2),
        );

        $data['permissions'] = $this->manage_acl_model->get_group_permissions((!empty($result_group) ? $result_group['id'] : 2));

        foreach($data['permissions'] as $val)
        {
            $k = $this->get_array_key($data['controllers'], 'id', $val['controllers_id']);
            
            $data['p'][''.$data['controllers'][$k]['name'].'_add']       = $val['p_add'];
            $data['p'][''.$data['controllers'][$k]['name'].'_edit']      = $val['p_edit'];
            $data['p'][''.$data['controllers'][$k]['name'].'_view']      = $val['p_view'];
            $data['p'][''.$data['controllers'][$k]['name'].'_delete']    = $val['p_delete'];
        }

        $data['add']        = ['users'];
        $data['edit']       = ['users', 'settings', 'pages'];
        $data['delete']     = ['users', 'pages', 'reports', 'messages', 'contacts'];


        // load views
        $content['content'] = $this->load->view('admin/manage_acl/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    private function get_array_key($array, $field, $value)
    {
        foreach($array as $key => $val)
          if ( $val[$field] === $value )
             return $key;

       return false;
    }

    /**
     * save
    */
    public function save()
    {
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);

        // users can only edit non-admins
        if(!$this->ion_auth->is_admin())
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_only_admin_can');
            $this->json_response([], $error);
        }   

        /* Validate form input */
        $this->form_validation
        ->set_rules('groups', lang('manage_acl_select_group'), 'trim|required|is_natural_no_zero');

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
                if(form_error($key))
                    $error_fields[] = $key;
            
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response(['error_fields' => json_encode($error_fields)], validation_errors());
        }

        $group_id                       = (int) $this->input->post('groups');
        if($group_id === 1 || $group_id === 3)
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('manage_acl_permissions')));
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], null, 1);
        }

        $data                               = array();
        $controllers                        = $this->manage_acl_model->get_controllers();
        foreach($controllers as $key => $val)
        {
            $data[$key]['controllers_id']   = $val['id'];
            $data[$key]['groups_id']        = $group_id;
            $data[$key]['p_add']            = isset($_POST[''.$val['name'].'_add']) ? 1 : 0;
            $data[$key]['p_edit']           = isset($_POST[''.$val['name'].'_edit']) ? 1 : 0;
            $data[$key]['p_view']           = isset($_POST[''.$val['name'].'_view']) ? 1 : 0;
            $data[$key]['p_delete']         = isset($_POST[''.$val['name'].'_delete']) ? 1 : 0;
        }

        $flag                               = $this->manage_acl_model->save_permissions($data, $group_id);

        $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('manage_acl_permissions')));
        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response([], null, 1);
    }


}

/* Manage_acl controller ends */