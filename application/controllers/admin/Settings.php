<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Controller
 *
 * This class handles settings module functionality
 *
 * @package     anofie
 * @author      classiebit
*/

class Settings extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        /* Page Title */
        $this->set_title( lang('menu_settings') );
    }


    /**
     * Settings Editor
     */
    function index()
    {
        // sanitize input data
        $_POST                       = $this->security->xss_clean($_POST);

        $this->acl->check_access('settings', 'p_view');

        // get settings
        $settings       = $this->settings_model->get_settings();
        $settings_2     = array();

        // form validations
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        foreach ($settings as $key => $setting)
        {
            $settings_2[$setting['setting_type']][$key] = $setting;
            
            if ($setting['validation'])
            {
                if ($setting['translate'])
                {
                    // setup a validation for each translation
                    foreach ($this->session->languages as $language_key=>$language_name)
                    {
                        $this->form_validation->set_rules($setting['name'] . "[" . $language_key . "]", $setting['label'] . " [" . $language_name . "]", $setting['validation']);
                    }
                }
                else
                {
                    // single validation
                    if(isset($_POST[''.$setting['name'].''])) // set validation seperately for each tab
                        $this->form_validation->set_rules($setting['name'], $setting['label'], $setting['validation']);
                }
            }
        }

        // settings not editable in demo mode
        if(DEMO_MODE === 1 && !empty($_POST))
            $this->form_validation->set_rules('demo_data', lang('common_demo'), 'required', 
                ['required' => lang('alert_demo')]
            );

        if ($this->form_validation->run() == TRUE)
        {
            $this->acl->check_access('settings', 'p_edit');
            
            $user = $this->session->userdata('logged_in');

            if(! empty($_FILES['site_logo']['name'])) // if logo logo
            {
                $file_image         = array('folder'=>'general', 'input_file'=>'site_logo', 'filename'=>'logo', 'format'=>'png|PNG', 'og_image' => true);
                
                // update users image
                $filename_image     = $this->file_uploads->upload_file($file_image, 300, 300, 96, 96, 300, 200);

                // through image upload error
                if(!empty($filename_image['error']))
                    $this->session->set_flashdata('error', $filename_image['error']);
            }

            // save the settings
            $saved = $this->settings_model->save_settings($this->input->post(), $user['id']);

            if ($saved)
            {
                $message = sprintf(lang('alert_update_success'), lang('menu_setting'));
                $message .= '<br><small>'.lang('action_logout').' & '.lang('users_login').' to see effects</small>';
                $this->session->set_flashdata('message', $message);

                // reload the new settings
                $settings = $this->settings_model->get_settings();
                foreach ($settings as $setting)
                {
                    $this->settings->{$setting['name']} = @unserialize($setting['value']);
                }
            }
            else
            {
                $this->session->set_flashdata('error', lang('alert_changes_save_fail'));
            }

            // reload the page
            redirect('admin/settings');
        }

        /* Initialize assets */
        $this->add_js_theme( "pages/settings/form_i18n.js", TRUE );
        $data                       = $this->includes;

        // set content data
        $data['settings']   = $settings_2;
        $data['cancel_url'] = "/admin";

        // load views
        $content['content'] = $this->load->view('admin/settings/form', $data, TRUE);
        $this->load->view($this->template, $content);
    }

}

/* Settings controller ends */