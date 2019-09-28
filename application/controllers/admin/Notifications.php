<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * Notifications Controller
 *
 * This class handles notifications module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Notifications extends Admin_Controller {

	/**
     * Constructor
    **/
	function __construct()
	{
	    parent::__construct();

        $this->load->model('notifications_model');
	}

    /**
     * delete
     */
    public function delete_notification()
    {
        /* Validate form input */
        $this->form_validation->set_rules('n_type', sprintf(lang('alert_id'), lang('notifications')), 'required|in_list[users]');

        if($this->form_validation->run() === TRUE)
        {
            /* Get Data */
            $n_type             = $this->input->post('n_type');
            $flag               = $this->notifications_model->delete_notifications($n_type, $this->user['id']);    
        }

        // data =[], msg=null, flag=0, error_fields=[]
        $this->json_response([], null, 1);
    }

}

/* Notifications controller ends */