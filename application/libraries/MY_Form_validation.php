<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter MongoDB Library
 *
/**
 * MY_Form_validation
 *
 * Override core methods forcing them to interact with  MongoDB.
 * @since v1.1
 */

class MY_Form_validation extends CI_Form_validation
{
    
    public function __construct($rules = array())
    {   
        $this->CI_ADMN =& get_instance();
        parent::__construct($rules);
        
    }

    // --------------------------------------------------------------------

	/**
	 * Alpha-numeric with underscores and dashes and spaces and dots
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha_dash_spaces($str)
	{
		return (bool) preg_match('/^[A-Z0-9 ._-]+$/i', $str);
	}

	// --------------------------------------------------------------------

	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric with underscores and dashes and spaces and dots
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha_dash_spaces_special($str)
	{
		return (bool) preg_match('/^[A-Z0-9 ,\'?!._-]+$/i', $str);
	}

	// --------------------------------------------------------------------

	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric with underscores only
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha_underscores($str)
	{
		return (bool) preg_match('/^[A-Z0-9_]+$/i', $str);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Alpha-numeric with dots
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha_dot($str)
	{
		return (bool) preg_match('/^[A-Z0-9.]+$/i', $str);
	}

	// --------------------------------------------------------------------
	

	/**
	 * Alpha-numeric with underscores and dashes and spaces and dots and special characters
	 *
	 * @param	string
	 * @return	bool
	 */
	// public function alpha_dash_special($str)
	// {
	// 	return (bool) preg_match('/^[A-Z0-9 ")(][)@(~&,._-]+$/i', $str);
	// }

	// --------------------------------------------------------------------


	/**
	 * Arabic letters + Alpha-numeric characters
	 *
	 * @param	string
	 * @return	bool
	 */
	public function alpha_numeric_arabic($str)
	{
		return (bool) preg_match('/^([a-z0-9]|\p{Arabic})+$/iu', $str);
	}

	// --------------------------------------------------------------------
    
}  
