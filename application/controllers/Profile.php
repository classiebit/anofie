<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Profile Controller
 *
 * This class handles feedback sending functionality
 *
 * @package     anofie
 * @author      classiebit
*/
class Profile extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'users_model',
                            'messages_model',
                            'notifications_model',
                        ));
    }

    /**
     * Default
     */
    public function index($username = NULL)
    {
        // validate username
        $username       = validate_username($username);
        if(!$username)
            show_404_custom();
        
        $user                = $this->users_model->get_users_by_username($username);

        if(empty($user))
            show_404_custom();
        
        /* Initialize assets and title */
        $this
        ->set_title($user->fullname)
        ->add_js_theme( "pages/profile/index_i18n.js", TRUE );

        $data           = $this->includes;

        // For profile sharing on social platforms
        $this->meta_url                 = site_url($user->username);
        $this->meta_title               = $user->fullname;
        $this->meta_description         = $this->settings->site_name;
        $this->meta_image               = base_url('upload/general/'.$this->settings->logo_og);

        // filters
        $offset = 0;
        $filters = array();
        $filters['limit'] = $this->pagination_limit;
        $filters['offset'] = $offset;

        // set content data
        $content_data   = array(
            'user'              => $user,
            'count_received'    => $this->messages_model->count_messages($user->id, 'r'),
        );

        // load views
        $data['on_subdomain'] = 1;

        // load views
        $data['content'] = $this->load->view('profile/send_message', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    // send message to user
    public function send_message()
    {
        // sanitize input data
        $_POST                      = $this->security->xss_clean($_POST);
        
        $data                       = array();
        
        $this->form_validation
        ->set_rules('message', lang('menu_message'), 'trim|required|max_length[500]')
        ->set_rules('user_id', lang('menu_user'), 'trim|required|alpha_numeric');
        
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
            
        $messages               = $this->input->post('message');

        $user_id                = (int) $this->input->post('user_id');
        
        $user                   = $this->users_model->get_users_by_id($user_id, FALSE, TRUE);
        if(empty($user))
            $this->json_response([], sprintf(lang('alert_not_found'), lang('menu_user')));

        $message                = nl2br(trim(stripslashes(htmlspecialchars($messages))));

        $data['message']        = $message;
        $data['m_to']           = $user_id;
        $data['m_from']         = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;  
        $data['ip_address']     = $this->input->ip_address();
        $data['added']          = date('Y-m-d H:i:s');
        
        $this->messages_model->save_messages($data);

        // if notification enabled then send email
        if($this->user['notifications'])
        {
            // Email send library
            $this->load->library('make_mail');
            $to      = $user->email; 
            $subject = lang('email_templates_feedback').$this->settings->site_name;
            $message = $message;
            $this->make_mail->send($to, $subject, $message);
        }
        
        // add notification for receiver
        $notification   = array(
            'users_id'  => $user_id,
            'n_type'    => 'messages',
            'n_content' => 'noti_new_message',
        );
        $this->notifications_model->save_notifications($notification);

        // add notification for admin
        $notification   = array(
            'users_id'  => 1,
            'n_type'    => 'messages',
            'n_content' => 'noti_new_message',
        );
        
        $this->notifications_model->save_notifications($notification);

        $this->json_response(['url'=>site_url('message/thankyou')], lang('sm_reply_success'), 1);
    }

    // show thank you page
    public function thankyou()
    {
        // setup page header data
        $this->set_title(lang('sm_thankyou'));

        $data           = $this->includes;

        // load views
        $data['content'] = $this->load->view('profile/thankyou', array(), TRUE);
        $this->load->view($this->template, $data);
    }


   
   
  
    /**
     * search_user
     */
    public function search_user($search = null)
    {
        $search = (string) trim(urldecode($search));
        $search = $this->security->xss_clean($search);

        // min_length & max_length
        if(mb_strlen($search) < 2 || mb_strlen($search) > 64)
            $this->json_response([], validation_errors());

        $users                 = array();
        $users                 = $this->users_model->get_search_users($search, (isset($this->user['id']) ?                                   $this->user['id'] : NULL));

        $data['view']       = $this->load->view('profile/search', ['users'=>$users], TRUE);

        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response($data, null, 1);
    }

}

/* User controller ends */