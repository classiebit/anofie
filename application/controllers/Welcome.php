<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Welcome Controller
 *
 * This class handles welcome module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Welcome extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        if($this->session->userdata('logged_in'))
            redirect(site_url('messages'));

        $this->load->model(array(
                            'users_model',
                        ));
    }

    /**
	 * Default
     */
	function index()
	{
        /* Initialize assets and title */
        $this
        ->add_plugin_theme([
            'frontend-theme/assets/vendor/onscreen/onscreen.min.js',
        ], 'default')
        ->add_js_theme( "pages/auth/index_i18n.js", TRUE )
        ->add_js_theme( "pages/welcome/index_i18n.js", TRUE );

        // load ion auth lang
        $this->lang->load('auth');

        // setup page header data
        $this->set_title(sprintf(lang('menu_welcome'), $this->settings->site_name));

        $data           = $this->includes;

        // set content data
        $content_data   = array(
            'title'     => $this->settings->site_title,
            'subtitle'     => $this->settings->site_welcome,
            'recent'    => $this->users_model->get_recent_users(),
        );

        // load views
        $data['content'] = $this->load->view('welcome', $content_data, TRUE);
		$this->load->view($this->template, $data);
	}

}

/* Welcome controller ends */