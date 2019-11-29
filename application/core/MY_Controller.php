<?php defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");

use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * Core Class all other classes extend
 */

class MY_Controller extends CI_Controller {

	/**
     * Common data
     */
    public $user;
    public $settings;
    public $languages;
    public $includes;
    public $theme;
    public $template;
    public $error;
    public $notifications;
    public $pagination_limit = 5;

    /**
     * RTL Languages
    */
    public $is_rtl      = false;
    public $rtl_langs   = ['persian', 'hebrew', 'arabic', 'malay', 'uyghur', 'urdu', 'malayalam'];

    /*
     * Meta 
     */
    public $meta_title;
    public $meta_tags;
    public $meta_description;
    public $meta_image;
    public $meta_url;

    /* Admin & Frontend themes */
    public $core_theme;
    public $admin_theme;
    public $frontend_theme;

    /* Demo data (this is only effective in DEMO mode) */
    public $demo_data;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // check if anofie is installed
        $this->is_installed();
        
        // autoload composer files
        include APPPATH . "/vendor/autoload.php";

        // get global settings
        $this->get_settings();

        // init regional settings
        $this->regional_settings();

        // check if language is rtl
        $this->check_rtl();

        // Load Ion Auth Library as default authenticate library
        $this->load->library('ion_auth');

        // load the core language file
        $this->lang->load('core');

        // get logged in user
        $this->user = $this->session->userdata('logged_in');

        // load notification model
        $this->load->model('notifications_model');

        // set meta values
        $this->meta_title        = $this->settings->meta_title;
        $this->meta_tags         = $this->settings->meta_tags;
        $this->meta_description  = $this->settings->meta_description;
        $this->meta_image        = base_url('upload/general/'.$this->settings->logo_og);
        $this->meta_url          = site_url();

        // core plugins
        $this->core_theme = array(
            'axios/axios.min.js',
            'pace-progressbar/pace.min.css',
            'pace-progressbar/pace.min.js',
            'sweetalert2/sweetalert2_custom.min.css',
            'sweetalert2/sweetalert2.min.js',
        );

        // admin theme
        $this->admin_theme = array(
            'dashboard-theme/assets/vendor/nucleo/css/nucleo.css',
            'dashboard-theme/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css',
            'dashboard-theme/assets/css/argon.min.css',

            'dashboard-theme/assets/vendor/jquery/dist/jquery.min.js',
            'dashboard-theme/assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js',
            'dashboard-theme/assets/js/argon.min.js',
        );

        // include frontend theme
        $this->frontend_theme = array(
            'frontend-theme/assets/vendor/nucleo/css/nucleo.css',
            'frontend-theme/assets/vendor/@fortawesome/fontawesome-free/css/all.min.css',
            'frontend-theme/assets/css/argon.min.css',
            
            'jquery/jquery.min.js',
            'frontend-theme/assets/vendor/popper/popper.min.js',
            'frontend-theme/assets/vendor/bootstrap/bootstrap.min.js',
            'frontend-theme/assets/vendor/headroom/headroom.min.js',
            'frontend-theme/assets/js/argon.min.js',
            
            'cookieconsent/cookieconsent.min.css',
            'cookieconsent/cookieconsent.min.js',
        );

