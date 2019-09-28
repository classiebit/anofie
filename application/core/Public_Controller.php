<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Public Class - used for all public pages
 */

class Public_Controller extends MY_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load frontend data
        $this->load_frontend();
        
        // declare main template
        $this->template = "../../{$this->settings->root_folder}/themes/{$this->settings->theme}/template.php";
    }
}
