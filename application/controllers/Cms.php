<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cms Controller
 *
 * This class handles static pages
 *
 * @package     anofie
 * @author      classiebit
*/

class Cms extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('pages_model');
    }

    /**
	 * Default
     */
	function index($page = 'about')
	{
        if($page != 'about' && $page != 'terms' && $page != 'privacy')
            item_not_found('page');

        /* Initialize assets and title */
        // setup page header data
        $this->set_title($this->settings->site_name.' '.lang($page));

        $data           = $this->includes;

        // get the page
        $pages          = $this->pages_model->get_page_by_any(['page'=>$page]);

        if(empty($pages))
            item_not_found('page');

        // set content data
        $content_data   = array(
            'title'     => $pages->title,
            'content'   => $pages->content,
        );

        // load views
        $data['content'] = $this->load->view('cms', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

}

/* About controller ends */