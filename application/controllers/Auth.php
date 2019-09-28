<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Auth Controller
 *
 * This class handles login functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Auth extends Public_Controller 
{
    private $redirect_to = 'messages';

    /**
     * Constructor
      */
    public function __construct()
    {
        parent::__construct();
        
        // if user is logged in, redirect to home page
        // redirect except logout
        if($this->uri->segment(1) != 'logout')
            if ($this->ion_auth->logged_in())
                redirect($this->redirect_to);

        // load the users model
        $this->load->model('users_model');
        $this->load->model('notifications_model');

        // Load Ion auth package files
        $this->load->library(['ion_auth']);
        $this->lang->load('auth');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
    }

    /**
     * index
     * always redirect to login
      */
    public function index()
    {
        redirect('login');
    }

    /**
     * login
     * show login page
      */
    public function login()
    {
        // setup page header data
        $this
        ->add_js_theme( "pages/auth/index_i18n.js", TRUE )
        ->set_title(lang('users_login'));
        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view('auth/login', NULL, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * do_login
     * login user using axios
      */
	public function do_login()
	{
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);

        //validate form input
		$this->form_validation->set_rules('identity', str_replace(':', '', $this->lang->line('login_identity_label')), 'trim|required');
		$this->form_validation->set_rules('password', str_replace(':', '', $this->lang->line('login_password_label')), 'trim|required');

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
                if(form_error($key))
                    $error_fields[] = $key;
            
            // data, msg, flag, error_fields
            $this->json_response([], validation_errors(), 0, $error_fields);
        }
	
        // check to see if the user is logging in
        // check for "remember me"
        // $remember = (bool) $this->input->post('remember');
        $remember = false;

        if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember))
        {
            //if the login is successful
            //redirect them back to the home page
            // after adding user data into session
            $this->login_helper($_SESSION['user_id']);
        }
        
        // if the login was un-successful
        // data, msg, flag, error_fields
        $this->json_response([], $this->ion_auth->errors(), 0);
	}

    private function login_helper($user_id = null, $redirect = FALSE)
    {
        $this->session->set_flashdata('message', $this->ion_auth->messages());
            
        $result                     = $this->users_model->get_users_by_id($user_id, TRUE);

        // get user group
        $user_group                 = $this->ion_auth->get_users_groups($user_id)->row();

        /* THIS IS FALLBACK CONDITION (ONLY 1% chances) */
        // if user is accidently not assigned to any group
        // then assume his/her default group as 3
        // and assign that user to a default group
        if(empty($user_group))
        {
            $_SESSION['groups_id']   = 3;
            $this->ion_auth->add_to_group(3, $user_id);
        }
        else
        {
            $_SESSION['groups_id']   = $user_group->id;
        }

        // ion auth session data
        $_SESSION['identity']       = $result['email'];
        $_SESSION['email']          = $result['email'];
        $_SESSION['user_id']        = $user_id;
            
        $this->session->set_userdata('logged_in', $result);

        // if non json call then redirect
        if($redirect)
            redirect();

        // data, msg, flag, error_fields
        $this->json_response([], $this->ion_auth->messages(), 1);
    }

    private function register_helper($data = [], $redirect = FALSE)
    {
        $username           = $data['username'];
        $email              = $data['email'];
        $password           = $data['password'];
        $additional_data    = $data['additional_data'];

        $new_user_id        = $this->ion_auth->register($username, $password, $email, $additional_data);

        if ($new_user_id)
        {
            // send registration email
            $data = array(
                'identity'   => $email,
                'title'      => $this->config->item('sender_name', 'ion_auth'),
            );
            $message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);
            
            // Email send library
            $this->load->library('make_mail');
            $to      = $email; 
            $subject = $this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject');
            $message = $message;
            $this->make_mail->send($to, $subject, $message);

            // if registration successful
            // add new notification for admin
            $notification   = array(
                'users_id'  => $new_user_id,
                'n_type'    => 'users',
                'n_content' => 'noti_new_users',
            );
            $this->notifications_model->save_notifications($notification);

            //if the register is successful
            //redirect them back to the home page
            // after adding user data into session
            $this->login_helper($new_user_id, $redirect);
        }
    }

    /**
     * register
     * show register page
      */
    public function register()
    {
        // setup page header data
        $this
        ->add_js_theme( "pages/auth/index_i18n.js", TRUE )
        ->set_title(lang('users_login'));
        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view('auth/register', NULL, TRUE);
        $this->load->view($this->template, $data);
    }

    /**
     * register the user
     */
    public function do_register()
    {
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);
        
        // validators
        $this->form_validation
        ->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'))
        ->set_rules('username', lang('users_username'), 'required|trim|min_length[3]|max_length[64]|alpha_numeric|callback__check_username')
        ->set_rules('fullname', lang('users_fullname'), 'required|trim|min_length[2]|max_length[256]')
        ->set_rules('accept', lang('users_register_terms'), 'required')
        ->set_rules('email', lang('users_email'), 'required|trim|max_length[256]|valid_email|callback__check_email')
        ->set_rules('password', lang('users_password'), 'required|trim|min_length[8]');

        if($this->form_validation->run() === FALSE)
        {
            // for fetching specific fields errors in order to view errors on each field seperately
            $error_fields = array();
            foreach($_POST as $key => $val)
                if(form_error($key))
                    $error_fields[] = $key;
            
            // data, msg, flag, error_fields
            $this->json_response([], validation_errors(), 0, $error_fields);
        }
        
        $data['username']                       = strtolower($this->input->post('username'));
        $data['email']                          = strtolower($this->input->post('email'));
        $data['password']                       = $this->input->post('password');
        $data['additional_data']                = array(
                                                    'fullname'    => $this->input->post('fullname'),
                                                );

        // from here user will be registered and login automatically
        $this->register_helper($data);
        
        // if the register was un-successful
        // data, msg, flag, error_fields
        $this->json_response([], $this->ion_auth->errors(), 0);
    }

    public function do_check_username()
    {
        $this->form_validation
        ->set_rules('username', lang('users_username'), 'required|trim|min_length[3]|max_length[64]|alpha_numeric|callback__check_username');

        if($this->form_validation->run() === false)
        {
            // data, msg, flag, error_fields
            $error_fields[] = "username";
            $this->json_response([], validation_errors(), 0, $error_fields);
        }

        // data, msg, flag, error_fields
        $this->json_response([], null, 1);
    }

    // log the user out
    public function logout()
    {
        // log the user out
        $this->ion_auth->logout();

        $this->session->sess_destroy();

        // redirect them to the login page
        $this->session->set_flashdata('message', $this->ion_auth->messages());
        redirect('');
    }

       // forgot password
    public function forgot_password()
    {
        // setup page header data
        $this->set_title( lang('users_forgot') );
        $data = $this->includes;

        // setting validation rules by checking whether identity is username or email
        $this->form_validation->set_rules('identity', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');

        if ($this->form_validation->run() == false)
        {
            // load views
            $data['content'] = $this->load->view('auth/forgot_password', NULL, TRUE);
            $this->load->view($this->template, $data);
        }
        else
        {
            $identity        = $this->ion_auth->where('email', $this->input->post('identity'))->users()->row();

            if(empty($identity)) 
            {
                $this->ion_auth->set_error('forgot_password_email_not_found');

                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect("auth/forgot_password");
            }

            // run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->email);

            if (!empty($forgotten))
            {   
                $data = array(
					'identity'		=> $identity->email,
					'forgotten_password_code' => $forgotten['forgotten_password_code']
				);

                $message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_forgot_password', 'ion_auth'), $data, true);

                // fallback email send
                $this->load->library('make_mail');
                $to 	 = $identity->email; 
                $subject = $this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject');
                $message = $message;
                $this->make_mail->send($to, $subject, $message);
                
                // if there were no errors
                $this->session->set_flashdata('message', lang('forgot_password_successful'));
                redirect("auth/login"); //we should display a confirmation page here instead of the login page
            }
            else
            {
                $this->session->set_flashdata('error', $this->ion_auth->errors());
                redirect("auth/forgot_password");
            }
        }
    }

    // reset password - final step for forgotten password
    public function reset_password($code = NULL)
    {
        if (!$code)
        {
            show_404();
        }

        // setup page header data
        $this->set_title( lang('reset_password_heading') );
        $data = $this->includes;

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user)
        {
            // if the code is valid then display the password reset form
            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false)
            {
                // display the form
                $this->data['user_id'] = array(
                    'name'  => 'user_id',
                    'id'    => 'user_id',
                    'type'  => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;

                // load views
                $data['content'] = $this->load->view('auth/reset_password', $this->data, TRUE);
                $this->load->view($this->template, $data);
            }
            else
            {
                // do we have a valid request?
                if ($user->id != $this->input->post('user_id'))
                {
                    // something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                }
                else
                {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change)
                    {
                        // if the password was successfully changed
                        $this->session->set_flashdata('message', $this->ion_auth->messages());
                        redirect("auth/login");
                    }
                    else
                    {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code);
                    }
                }
            }
        }
        else
        {
            // if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password");
        }
    }

    /**
     * Make sure username is available
     *
     * @param  string $username
     * @return int|boolean
     */
    function _check_username($username)
    {
        if ($this->users_model->username_exists($username) && !empty($username))
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
        if ($this->users_model->email_exists($email) && !empty($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users_email_error'), $email));
            return FALSE;
        }
        else
        {
            return $email;
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key   = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

}
