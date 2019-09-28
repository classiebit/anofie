<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Admin Class - used for all administration pages
 */
class Admin_Controller extends MY_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
        
        // redirect to login page if user is not logged in
        if (!$this->ion_auth->logged_in())
            redirect();

        // do not allow group: customers to admin panel
        if($_SESSION['groups_id'] == 3) 
            redirect();
        
        // get admin notifications
        $this->notifications = $this->notifications_model->get_notifications();

        // ACL Library
        $this->load->library('acl');

        // prepare theme name
        $this->settings->theme = strtolower($this->config->item('admin_theme'));

        /* LOAD ADMIN AND CORE PLUGINS AND CUSTOM JS AND CSS */
        /* ORDER IS IMPORTANT */
        
        // 1. Dashboard-theme and plugins
        $this->add_plugin_theme($this->admin_theme, 'admin');
        
        // 2. Load core plugins and assets
        $this->load_core_assets();
        
        // 3. Load admin custom common js
        $this->add_js_theme( "custom_i18n.js", TRUE );

        // declare main template
        $this->template = "../../{$this->settings->root_folder}/themes/{$this->settings->theme}/template.php";
    }

    function include_index_plugins()
    {
        $this
        ->add_plugin_theme(array(
            // DataTable B4
            'datatable/datatables.min.css',
            'datatable/datatables.min.js',
        ), 'admin')
        ->add_js_theme('index_i18n.js', TRUE);
    }

}
