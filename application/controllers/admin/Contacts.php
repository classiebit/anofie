<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contacts Controller
 *
 * This class handles contacts module functionality
 *
 * @package     classiebit
 * @author      prodpk
*/

class Contacts extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model('contact_model');

        // Page Title
        $this->set_title( lang('menu_contacts') );
    }


   /**
     * index
     */
    function index()
    {
        /* Initialize assets */
        $this->include_index_plugins();
        $this->add_js_theme( "pages/contacts/contact.js");
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('contacts_name'),
                                lang('users_email'),
                                lang('contacts_title'),
                                lang('common_added'),
                                lang('action_read'),
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

        $table              = 'contacts';        
        $columns            = array(
                                "$table.id",
                                "$table.name",
                                "$table.email",
                                "$table.title",
                                "$table.message",
                                "$table.created",
                                "$table.read",
                                "$table.read_by",
                            );
        $columns_order      = array(
                                "#",
                                "$table.name",
                                "$table.email",
                                "$table.title",
                                "$table.created",
                            );
        $columns_search     = array(
                                'name',
                                'email',
                                'title',
                            );
        $order              = array('created' => 'DESC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = mb_substr($val->name, 0, 30, 'utf-8');
            $row[]          = $val->email;
            $row[]          = mb_substr($val->title, 0, 30, 'utf-8');
            $row[]          = date('g:iA j/m/y ', strtotime($val->created));
            $row[]          = modal_contact($val);
            $row[]          = action_buttons('contacts', $val->id, '', lang('menu_contact'));
            $data[]         = $row;
        }
 
        $output             = array(
                                "draw"              => $_POST['draw'],
                                "recordsTotal"      => $this->datatables->count_all(),
                                "recordsFiltered"   => $this->datatables->count_filtered(),
                                "data"              => $data,
                            );
        
        //output to json format
        echo json_encode($output);exit;
    }

    /**
     * Marks email message as read
     *
     * @param  int $id
     * @return boolean
    */
    public function read()
    {
        $id = (int) $this->input->post('id');

        if ($id)
        {
            $read = $this->contact_model->read($id, $this->user['id']);

            if ($read)
            {
                $results['success'] = sprintf(lang('alert_update_success'), lang('menu_contact'));
            }
            else
            {
                $results['error'] = sprintf(lang('alert_update_fail'), lang('menu_contact'));
            }
        }
        else
        {
            $results['error'] = sprintf(lang('alert_update_fail'), lang('menu_contact'));
        }

        $this->json_response([], null, 1);
    }

    /**
     * delete
     */
    public function delete($id = NULL)
    {
        // check access
        $this->acl->check_access('contacts', 'p_delete', TRUE);

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_contact')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->contact_model->get_contacts_by_id($id);

        if(empty($result))
        {
           // data =[], msg=null, flag=0, error_fields=[]
            $error = sprintf(lang('alert_not_found'), lang('menu_contact'));
            $this->json_response([], $error);
        }

        $flag                   = $this->contact_model->delete_contacts($id, $result);

        if($flag)
        {
            $message = sprintf(lang('alert_delete_success'), lang('menu_contact'));
            $this->session->set_flashdata('message', $message);
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], null, 1);
        }
        
        $error = sprintf(lang('alert_delete_fail'), lang('menu_contact'));
        
        $this->session->set_flashdata('error', $error);
        $this->json_response();
    }

}

/* Contacts controller ends */