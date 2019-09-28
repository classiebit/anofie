<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Errors Controller
 *
 * This class handles errors module functionality
 *
 * @package     anofie
 * @author      classiebit
*/


class Errors extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // disable the profiler
        $this->output->enable_profiler(FALSE);
    }


    /**
     * Custom 404 page
     */
    function error404()
    {
        // setup page header data
        $this->set_title(lang('action_404'));

        $data = $this->includes;

        // load views
        $data['content'] = $this->load->view("errors/error_404", NULL, TRUE);
        $this->load->view($this->template, $data);
    }

}
