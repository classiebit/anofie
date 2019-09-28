<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Language Controller
 *
 * This class handles language module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Language extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Change language - user selected
     */
    function change_language($language = 'english')
    {
        $language   = (string) $language;
        $languages  = get_languages();

        if(array_key_exists($language, $languages))
        {
            // check if selected language file exist
            // if not exist then load english as fallback
            $basepath   = APPPATH.'language/'.$language.'/core_lang.php';
            $found      = file_exists($basepath);
            if ($found)
                $this->session->language = $language;
        }

        redirect($_SERVER['HTTP_REFERER']);
    }

}
