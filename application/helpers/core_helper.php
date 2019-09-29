<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Retrieves list of language folders
 *
 * @return array
 */
if ( ! function_exists('get_languages'))
{
    function get_languages()
    {
        $CI = get_instance();

        if ($CI->session->languages)
        {
            return $CI->session->languages;
        }

        $CI->load->helper('directory');

        $language_directories = directory_map(APPPATH . '/language/', 1);

        if ( ! $language_directories)
        {
            $language_directories = directory_map(BASEPATH . '/language/', 1);
        }

        $languages = array();
        foreach ($language_directories as $language)
        {
            if (substr($language, -1) == "/" || substr($language, -1) == "\\")
            {
                $languages[substr($language, 0, -1)] = ucwords(str_replace(array('-', '_'), ' ', substr($language, 0, -1)));
            }
        }

        $CI->session->languages = $languages;

        return $languages;
    }
}

// ------------------------------------------------------------------------


/**
 * Convert name image.jpg to image_thumb.jpg
 *
 * @param string $image
 */
if ( ! function_exists('image_to_thumb'))
{
    function image_to_thumb($image)
    {
        $ext      = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $image    = strtolower(pathinfo($image, PATHINFO_FILENAME)).'_thumb.'.$ext;

        return $image;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists('language_menu'))
{
    /**
     * Language Menu
     *
     * Generates a drop-down menu of languages.
     *
     * @param   string  language
     * @param   string  classname
     * @param   string  menu name
     * @param   mixed   attributes
     * @return  string
     */
    function language_menu($default = 'english', $class = 'form-control', $name = 'currencies', $attributes = '')
    {
        $CI =& get_instance();

        $default = ($default === 'english') ? 'english' : $default;
        
        $menu = '<select name="'.$name.'" data-live-search="true"';

        if ($class !== '')
        {
            $menu .= ' class="'.$class.'"';
        }

        $menu           .= _stringify_attributes($attributes).">\n";
        $languages       = get_languages();
        ksort($languages);
        foreach ($languages as $key => $val)
        {
            $selected = ($default == $key) ? ' selected="selected"' : '';
            $menu .= '<option value="'.$key.'"'.$selected.'>'.$val."</option>\n";
        }

        return $menu.'</select>';
    }
}


// ------------------------------------------------------------------------

if ( ! function_exists('timezone_menu'))
{
    /**
	 * Timezone Menu
	 *
	 * Generates a drop-down menu of timezones.
	 *
	 * @param	string	timezone
	 * @param	string	classname
	 * @param	string	menu name
	 * @param	mixed	attributes
	 * @return	string
	 */
	function timezone_menu($default = 'UTC', $class = '', $name = 'timezones', $attributes = '')
	{
		$CI =& get_instance();
		$CI->lang->load('date', 'english');

		$default = ($default === 'GMT') ? 'UTC' : $default;

		$menu = '<select name="'.$name.'"';

		if ($class !== '')
		{
			$menu .= ' class="'.$class.'"';
		}

		$menu .= _stringify_attributes($attributes).">\n";

		foreach (timezones() as $key => $val)
		{
			$selected = ($default === $key) ? ' selected="selected"' : '';
			$menu .= '<option value="'.$key.'"'.$selected.'>'.$CI->lang->line($key)."</option>\n";
		}

		return $menu.'</select>';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('action_buttons'))
{
    /**
     * Action Buttons
     *
     * Generates a drop-down menu of form actions.
     *
     * @param   string  route
     * @param   string  id
     * @param   string  title
     * @param   string  cont (controller name)
     * @param   array   data
     */
    function action_buttons($route = NULL, $id = NULL, $title = NULL, $cont = NULL, $data = NULL)
    {
        $CI =& get_instance();

        $menu = '<div class="btn-group">';
                        
        if($route === 'faqs' || $route === 'reports' || $route === 'messages')
            $menu .= '<button type="button" data-target="#modal-'.$data->id.'" data-toggle="modal" class="btn btn-sm btn-info">'.lang('action_view').'</button>';
            
        elseif($route === 'custom_fields' || $route === 'groups' || $route == 'contacts') {}
        else
            $menu .= '<a href="'.site_url("admin/".$route."/view/".$id).'" class="btn btn-sm btn-info">'.lang('action_view').'</a>';
        
        if($route !== 'reports' && $route !== 'messages' && $route !== 'contacts')
            $menu .=  '<a href="'.site_url("admin/".$route."/form/".$id).'" class="btn btn-sm btn-success">'.lang('action_edit').'</a>';

        $menu .=  '<button type="button" role="button" class="btn btn-sm btn-danger" onclick="ajaxDelete(`'.$id.'`, `'.$title.'`, `'.$cont.'`)">'.lang('action_delete').'</button>';

        $menu .=    '</div>';

        if($route === 'reports' || $route === 'messages')
            $menu .= modal_report($data);

        return $menu;   
    }
}


// ------------------------------------------------------------------------


if ( ! function_exists('status_switch'))
{
    /**
     * Status Switch
     *
     * Generates a switch for status update.
     *
     * @param   string  status
     * @param   string  id
     */
    function status_switch($status = NULL, $id = NULL)
    {
        $CI =& get_instance();

        $switch  = '<div>';

        $switch .= '<label class="custom-toggle"><input type="checkbox" onchange="statusUpdate(this, `'.$id.'`)" '.($status == 1 ? 'checked' : '').'><span class="custom-toggle-slider rounded-circle"></span></label>';

        $switch .= '</div>';

        return $switch;
    }
}



// ------------------------------------------------------------------------


if ( ! function_exists('modal_contact'))
{
    /**
     * Modal Contact
     *
     * Generates a modal for contact.
     *
     * @param   object  data
     */
    function modal_contact($data)
    {
        $CI =& get_instance();
        $modal  = '<button type="button" data-target="#modal-'.$data->id.'" data-toggle="modal" class="btn btn-sm '.($data->read ? '' : 'btn-primary').'" >'.($data->read ? '<i class="far fa-envelope-open"></i>' : '<i class="fas fa-envelope"></i>').'</button>';

        $modal .= '<div class="modal fade " data-backdrop="false" id="modal-'.$data->id.'" data-read="'.($data->read ? "true" : "false").'" data-id="'.$data->id.'" tabindex="-1" role="dialog" aria-labelledby="modal-label-'.$data->id.'">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="modal-label-'.$data->id.'">'.$data->title.'</h4>
                            </div>
                            <div class="modal-body">
                                <p class="wrap-text">'.$data->message.'</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect"  data-dismiss="modal">'.lang('action_cancel').'</button>
                            </div>
                        </div>
                    </div>
                </div>';
                
        return $modal;
    }
}


// ------------------------------------------------------------------------
if ( ! function_exists('modal_report'))
{
    /**
     * Modal Report
     *
     * Generates a modal for report.
     *
     * @param   object  data
     */
    function modal_report($data)
    {
        $CI =& get_instance();

        $modal = '<div class="modal fade" id="modal-'.$data->id.'" data-id="'.$data->id.'" tabindex="-1" role="dialog" aria-labelledby="modal-label-'.$data->id.'">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="wrap-text">'.$data->message.'</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">'.lang('action_cancel').'</button>
                            </div>
                        </div>
                    </div>
                </div>';
                
        return $modal;
    }
}

// ------------------------------------------------------------------------


if ( ! function_exists('time_elapsed_string'))
{
    /**
     * Time Elapsed
     *
     * Timestamp To Time Elapsed.
     *
     * @param   object  data
     */
    function time_elapsed_string($datetime, $full = false) 
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

// ------------------------------------------------------------------------


if ( ! function_exists('get_domain'))
{
    /**
     * Get Domain
     *
     * return domain name.
     *
     * @param   object  data
     */

    function get_domain()
    {
        $CI =& get_instance();
        return preg_replace("/^[\w]{2,6}:\/\/([\w\d\.\-]+).*$/","$1", $CI->config->slash_item('base_url'));
    }

}

// ------------------------------------------------------------------------
if ( ! function_exists('random_string'))
{
    // PHP function to print a  
    // random string of length n 
    function random_string($n) 
    { 
        // Variable which store final string 
        $generated_string = ""; 
        
        // Create a string with the help of  
        // small letters, capital letters and 
        // digits. 
        $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
        
        // Find the lenght of created string 
        $len = strlen($domain); 
        
        // Loop to create random string 
        for ($i = 0; $i < $n; $i++) 
        { 
            // Generate a random index to pick 
            // characters 
            $index = rand(0, $len - 1); 
            
            // Concatenating the character  
            // in resultant string 
            $generated_string = $generated_string . $domain[$index]; 
        } 
        
        // Return the random generated string 
        return $generated_string; 
    } 

}

// ------------------------------------------------------------------------

if ( ! function_exists('form_input_custom'))
{
	/**
	 * Custom Input Field
	 *
	 * @param	mixed
	 * @param	string
	 * @param	mixed
	 * @return	string
	 */
	function form_input_custom($data = '', $type = '', $value = '', $extra = '')
	{
		$defaults = array(
			'type' => $type,
			'name' => is_array($data) ? '' : $data,
			'value' => $value
		);

		return '<input '._parse_form_attributes($data, $defaults)._attributes_to_string($extra)." />\n";
	}
}

// ------------------------------------------------------------------------
if ( ! function_exists('show_404_custom'))
{
    // show custom 404 page
    function show_404_custom() 
    { 
        redirect('404_override');
    } 

}


// ------------------------------------------------------------------------
if ( ! function_exists('item_not_found'))
{
    // redirect to module listing if any item not found
    function item_not_found($item = null) 
    { 
        $CI = get_instance();

        $CI->session->set_flashdata('error', sprintf(lang('alert_not_found') ,lang("menu_$item")));
        redirect($this->uri->segment(1).'/'.$this->uri->segment(2));
    } 

}


// ------------------------------------------------------------------------
if ( ! function_exists('time_ago'))
{
    function time_ago($datetime, $text = '') 
    {
        $full = false;

        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => lang('common_year'),
            'm' => lang('common_month'),
            'w' => lang('common_week'),
            'd' => lang('common_day'),
            'h' => lang('common_hour'),
            'i' => lang('common_minute'),
            's' => lang('common_second'),
        );
        
        foreach ($string as $k => &$v) 
        {
            if ($diff->$k) 
            {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } 
            else 
            {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        
        return strtolower($text).' '.($string ? implode(', ', $string) . ' '.lang('common_ago') : ' '.lang('common_now'));
    }

}


// ------------------------------------------------------------------------
if ( ! function_exists('validate_username'))
{
    /**
     * Validate username
     * - alpha_numeric
     * - min_length = 3
     * - max_length = 64
     * 
     * username = string
      */
    function validate_username($username = null) 
    {
        $username = (string) trim(urldecode($username));
        
        // alpha_numeric
        if(!ctype_alnum($username))
            return false;    

        // min_length & max_length
        if(mb_strlen($username) < 3 || mb_strlen($username) > 64)
            return false;    

        return $username;
    }

}