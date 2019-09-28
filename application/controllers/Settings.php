<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Controller
 *
 * This class handles settings module functionality
 *
 * @package     anofie
 * @author      classiebit
*/
class Settings extends Private_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model(array(
                            'users_model',
                            'messages_model',
                        ));
        $this->load->library('file_uploads');
    }


    /**
	 * Setting Edit
     */
	function index()
	{
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);

        // validators
        $this->form_validation
        ->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'))
        ->set_rules('fullname', lang('users_fullname'), 'required|trim|min_length[2]|max_length[256]')
        ->set_rules('email', lang('users_email'), 'required|trim|max_length[128]|valid_email|callback__check_email')
        ->set_rules('username', lang('users_username'), 'required|trim|min_length[3]|max_length[64]|alpha_numeric|callback__check_username')
        ->set_rules('password', lang('users_password'), 'trim|min_length[8]');
        
        if($this->input->post('password'))
        {
            $this->form_validation
            ->set_rules('password_confirm', lang('users_password_confirm'), 'trim|required|matches[password]')
            // check old password first
            ->set_rules('password_current', lang('users_password_current'), 'trim|required');
        }
            
        // do not upload pic in demo mode
        if(DEMO_MODE)
        {
            $_FILES = [];
        }

        // upload users image
        $filename_image = null;
        if(! empty($_FILES['image']['name']) ) // if image 
        {
            $file_image         = array('folder'=>'users/images', 'input_file'=>'image');
            // update users image
            $filename_image     = $this->file_uploads->upload_file($file_image, 512, 512, 256, 256);
            // through image upload error
            if(!empty($filename_image['error']))
                $this->form_validation->set_rules('image_error', lang('common_image'), 'required', array('required'=>$filename_image['error']));
        }

        if ($this->form_validation->run() === TRUE)
        {
            // save the changes
            $data                       = array();

            // upload image only if selected
            if(!empty($filename_image) && !isset($filename_image['error']))
                $data['image']          = $filename_image;
            

            $data['fullname']           = $this->input->post('fullname');
            $data['email']              = $this->input->post('email');
            $data['username']           = $this->input->post('username');
            $data['notifications']      = isset($_POST['notifications']) ? 1 : 0;

            // in demo mode, don't update email, username & password
            if(DEMO_MODE)
            {
                unset($data['email']);
                unset($data['username']);
                $_POST['password'] = null;
            }

            $saved                      = $this->ion_auth->update($this->user['id'], $data);

            $is_changed = true;
            if ($saved)
            {
                // reload the new user data and store in session
                $result = $this->users_model->get_users_by_id($this->user['id'], TRUE);
                $this->session->set_userdata('logged_in', $result);

                // in case of changing password
                // check old and then change password
                
                if($this->input->post('password'))
                {
                    $old = $this->input->post('password_current');
                    $new = $this->input->post('password');
                    
                    $is_changed = $this->ion_auth->change_password($result['email'], $old, $new);
                }
                    
                if($is_changed)    
                    $this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('action_profile')));
                else
                    $this->session->set_flashdata('error', lang('users_password_update_fail'));
            }   
            
            if(!$saved && $is_changed)
                $this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('action_profile')));
            
            // reload page and display message
            redirect('settings');
        }

        // setup page header data
        $this
        ->add_js_theme( "pages/settings/index_i18n.js", TRUE );
		
        $this->set_title( lang('menu_user').' '.lang('action_profile'));
        $data = $this->includes;
      
        // set content data
        $content_data = array(
            'cancel_url'        => base_url(),
            'user'              => $this->user,
        );

        // load views
        $data['content'] = $this->load->view('settings/settings', $content_data, TRUE);
        $this->load->view($this->template, $data);
	}

    /**
     * delete_account
     */
    public function delete_account()
    {
        // skip admin 
        if(1 === (int) $this->user['id'])
        {
            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], lang('users_super_admin_no_delete'));
        }

        // skip this action in demo mode
        if(DEMO_MODE)
        {
            $error = lang('common_demo');
            $this->json_response([], $error);
        }
        
        $data                   = [
            'active'        => 0,
            'is_deleted'    => 1,
        ];

        $flag                   = $this->ion_auth->update($this->user['id'], $data);
    
        if($flag)
        {
            // log the user out
            $logout = $this->ion_auth->logout();
            $this->session->sess_destroy();

            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_delete_success'), lang('menu_user')), 1);
        }

        // data=[], msg=null, flag=0, error_fields=[]
        $this->json_response([], sprintf(lang('alert_delete_fail'), lang('menu_user')));
    }

    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/

    /**
     * Make sure username is available
     *
     * @param  string $username
     * @return int|boolean
     */
    function _check_username($username)
    {
        if (trim($username) != $this->user['username'] && $this->users_model->username_exists($username))
        {
            $this->form_validation->set_message('_check_username', sprintf(lang('users_username_error'), $username));
            return FALSE;
        }
        else
        {
            return $username;
        }
    }


    /**
     * Make sure email is available
     *
     * @param  string $email
     * @return int|boolean
     */
    function _check_email($email)
    {
        if (trim($email) != $this->user['email'] && $this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users_email_error'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }

}

/*End User Profile*/