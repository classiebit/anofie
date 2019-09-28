<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Groups Controller
 *
 * This class handles groups module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Groups extends Admin_Controller {

	/**
     * Constructor
    **/
	function __construct()
	{
	    parent::__construct();
	    
        // Page Title
        $this->set_title( lang('menu_groups') );
        
	}


	/**
     * index
     */
    function index()
    {
        /* Initialize assets */
        $this->include_index_plugins();
        $data = $this->includes;

        // Table Header
        $data['t_headers']  = array(
                                '#',
                                lang('common_name'),
                                lang('common_description'),
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

        $table              = 'groups';        
        $columns            = array(
                                "$table.id",
                                "$table.name",
                                "$table.description",
                                "$table.date_updated",
                            );
        $columns_order      = array(
                                "#",
                                "$table.name",
                                "$table.description",
                                "$table.date_updated",
                            );
        $columns_search     = array(
                                'name',
                            );
        $order              = array('id' => 'ASC');
        
        $result             = $this->datatables->get_datatables($table, $columns, $columns_order, $columns_search, $order);
        $data               = array();
        $no                 = $_POST['start'];
        
        foreach ($result as $val) 
        {
            $no++;
            $row            = array();
            $row[]          = $no;
            $row[]          = $val->name;
            $row[]          = $val->description;
            $row[]          = date('g:iA j/m/y ', strtotime($val->date_updated));
            $row[]          = $val->id <= 3 ? '<small>'.lang('common_uneditable').'<small>' : action_buttons('groups', $val->id, $val->name, lang('menu_group'));
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
        /* Initialize assets */
        $data                       = $this->includes;
        
        $id                         = (int) $id;

        // in case of edit
        if($id)
        {
            if($id === 1 || $id === 2 || $id === 3)
            {
                $this->session->set_flashdata('error', lang('common_uneditable'));
                redirect($this->uri->segment(1).'/'.$this->uri->segment(2));               
            }

            $result                 = $this->ion_auth->group($id)->row();

            if(empty($result))
            {
                item_not_found('group');
            }

            // hidden field in case of update
            $data['id']             = $result->id; 
        }
			
		$data['name']      = array(
            'name'          => 'name',
            'id'            => 'name',
            'type'          => 'text',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('name', !empty($result->name) ? $result->name : ''),
        );
        $data['description']= array(
            'name'          => 'description',
            'id'            => 'description',
            'type'          => 'textarea',
            'class'         => 'form-control',
            'value'         => $this->form_validation->set_value('description', !empty($result->description) ? $result->description : ''),
        );
        
        /* Load Template */
        $content['content']    = $this->load->view('admin/groups/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

    /**
     * save
    */
    public function save()
    {
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);

        if(!$this->ion_auth->is_admin())
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_only_admin_can');
            $this->json_response([], $error);
        }   

    	$id             = (int) $this->input->post('id');

        if($id === 1 || $id === 2 || $id === 3)
        {
            $this->session->set_flashdata('message', lang('common_uneditable'));
            // data =[], msg=null, flag=0, error_fields=[]
            $error = $this->session->flashdata('message');
            $this->json_response([], $error);
        }

        // Unique columns
        $result         = (object) array();
        $result->name   = '';

    	if($id)
    	{
            $result                 = $this->ion_auth->group($id)->row();

            if(empty($result))
            {
                $this->session->set_flashdata('error', sprintf(lang('alert_not_found'), lang('menu_group')));
                // data =[], msg=null, flag=0, error_fields=[]
                $error = $this->session->flashdata('error');
                $this->json_response([], $error);
            }
    	}

        /* Validate form input */
        $this->form_validation
        ->set_rules('name', lang('common_name'), 'trim|required|min_length[2]|max_length[20]|alpha')
        ->set_rules('description', lang('common_description'), 'trim|required|min_length[2]|max_length[100]');

        // Check duplicacy for unique columns
        if($this->input->post('name') !== $result->name)
            $this->form_validation->set_rules('name', lang('common_name'), 'trim|required|min_length[2]|max_length[20]|is_unique[groups.name]');

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

        $data                           = array();
        $data['name'] 				    = $this->input->post('name');
        $data['description']		    = $this->input->post('description');

        if(! $id) // create group
            $flag = $this->ion_auth->create_group($data['name'], $data['description']);
        else  // update group
            $flag = $this->ion_auth->update_group($id, $data['name'], ['description'=>$data['description']]);
        
        if($flag)
        {
        	if($id)
            	$this->session->set_flashdata('message', sprintf(lang('alert_update_success'), lang('menu_group')));
            else
            	$this->session->set_flashdata('message', sprintf(lang('alert_insert_success'), lang('menu_group')));

            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], null, 1);
        }
        
        if($id)
        	$this->session->set_flashdata('error', sprintf(lang('alert_update_fail'), lang('menu_group')));
        else
        	$this->session->set_flashdata('error', sprintf(lang('alert_insert_fail'), lang('menu_group')));

        $this->json_response();
    }

    
    /**
     * delete
     */
    public function delete($id = NULL)
    {
        if(!$this->ion_auth->is_admin())
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = lang('users_only_admin_can');
            $this->json_response([], $error);
        }   
        
        if($id === 1 || $id === 2 || $id === 3)
        {
            $this->session->set_flashdata('message', lang('common_uneditable'));
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], $this->session->flashdata('message'));
        }

        /* Validate form input */
        $this->form_validation->set_rules('id', sprintf(lang('alert_id'), lang('menu_group')), 'required|numeric');

        /* Get Data */
        $id                 = (int) $this->input->post('id');
        $result             = $this->ion_auth->group($id)->row();

        if(empty($result))
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $error = sprintf(lang('alert_not_found'), lang('menu_group'));
            $this->json_response([], $error);
        }

        $flag                   = $this->ion_auth->delete_group($id);

        if($flag)
        {
            // data =[], msg=null, flag=0, error_fields=[]
            $this->json_response([], sprintf(lang('alert_delete_success'), lang('menu_group')), 1);
        }
        
        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response([], $this->ion_auth->messages());
    }



}

/* Groups controller ends */