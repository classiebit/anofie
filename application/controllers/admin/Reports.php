<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reports Controller
 *
 * This class handles contacts module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Reports extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model('messages_model');

        // Page Title
        $this->set_title( lang('menu_reports') );
    }


   /**
     * index
     */
    function index()
    {
        // check access
        $this->acl->check_access('reports', 'p_view');

        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('sm_reply_sender'),
                                lang('sm_reply_receiver'),
                                lang('sm_reported_message'),
                                lang('common_added'),
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

        $table              = 'messages';        
        $columns            = array(
                                "$table.id",
                                "$table.m_from",
                                "$table.m_to",
                                "$table.message",
                                "$table.added",
                                "$table.ip_address",
                                "(SELECT u.username FROM users u WHERE u.id = $table.m_from) username",
                                "(SELECT r.username FROM users r WHERE r.id = $table.m_to) receiver",
                            );
        $columns_order      = array(
                                "#",
                                "$table.m_from",
                                "$table.m_to",
                                "$table.message",
                                "$table.added",
                            );
        $columns_search     = array(
                                'message',
                            );
        $order              = array('added' => 'DESC');
        $where              = array('m_flag' => 1);
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order, $where);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->m_from ? '<a href="'.site_url('admin/users/form/').$val->m_from.'" target="_blank">'.$val->username.'</a>' : $val->ip_address;
            $row[]          = '<a href="'.site_url('admin/users/form/').$val->m_to.'" target="_blank">'.$val->receiver.'</a>';
            $row[]          = mb_substr($val->message, 0, 30, 'utf-8');
            $row[]          = date('g:iA j/m/y ', strtotime($val->added));
            $row[]          = action_buttons('reports', $val->id, mb_substr($val->message, 0, 50, 'utf-8'), lang('menu_report'), $val);
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $_POST['draw'],
                                "recordsTotal"      => $this->datatables->count_all($where),
                                "recordsFiltered"   => $this->datatables->count_filtered($where),
                                "data"              => $data,
                            );
        
        //output to json format
        echo json_encode($output);exit;
    }

    /**
     * delete
    */
    public function delete($id = NULL)
    {   
        // check access
        $this->acl->check_access('reports', 'p_delete');

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_report')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->messages_model->get_message_by_id(NULL, $id, TRUE);

        if(empty($result))
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = sprintf(lang('alert_not_found'), lang('menu_report'));
            $this->json_response([], $error);
        }

        $flag                   = $this->messages_model->delete_messages_permanent($id, $result);

        if($flag)
        {
            $message = sprintf(lang('alert_delete_success'), lang('menu_report'));
            $this->session->set_flashdata('message', $message);
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], null, 1);
        }
        
        $error = sprintf(lang('alert_delete_fail'), lang('menu_report'));
        
        $this->session->set_flashdata('error', $error);
        $this->json_response();
    }

}

/* Reports controller ends */