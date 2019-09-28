<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model(array(
                            'users_model',
                            'messages_model',
                        ));
    }


    /**
     * Dashboard
     */
    function index()
    {
        // setup page header data
		$this
        ->add_plugin_theme(array(
            'dashboard-theme/assets/vendor/chart.js/dist/Chart.min.js',
            'dashboard-theme/assets/vendor/chart.js/dist/Chart.extension.js',
        ), 'admin')
        ->add_js_theme( "pages/dashboard_i18n.js", TRUE )
        ->set_title( lang('menu_dashboard') );
		
        $data = $this->includes;

        // Breadcrumb
        $data['breadcrumb'][0]     = array('icon'=>'dashboard','route_name'=>lang('menu_dashboard'),'route_path'=>site_url('admin/dashboard'));

        $data['total_users']       = $this->users_model->count_users();
        $data['total_messages']    = $this->messages_model->count_messages_total();
        $data['top_receivers']     = $this->messages_model->count_top_receivers();
        
        // load views
        $content['content']        = $this->load->view('admin/dashboard', $data, TRUE);
        $this->load->view($this->template, $content);
    }

}
