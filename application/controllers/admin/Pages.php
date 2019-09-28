<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pages Controller
 *
 * @package     anofie
 * @author      classiebit
*/


class Pages extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('pages_model');

        // Page Title
        $this->set_title( lang('menu_pages') );
    }

    /**
     * index
     */
    function index()
    {
        // check access
        $this->acl->check_access('pages', 'p_view');

         /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('menu_page'),
                                lang('common_title'),
                                lang('common_updated'),
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

        $table              = 'pages';        
        $columns            = array(
                                "$table.id",
                                "$table.page",
                                "$table.title",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.page",
                                "$table.title",
                                "$table.date_updated",
                            );
        $columns_search     = array('page', 'title');
        $order              = array('id' => 'ASC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->page;
            $row[]          = $val->title;
            $row[]          = date('d/M/y g:iA', strtotime($val->date_updated));
            $row[]          = '<a href="'.site_url('admin/pages/form/'.$val->id).'" class="btn btn-sm btn-info">'.lang('action_edit').'</a>';
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
     * form
    */
    public function form($id = NULL)
    {
        $id                         = (int) $id;
        
        // page can only be edited
        if(!$id)
            redirect($this->uri->segment(1).'/'.$this->uri->segment(2));

        $this->acl->check_access('pages', 'p_edit');
        
        /* Initialize assets */
        $this
        ->add_plugin_theme(array(   
                            "tinymce/tinymce.min.js",
                        ), 'admin')
        ->add_js_theme( "pages/pages/form_i18n.js", TRUE );
        $data                       = $this->includes;
        
        // in case of edit
        if($id)
        {
            $result                 = $this->pages_model->get_pages_by_id($id);

            if(empty($result))
            {
                item_not_found('page');
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
            $data['page']           = $result->page; 
        }
            
        $data['title'] = array(
            'name'      => 'title',
            'id'        => 'title',
            'type'      => 'text',
            'class'     => 'form-control',
            'value'     => $this->form_validation->set_value('title', !empty($result->title) ? $result->title : ''),
        );

        $data['content'] = array(
            'name'      => 'content',
            'id'        => 'content',
            'type'      => 'textarea',
            'class'     => 'form-control tinymce',
            'value'     => $this->form_validation->set_value('content', !empty($result->content) ? $result->content : ''),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/pages/form', $data, TRUE);
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

        // page can only be edited
        if(!$id)
            $this->json_response([], sprintf(lang('alert_not_found'), lang('menu_page')));

        $this->acl->check_access('pages', 'p_edit', TRUE);

        // static page content not editable in demo mode
        if(DEMO_MODE === 1)
            $this->json_response([], lang('alert_demo'));

        // Unique columns
        $result             = (object) array();
        if($id)
        {
            $result                 = $this->pages_model->get_pages_by_id($id);

            if(empty($result))
            {
                // data =[], msg=null, flag=0, error_fields=[]
                $error = sprintf(lang('alert_not_found'), lang('menu_page'));
                $this->json_response([], $error);
            }
        }
        
        /* Validate form input */
        $this->form_validation
        ->set_rules('title', lang('common_title'), 'trim|required|max_length[128]')
        ->set_rules('content', lang('common_content'), 'trim|required');

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

        // data to insert in db table
        $data                       = array();
        $data['title']              = $this->input->post('title');
        $data['content']            = $this->input->post('content');
        $data['date_updated']       = date('Y-m-d H:i:s');
        
        if(!$id)
            $data['date_added']     = date('Y-m-d H:i:s');
        
        $flag                       = $this->pages_model->save_pages($data, $id);

        if($flag)
        {
            $message = sprintf(lang('alert_insert_success'), lang('menu_page'));
            if($id)
                $message = sprintf(lang('alert_update_success'), lang('menu_page'));
                
            $this->session->set_flashdata('message', $message);
            
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], null, 1);
        }
        
        $error = lang('alert_changes_save_fail');
        $this->session->set_flashdata('error', $error);
        $this->json_response();
    }

}

/* Pages controller ends */