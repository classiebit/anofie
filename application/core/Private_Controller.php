<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Private Class - used for all private pages
 */

class Private_Controller extends MY_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // redirect to login page if user is not logged in
        if (!$this->ion_auth->logged_in())
            redirect();

        // load frontend data
        $this->load_frontend();

        // declare main template
        $this->template = "../../{$this->settings->root_folder}/themes/{$this->settings->theme}/template.php";
    }
    
}
