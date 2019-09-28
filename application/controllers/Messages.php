<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Messages Controller
 *
 * This class handles messages module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Messages extends Private_Controller {

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

    private function common()
    {
        /* Initialize assets and title */
        $this
        ->add_js_theme( "pages/messages/received_i18n.js", TRUE )
        ->add_js_theme( "pages/messages/index_i18n.js", TRUE );

        // setup page header data
        $this->set_title($this->user['fullname']);
        $data           = $this->includes;

        // delete notifications
        $this->notifications_model->delete_notifications('messages', $this->user['id']);
        
        // set content data
        $content_data                                   = $this->get_active_tab();
        $content_data['count_received']                 = $this->messages_model->count_messages($this->user['id'], 'r');
        
        // load views
        $data['content'] = $this->load->view('messages/index', $content_data, TRUE);
        $this->load->view($this->template, $data);
    }

    private function get_active_tab()
    {
        // set active parent and child tabs 
        // based on the active url
        $seg_1 = $this->uri->segment(1);
        $seg_2 = $this->uri->segment(2);
        $seg_3 = $this->uri->segment(3);
        
        // received | sent | favorites
        $parent_tab = ''; 
        $child_tab  = ''; 
        // Received: Messages
        if($seg_1 == 'messages' && $seg_2 == '')
        {
            $parent_tab = 'received';
            $child_tab  = 'messages';
        }
        // Sent: Messages
        else if($seg_1 == 'messages' && $seg_2 == 'sent' && $seg_3 == '') 
        {
            $parent_tab = 'sent';
            $child_tab  = 'messages';
        }
        // Favorites
        else if($seg_1 == 'messages' && $seg_2 == 'favorites') 
        {
            $parent_tab = 'favorites';
        } 
        
        return [
            'parent_tab'    => $parent_tab,
            'child_tab'     => $child_tab,
        ];
    }

    /**
     * Default
     */
    function index()
    {
        $this->common();   
    }

    /*
     * sent
     */

    function sent()
    {
        $this->common();
    }

    /**
     * get favorites messages
     */
    function favorites()
    {
        $this->common();
    }


    
    /******** ------------ APIs  ------------ *******/

    /**
     * get received messages
     */
    function get_received_messages($type = 'r', $offset = 0, $type2 = 0)
    {   
        // validation
        $offset         = (int) $offset;
        $type2          = (int) $type2;
        if($type !== 'r' && $type !== 'f' && $type !== 's')
            $type       = 'r';

        // filters
        $filters                    = array();
        $filters['limit']           = 4;
        $filters['offset']          = $offset;

        $messages                   = array();
        
        
        $messages      = $this->messages_model->get_sent_messages($this->user['id'], $type, $filters, FALSE);
            
        if(empty($messages))
        {
            $data   = array(
                        'messages'  => array(),
                        'offset'    => 0,
                        'more'      => 0  // to stop load more process
                    );
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response($data);
        }
        
        $data                       = array();
        $data['messages']           = $messages;
        $data['offset']             = $offset == 0 ? $filters['limit'] : $filters['limit'] + $offset; 
        $data['more']               = 1;  // to continue load more process
        $data['user']               = $this->user;
        $data['type']               = $type;
        $data['type2']              = $type2;
        $data['type3']              = 1;

        // for received messages
        if($type == 'r')
        {
            $data['view']          = $this->load->view('messages/received', $data, TRUE);
        }

        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response($data, null, 1);
    }

    // get favorite messages
    function get_favorite_messages($type = 'r', $offset = 0, $type2 = 0)
    {   
        // validation
        $offset         = (int) $offset;
        $type2          = (int) $type2;
        if($type !== 'r' && $type !== 'f' && $type !== 's')
            $type       = 'r';

        // filters
        $filters                    = array();
        $filters['limit']           = 4;
        $filters['offset']          = $offset;

        $messages                   = array();
        
        // get sent favorites messages
        $messages      = $this->messages_model->get_sent_messages($this->user['id'], $type, $filters, FALSE);

        if(empty($messages))
        {
            $data       = array(
                            'messages'  => array(),
                            'offset'    => 0,
                            'more'      => 0  // to stop load more process
                        );
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response($data);
        }
        
        $data                       = array();
        $data['messages']           = $messages;
        $data['offset']             = $offset == 0 ? $filters['limit'] : $filters['limit'] + $offset; 
        $data['more']               = 1;  // to continue load more process
        $data['user']               = $this->user;
        $data['type']               = $type;
        $data['type2']              = $type2;
        $data['type3']              = 1;
        
        // for received messages
        $data['view']          = $this->load->view('messages/received', $data, TRUE);

        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response($data, null, 1);
    }

    /**
     * get messages
     */
    function get_sent_messages($type = 'r', $offset = 0, $type2 = 0)
    {   
        // validation
        $offset         = (int) $offset;
        $type2          = (int) $type2;
        if($type !== 'r' && $type !== 'f' && $type !== 's')
            $type       = 'r';

        // filters
        $filters                    = array();
        $filters['limit']           = 4;
        $filters['offset']          = $offset;

        $messages                   = array();
        
        $messages      = $this->messages_model->get_sent_messages($this->user['id'], $type, $filters, FALSE);
        
        if(empty($messages))
        {
            $data       = array(
                            'messages'  => array(),
                            'offset'    => 0,
                            'more'      => 0  // to stop load more process
                        );
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response($data);
        }
        
        $data                       = array();
        $data['messages']           = $messages;
        $data['offset']             = $offset == 0 ? $filters['limit'] : $filters['limit'] + $offset; 
        $data['more']               = 1;  // to continue load more process
        $data['user']               = $this->user;
        $data['type']               = $type;
        $data['type2']              = $type2;
        
        // for received messages
        if($type == 's')
            $data['view']           = $this->load->view('messages/sent', $data, TRUE);
        else
            $data['view']          = $this->load->view('messages/received', $data, TRUE);

        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response($data, null, 1);
    }
  
  
    /******** ------------ ACTIONS ------------ *******/
    /**
     * delete
     */
    public function delete($sent = FALSE)
    {
        if($sent)
            $sent = TRUE;
        else
            $sent = FALSE;

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->messages_model->get_message_by_id($this->user['id'], $id, FALSE, $sent);

        if(empty($result))
        {
            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_not_found') ,lang('menu_message')));
        }

       $flag                   = $this->messages_model->delete_messages($this->user['id'], $result, $sent);

        if($flag)
        {
            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_delete_success') ,lang('menu_message')), 1);
        }
        
        // data=[], msg=null, flag=0, error_fields=[]
        $this->json_response([], sprintf(lang('alert_delete_fail') ,lang('menu_message')));
    }

    /**
     * make favorite
     */
    public function favorite()
    {
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_message')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->messages_model->get_message_by_id($this->user['id'], $id);

        if(empty($result))
        {
            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_not_found') ,lang('menu_message')));
        }

       $flag                   = $this->messages_model->favorite_messages($this->user['id'], $result);

        if($flag)
        {
            // data, msg, flag, error_fields
            $this->json_response(['favorite'=>$result->m_favorite == 1 ? 0 : 1], null, 1);
        }
        
        // for unexpected errors
        // data, msg, flag, error_fields
        $this->json_response([], '<p>'.lang('alert_wrong').'</p>');
    }

    /**
     * report
     */
    public function report()
    {
        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_message')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->messages_model->get_message_by_id($this->user['id'], $id);

        if(empty($result))
        {
            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_not_found') ,lang('menu_message')));
        }

       $flag                   = $this->messages_model->report_messages($this->user['id'], $result);

        if($flag)
        {
            // data=[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_report_success') ,lang('menu_message')), 1);
        }
        
        // data=[], msg=null, flag=0, error_fields=[]
        $this->json_response([], sprintf(lang('alert_report_fail') ,lang('menu_message')));
    }


}

/* Messages controller ends */