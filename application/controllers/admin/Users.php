<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Users Controller
 *
 * This class handles users module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Users extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('users_model');
        

         // Page Title
        $this->set_title( lang('menu_users') );
    }

    /**
     * index
     */
    function index()
    {
        $this->acl->check_access('users', 'p_view');

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('users_fullname'),
                                lang('users_username'),
                                lang('users_email'),
                                lang('common_updated'),
                                lang('common_status'),
                                lang('action_action'),
                            );
        // load views
        $content['content'] = $this->load->view('admin/index', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * ajax_list
    */
    public function ajax_list()
    {
        $this->load->library('datatables');

        $table              = 'users';        
        $columns            = array(
                                "$table.id",
                                "$table.fullname",
                                "$table.username",
                                "$table.email",
                                "$table.active",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.fullname",
                                "$table.username",
                                "$table.email",
                                "$table.date_updated",
                                "$table.active",
                            );
        $columns_search     = array(
                                'fullname',
                                'username',
                                'email',
                            );
        $order              = array('date_updated' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $this->input->post('start');

        
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->fullname;
            $row[]          = $val->username;
            $row[]          = $val->email;
            $row[]          = date('g:iA d/m/y ', strtotime($val->date_updated));
            $row[]          = status_switch($val->active, $val->id);
            $row[]          = action_buttons('users', $val->id, $val->fullname, lang('menu_user'));
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $this->input->post('draw'),
                                "recordsTotal"      => $this->datatables->count_all(),
                                "recordsFiltered"   => $this->datatables->count_filtered(),
                                "data"              => $data,
                            );
        
        //output to json format
        echo json_encode($output);exit;
    }

    /**
     * form
    */
    public function form($id = NULL)
    {
        // check access
        if($id)
            $this->acl->check_access('users', 'p_edit');
        else
            $this->acl->check_access('users', 'p_add');

        /* Initialize assets */
        $data                       = $this->includes;
        
        $id                         = (int) $id;

        // in case of edit
        if($id)
        {
            $result                 = $this->users_model->get_users_by_id($id);

            if(empty($result))
            {
                item_not_found('user');
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            
            // current image & icon
            $data['c_image']        = $result->image;
        }
        
        $data['fullname'] = array(
            'name'      => 'fullname',
            'id'        => 'fullname',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('fullname', !empty($result->fullname) ? $result->fullname : ''),
        );
        $data['username'] = array(
            'name'      => 'username',
            'id'        => 'username',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('username', !empty($result->username) ? $result->username : ''),
        );
        $data['email'] = array(
            'name'      => 'email',
            'id'        => 'email',
            'type'      => 'email',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('email', !empty($result->email) ? $result->email : ''),
        );
        $data['password'] = array(
            'name'      => 'password',
            'id'        => 'password',
            'type'      => 'password',
            'class'     => 'form-control',
        );
        $data['password_confirm'] = array(
            'name'      => 'password_confirm',
            'id'        => 'password',
            'type'      => 'password',
            'class'     => 'form-control',
        );
        $data['image'] = array(
            'name'      => 'image',
            'id'        => 'image',
            'type'      => 'file',
            'accept'    => 'image/*',
            'class'     => 'form-control',
        );
        
        if($id) // only in case of editing
        {
            /*Get groups*/
            $result_group       = $this->ion_auth->get_users_groups($result->id)->result_array();
            $groups             = $this->ion_auth->groups()->result_array();
          
            // if its admin 
            if($this->ion_auth->is_admin($id))
            {
                foreach($groups as $val)
                    if($val['id'] <= 1) // super admin group cannot be changed
                        $data['group'][$val['id']] = $val['name'];
            }
            else
            {
                foreach($groups as $val)
                    if($val['id'] > 1) // exclude admin group (super admin can be only one)
                        $data['group'][$val['id']] = $val['name'];
            }
            
            
            $data['groups']     = array(
                'name'          => 'groups',
                'id'            => 'groups',
                'class'         => 'form-control show-tick text-capitalize',
                'options'       => $data['group'],
                'selected'      => $this->form_validation->set_value('groups', !empty($result_group) ? $result_group[0]['id'] : 3),
            );    
        }

        $data['status']     = array(
            'name'          => 'active',
            'id'            => 'active',
            'class'         => 'form-control show-tick',
            'options'       => array(
                                    '0' => lang('common_status_inactive'),
                                    '1' => lang('common_status_active'),
                                ),
            'selected'      => $this->form_validation->set_value('active', !empty($result->active) ? $result->active : 0),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/users/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);

        $id             = (int) $this->input->post('id');

        // check access
        if($id)
            $this->acl->check_access('users', 'p_edit', TRUE);
        else
            $this->acl->check_access('users', 'p_add', TRUE);

        // Unique columns
        $result             = (object) array();
        $result->username   = '';
        $result->email      = '';

        if($id)
        {
            // users can only edit non-admins
            if(!$this->ion_auth->is_admin() && !$this->ion_auth->is_admin($id))
            {
                // data =[], msg=null, flag=0, error_fields=[]
                $error = lang('users_only_admin_can');
                $this->json_response([], $error);
            }   

            $result                 = $this->users_model->get_users_by_id($id);

            if(empty($result))
            {
                $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_user')));
                // data =[], msg=null, flag=0, error_fields=[]
                $this->json_response([], $this->session->flashdata('message'));
            }
        }
        
        // validators
        $this->form_validation
        ->set_rules('username', lang('users_username'), 'trim|required|min_length[3]|max_length[254]|alpha_numeric')
        ->set_rules('email', lang('users_email'), 'trim|required|max_length[254]|valid_email')
        ->set_rules('fullname', lang('users_fullname'), 'required|trim|min_length[2]|max_length[256]');
        
        /*Validate password*/
        if($id)
        {
            $this->form_validation
            ->set_rules('password', lang('users_password'), 'trim|min_length[8]|matches[password_confirm]')
            ->set_rules('password_confirm', lang('users_password_confirm'), 'trim|matches[password]');
        }
        else
        {
            $this->form_validation
            ->set_rules('password', lang('users_password'), 'trim|min_length[8]|matches[password_confirm]|required')
            ->set_rules('password_confirm', lang('users_password_confirm'), 'trim|matches[password]|required');
        }
            
        /*Validate email & username for duplicacy*/
        if($this->input->post('username') !== $result->username)
            $this->form_validation->set_rules('username', lang('users_username'), 'trim|required|min_length[3]|max_length[64]|is_unique[users.username]|alpha_numeric');

        if($this->input->post('email') !== $result->email)
            $this->form_validation->set_rules('email', lang('users_email'), 'trim|required|max_length[128]|valid_email|is_unique[users.email]');

        // in demo mode, don't update profile pic
        if(DEMO_MODE)
        {
            $_FILES = [];
        }

        if(! empty($_FILES['image']['name'])) // if image 
        {
            $file_image         = array('folder'=>'users/images', 'input_file'=>'image');
            // Remove old image
            if($id)
                $this->file_uploads->remove_file('./upload/'.$file_image['folder'].'/', $result->image);
            // update users image      
                  
            $filename_image     = $this->file_uploads->upload_file($file_image);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }
        
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

        // insert data
        $data                   = array();
        $data['fullname']       = $this->input->post('fullname');
        $data['date_updated']       = date('Y-m-d H:i:s');
        
        if(!$id)
            $data['date_added']     = date('Y-m-d H:i:s');

        $username               = strtolower($this->input->post('username'));
        $email                  = strtolower($this->input->post('email'));
        
        if(! empty($filename_image) && ! isset($filename_image['error']))
            $data['image']      = $filename_image;
        
        if(!$id) // register only in case of creating user
        {
            $password           = $this->input->post('password');
            $flag               = $this->ion_auth->register($username, $password, $email, $data);
        }
        else // the user update process of ion auth
        {
            // only admin and owner of account can edit account
            if ( (!$this->ion_auth->is_admin() && !($this->ion_auth->user()->row()->id == $id) ) ) 
            {
                $this->session->set_flashdata('error', lang('users_update_only_admin_owner'));
                // data =[], msg=null, flag=0, error_fields=[]
                $this->json_response([], $this->session->flashdata('error'));
            }

            $currentGroups      = $this->ion_auth->get_users_groups($id)->result();

            // Only allow updating groups if user is admin
            if ($this->ion_auth->is_admin())
            {
                //Update the groups user belongs to
                $groupData      = (int) $this->input->post('groups');

                // one admin only
                if($groupData === 1)
                    $groupData = 3;

                // admin will remain admin always
                if($this->ion_auth->is_admin($id))
                    $groupData = 1;

                if (isset($groupData) && !empty($groupData)) 
                {
                    $this->ion_auth->remove_from_group('', $id);

                    $g_name = $this->users_model->get_group_name($groupData)->name;

                    $data['active']         = $this->input->post('active');

                    $this->ion_auth->add_to_group($groupData, $id);
                }

                if($this->input->post('password'))
                {
                    $data['password']       = $this->input->post('password');
                }
            }

            $data['email']      = $email;
            $data['username']   = $username;

            // in demo mode, don't update email, username & password
            if(DEMO_MODE)
            {
                unset($data['email']);
                unset($data['username']);
                unset($data['password']);
            }

            $flag               = $this->ion_auth->update($id, $data);
        }

        if($flag)
        {
            // add batch notification when new batch inserted
            if(!$id)
            {
                $notification   = array(
                    'users_id'  => $this->user['id'],
                    'n_type'    => 'users',
                    'n_content' => 'noti_new_users',
                );
                $this->notifications_model->save_notifications($notification);    
            }

            if($id)
                $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_user')));
            else
                $this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_user')));

            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], null, 1);
        }
        
        if($id)
            $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_user')));
        else
            $this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_user')));

        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response();
    }

     /**
     * view
     */
    public function view($id = NULL)
    {
        $this->acl->check_access('users', 'p_view');

        /* Initialize assets */
        $data                   = $this->includes;
        
        /* Get Data */
        $id = (int) $id;
        $result = $this->users_model->get_users_by_id($id);

        if(empty($result))
        {
            item_not_found('user');
        }

        $data['users']         = $result;
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/users/view', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * status_update
     */
    public function status_update()
    {
        $this->acl->check_access('user', 'p_edit', TRUE);

        /* Validate form input */
        $this->form_validation
        ->set_rules('id', sprintf(lang('alert_id'), lang('menu_user')), 'required|numeric')
        ->set_rules('status', lang('common_status'), 'required|in_list[0,1]');

        if($this->form_validation->run() === FALSE)
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = validation_errors();
            $this->json_response([], $error);
        }

        // data to insert in db table
        $data                       = array();
        $id                         = (int) $this->input->post('id');
        $data['active']             = (int) $this->input->post('status');

        // users can only edit non-admins
        if(!$this->ion_auth->is_admin() && !$this->ion_auth->is_admin($id))
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_only_admin_can');
            $this->json_response([], $error);
        }   

        if($id === 1)
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_super_admin_no_status');
            $this->json_response([], $error);
        }

        // skip this action in demo mode
        if(DEMO_MODE)
        {
            $error = lang('common_demo');
            $this->json_response([], $error);
        }

        if(empty($id))
        {
            $this->session->set_flashdata('message', sprintf(lang('alert_not_found'), lang('menu_user')));
            $this->json_response([], $this->session->flashdata('message'));
        }
        
        $flag                       = $this->users_model->save_users($data, $id);

        if($flag)
        {
            $this->json_response([], sprintf(lang('alert_status_success'), lang('menu_user')), 1);
        }

        $this->json_response([], sprintf(lang('alert_status_fail'), lang('menu_user')));
    }

    /**
     * delete
     */
    public function delete($id = NULL)
    {
        $this->acl->check_access('user', 'p_delete', TRUE);

        if(!$this->ion_auth->is_admin())
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_only_admin_can');
            $this->json_response([], $error);
        }

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_user')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->users_model->get_users_by_id($id);

        if($id === 1)
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_super_admin_no_delete');
            $this->json_response([], $error);
        }

        if(empty($result))
        {
            $this->json_response([], sprintf(lang('alert_not_found') ,lang('menu_user')));
        }

        // skip this action in demo mode
        if(DEMO_MODE)
        {
            $error = lang('common_demo');
            $this->json_response([], $error);
        }

        $flag                   = $this->ion_auth->delete_user($id);

        if($flag)
        {
            
            // Remove image
            if(!empty($result->image))
                $this->file_uploads->remove_file('./upload/users/images/', $result->image);
            
            $this->json_response([], sprintf(lang('alert_delete_success'), lang('menu_user')), 1);
        }
        
        $this->json_response([], sprintf(lang('alert_delete_fail'), lang('menu_user')));
    }
    
}

/* Users controller ends */