        // enable the profiler?
        $this->output->enable_profiler($this->config->item('profiler'));
	}

    /**
     * json_response
     * echo json using global format
      */
    function json_response($data = [], $msg = null, $flag = 0, $error_fields = [])
    {
        echo json_encode([
            'data'          => $data,
            'msg'           => $msg,
            'flag'          => $flag,
            'error_fields'  => $error_fields,
        ]);
        exit;
    }

    /**
     * Load frontend data
    */
    function load_frontend()
    {
        // prepare theme name
        $this->settings->theme = strtolower($this->config->item('public_theme'));

        // get user notifications
        if(isset($this->user['id']))
            $this->notifications = $this->notifications_model->get_notifications($this->user['id']);
        
        /**
        * LOAD FRONT-END AND CORE PLUGINS AND CUSTOM JS AND CSS 
        * ORDER IS IMPORTANT 
        * ==========================================================
        */
        // 1. frontend-theme and plugins
        $this->add_plugin_theme($this->frontend_theme, 'default');
        
        // 2. Load core plugins and assets
        $this->load_core_assets();
        
        // 3. Load frontend custom common js
        $this->add_js_theme( "custom_i18n.js", TRUE );
    }

    /**
     * Load core 
     * js
     * css
     * plugins
     * 
     * will be called from Admin, public and private controller
     * in order to load core files after loading all theme specific assets
     */
    function load_core_assets()
    {
        // load core js and css
        $this
        ->add_plugin_theme($this->core_theme, 'core')
        ->add_external_css(array(
            base_url("/themes/core/css/core.css"),
        ))
        ->add_external_js(array(
            "/themes/core/js/core_i18n.js",
        ), NULL, TRUE);
    }

    /**
     *  Check if installed
    */
    function is_installed()
    {
        // if upload general directory not exist means app is not installed
        if(!is_dir(BASEPATH.'../upload/general'))
        {
            echo 'Anofie is not installed. Read the <a href="https://anofie-docs.classiebit.com" target="_blank">Anofie Docs</a>';exit;
        }
            
    }

    /**
     * Get global settings
      */
    function get_settings()
    {
        // get global settings
        $settings = $this->settings_model->get_settings();
        $this->settings = new stdClass();
        foreach ($settings as $setting)
        {
            $this->settings->{$setting['name']} = (@unserialize($setting['value']) !== FALSE) ? unserialize($setting['value']) : $setting['value'];
        }
        $this->settings->root_folder  = $this->config->item('root_folder');

        // get site logo
        $logos = scandir(BASEPATH.'../upload/general');
        foreach($logos as $val)
        {
            if(strpos($val, 'logo_thumb') !== false)
                $this->settings->logo_thumb = $val;

            if(strpos($val, 'logo_og') !== false)
                $this->settings->logo_og = $val;

            if(strpos($val, 'logo.') !== false)
                $this->settings->logo = $val;
        }
    }

    /**
     * Default regional setting
     * 
     * set timezone and site language from 
     * settings
      */
    function regional_settings()
    {
        // set the time zone
        $timezones = $this->config->item('timezones');
        if (function_exists('date_default_timezone_set'))
        {
            date_default_timezone_set($timezones[$this->settings->timezones]);
        }

        // set site language
        $this->languages = get_languages();
        
        // set language according to this priority:
        //   1) Set user selected language
        //   2) If no session, use the global setting languauge
        if ($this->session->language)
        {
            $this->config->set_item('language', $this->session->language);
        }
        else
        {
            $this->config->set_item('language', $this->settings->default_language);
            
            // save language to session
            $this->session->language = $this->config->item('language');
        }
    }

    /**
     * Check if language is rtl
     * 
     * 
      */
    function check_rtl()
    {
        $current_lang = strtolower($this->config->item('language'));
        if (in_array($current_lang, $this->rtl_langs)) 
            $this->is_rtl = TRUE;

        return true;
    }


    /**
     * Redirect the current non-https request
     * to https on the basis of few conditions
    */
    function redirect_https()
    {
        // redirect to https except bots
        // and only redirect to https if 
        // - base_url is set to https and currently on http
        if(ENVIRONMENT === 'production')
        {
            $CrawlerDetect = new CrawlerDetect;
            // if it's not a bot/crawler
            if(!$CrawlerDetect->isCrawler()) 
            {
                // check if request is http
                if (! (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ) 
                {
                    // currently on http connection
                    // redirect only if base_url has https
                    $is_https = explode(':', $this->config->item('base_url'))[0];
                    if($is_https === 'https')
                    {
                        // simple redirect if non-sub-domain
                        $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

                        redirect($redirect);
                    }
                }
            }
        }
    }


    /**
     * Add CSS from external source or outside folder theme
     *
     * This function used to easily add css files to be included in a template.
     * with this function, we can just add css name as parameter and their external path,
     * or add css complete with path. See example.
     *
     * We can add one or more css files as parameter, either as string or array.
     * If using parameter as string, it must use comma separator between css file name.
     * -----------------------------------
     * Example:
     * -----------------------------------
     * 1. Using string as first parameter
     *     $this->add_external_css( "global.css, color.css", "http://example.com/assets/css/" );
     *      or
     *      $this->add_external_css(  "http://example.com/assets/css/global.css, http://example.com/assets/css/color.css" );
     *
     * 2. Using array as first parameter
     *     $this->add_external_css( array( "global.css", "color.css" ),  "http://example.com/assets/css/" );
     *      or
     *      $this->add_external_css(  array( "http://example.com/assets/css/global.css", "http://example.com/assets/css/color.css") );
     *
     */
    function add_external_css($css_files, $path = NULL)
    {
        // make sure that $this->includes has array value
        if ( ! is_array( $this->includes ) )
            $this->includes = array();

        // if $css_files is string, then convert into array
        $css_files = is_array( $css_files ) ? $css_files : explode( ",", $css_files );

        foreach( $css_files as $css )
        {
            // remove white space if any
            $css = trim( $css );

            // go to next when passing empty space
            if ( empty( $css ) ) continue;

            // using sha1( $css ) as a key to prevent duplicate css to be included
            $this->includes[ 'css_files' ][ sha1( $css ) ] = is_null( $path ) ? $css : $path . $css;
        }

        return $this;
    }


    /**
     * Add JS from external source or outside folder theme
     *
     * This function used to easily add js files to be included in a template.
     * with this function, we can just add js name as parameter and their external path,
     * or add js complete with path. See example.
     *
     * We can add one or more js files as parameter, either as string or array.
     * If using parameter as string, it must use comma separator between js file name.
     * -----------------------------------
     * Example:
     * -----------------------------------
     * 1. Using string as first parameter
     *     $this->add_external_js( "global.js, color.js", "http://example.com/assets/js/" );
     *      or
     *      $this->add_external_js(  "http://example.com/assets/js/global.js, http://example.com/assets/js/color.js" );
     *
     * 2. Using array as first parameter
     *     $this->add_external_js( array( "global.js", "color.js" ),  "http://example.com/assets/js/" );
     *      or
     *      $this->add_external_js(  array( "http://example.com/assets/js/global.js", "http://example.com/assets/js/color.js") );
     *
     * 
     */
    function add_external_js( $js_files, $path = NULL, $is_i18n = FALSE )
    {
        if ( $is_i18n )
            return $this->add_jsi18n_theme( $js_files, TRUE );

        // make sure that $this->includes has array value
        if ( ! is_array( $this->includes ) )
            $this->includes = array();

        // if $js_files is string, then convert into array
        $js_files = is_array( $js_files ) ? $js_files : explode( ",", $js_files );

        foreach( $js_files as $js )
        {
            // remove white space if any
            $js = trim( $js );

            // go to next when passing empty space
            if ( empty( $js ) ) continue;

            // using sha1( $css ) as a key to prevent duplicate css to be included
            $this->includes[ 'js_files' ][ sha1( $js ) ] = is_null( $path ) ? $js : $path . $js;
        }

        return $this;
    }


    /**
     * Add CSS from Active Theme Folder
     *
     * This function used to easily add css files to be included in a template.
     * with this function, we can just add css name as parameter
     * and it will use default css path in active theme.
     *
     * We can add one or more css files as parameter, either as string or array.
     * If using parameter as string, it must use comma separator between css file name.
     * -----------------------------------
     * Example:
     * -----------------------------------
     * 1. Using string as parameter
     *     $this->add_css_theme( "bootstrap.min.css, style.css, admin.css" );
     *
     * 2. Using array as parameter
     *     $this->add_css_theme( array( "bootstrap.min.css", "style.css", "admin.css" ) );
     *
     */
    function add_css_theme( $css_files )
    {
        // make sure that $this->includes has array value
        if ( ! is_array( $this->includes ) )
            $this->includes = array();

        // if $css_files is string, then convert into array
        $css_files = is_array( $css_files ) ? $css_files : explode( ",", $css_files );

        foreach( $css_files as $css )
        {
            // remove white space if any
            $css = trim( $css );

            // go to next when passing empty space
            if ( empty( $css ) ) continue;

            // using sha1( $css ) as a key to prevent duplicate css to be included
            $this->includes[ 'css_files' ][ sha1( $css ) ] = base_url( "/themes/{$this->settings->theme}/css" ) . "/{$css}";
        }

        return $this;
    }


    /**
     * Add JS from Active Theme Folder
     *
     * This function used to easily add js files to be included in a template.
     * with this function, we can just add js name as parameter
     * and it will use default js path in active theme.
     *
     * We can add one or more js files as parameter, either as string or array.
     * If using parameter as string, it must use comma separator between js file name.
     *
     * The second parameter is used to determine wether js file is support internationalization or not.
     * Default is FALSE
     * -----------------------------------
     * Example:
     * -----------------------------------
     * 1. Using string as parameter
     *     $this->add_js_theme( "jquery-1.11.1.min.js, bootstrap.min.js, another.js" );
     *
     * 2. Using array as parameter
     *     $this->add_js_theme( array( "jquery-1.11.1.min.js", "bootstrap.min.js,", "another.js" ) );
     *
     */
    function add_js_theme( $js_files, $is_i18n = FALSE )
    {
        if ( $is_i18n )
            return $this->add_jsi18n_theme( $js_files );

        // make sure that $this->includes has array value
        if ( ! is_array( $this->includes ) )
            $this->includes = array();

        // if $css_files is string, then convert into array
        $js_files = is_array( $js_files ) ? $js_files : explode( ",", $js_files );

        foreach( $js_files as $js )
        {
            // remove white space if any
            $js = trim( $js );

            // go to next when passing empty space
            if ( empty( $js ) ) continue;

            // using sha1( $js ) as a key to prevent duplicate js to be included
            $this->includes[ 'js_files' ][ sha1( $js ) ] = base_url( "/themes/{$this->settings->theme}/js" ) . "/{$js}";
        }

        return $this;
    }

    /**
     * Add plugins from specifically provided Theme Folder
     *
     */
    function add_plugin_theme( $plugin_files, $is_common = FALSE )
    {   
        // if plugin files are common
        if($is_common)
            $theme_folder = $is_common;
        else
            $theme_folder = $this->settings->theme;

        // make sure that $this->includes has array value
        if ( ! is_array( $this->includes ) )
            $this->includes = array();

        // if $plugin_files is string, then convert into array
        $plugin_files = is_array( $plugin_files ) ? $plugin_files : explode( ",", $plugin_files );

        foreach( $plugin_files as $p_f )
        {
            // remove white space if any
            $js = trim( $p_f );

            // go to next when passing empty space
            if ( empty( $p_f ) ) continue;

            // using sha1( $p_f ) as a key to prevent duplicate css & js to be included
            // and if its a css file then add it into css_files else js_files
            if(strpos($p_f, '.css') === FALSE)
                $this->includes[ 'js_files' ][ sha1( $p_f ) ] = base_url( "/themes/{$theme_folder}/plugins" ) . "/{$p_f}";
            else    
                $this->includes[ 'css_files' ][ sha1( $p_f ) ] = base_url( "/themes/{$theme_folder}/plugins" ) . "/{$p_f}";
        }

        return $this;
    }


    /**
     * Add JSi18n files from Active Theme Folder
     *
     * This function used to easily add jsi18n files to be included in a template.
     * with this function, we can just add jsi18n name as parameter
     * and it will use default js path in active theme.
     *
     * We can add one or more jsi18n files as parameter, either as string or array.
     * If using parameter as string, it must use comma separator between jsi18n file name.
     * -----------------------------------
     * Example:
     * -----------------------------------
     * 1. Using string as parameter
     *     $this->add_jsi18n_theme( "dahboard_i18n.js, contact_i18n.js" );
     *
     * 2. Using array as parameter
     *     $this->add_jsi18n_theme( array( "dahboard_i18n.js", "contact_i18n.js" ) );
     *
     * 3. Or we can use add_js_theme function, and add TRUE for second parameter
     *     $this->add_js_theme( "dahboard_i18n.js, contact_i18n.js", TRUE );
     *      or
     *     $this->add_js_theme( array( "dahboard_i18n.js", "contact_i18n.js" ), TRUE );
     *
     */
    function add_jsi18n_theme( $js_files , $is_external = FALSE)
    {
        // make sure that $this->includes has array value
        if ( ! is_array( $this->includes ) )
            $this->includes = array();

        // if $css_files is string, then convert into array
        $js_files = is_array( $js_files ) ? $js_files : explode( ",", $js_files );

        foreach( $js_files as $js )
        {
            // remove white space if any
            $js = trim( $js );

            // go to next when passing empty space
            if ( empty( $js ) ) continue;

            // using sha1( $js ) as a key to prevent duplicate js to be included
            if($is_external) 
            {
                $this->includes[ 'js_files_i18n' ][ sha1( $js ) ] = $this->jsi18n->translate( $js );
            }
            else
            {
                $this->includes[ 'js_files_i18n' ][ sha1( $js ) ] = $this->jsi18n->translate( "/themes/{$this->settings->theme}/js/{$js}" );
            }
        }

        return $this;
    }


    /** 
     * Set Page Title
    */
    function set_title( $page_title )
    {
        $this->includes[ 'page_title' ] = $page_title;

        /* check wether page_header has been set or has a value
        * if not, then set page_title as page_header
        */
        $this->includes[ 'page_header' ] = isset( $this->includes[ 'page_header' ] ) ? $this->includes[ 'page_header' ] : $page_title;
        return $this;
    }


    /**
     * Set Page Header
     * sometime, we want to have page header different from page title
     * so, use this function
     */
    function set_page_header( $page_header )
    {
        $this->includes[ 'page_header' ] = $page_header;
        return $this;
    }


    /**
     * Set Template
     * sometime, we want to use different template for different page
     * for example, 404 template, login template, full-width template, sidebar template, etc.
     * so, use this function
     * 
     */
    function set_template( $template_file = 'template.php' )
    {
        // make sure that $template_file has .php extension
        $template_file = substr( $template_file, -4 ) == '.php' ? $template_file : ( $template_file . ".php" );

        $this->template = "../../{$this->settings->root_folder}/themes/{$this->settings->theme}/{$template_file}";
    }

}

require(APPPATH.'core/Admin_Controller.php');
require(APPPATH.'core/Private_Controller.php');
require(APPPATH.'core/Public_Controller.php');