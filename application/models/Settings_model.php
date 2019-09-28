<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Settings Model
 *
 * This model handles settings module data
 *
 * @package     anofie
 * @author      classiebit
*/

class Settings_model extends CI_Model {

    /**
     * @vars
     */
    private $_db;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // define primary table
        $this->_db = 'settings';
    }


    /**
     * Retrieve all settings
     *
     * @return array|null
     */
    function get_settings()
    {
        $results = NULL;

        $sql = "
            SELECT *
            FROM {$this->_db}
            ORDER BY sort_order ASC
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0)
        {
            $results = $query->result_array();
        }

        return $results;
    }


    /**
     * Save changes to the settings
     *
     * @param  array $data
     * @param  int $user_id
     * @return boolean
     */
    function save_settings($data = array(), $user_id = NULL)
    {
        if ($data && $user_id)
        {
            $saved = FALSE;

            foreach ($data as $key => $value)
            {
                $sql = "
                    UPDATE {$this->_db}
                    SET value = " . ((is_array($value)) ? $this->db->escape(serialize($value)) : $this->db->escape($value)) . ",
                        last_update = '" . date('Y-m-d H:i:s') . "'
                    WHERE name = " . $this->db->escape($key) . "
                ";

                $this->db->query($sql);

                if ($this->db->affected_rows() > 0)
                {
                    $saved = TRUE;
                }
            }

            if ($saved)
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * get setting row by name
    */
    function get_setting_by_name($name = NULL)
    {
        return $this->db->where(array('name'=>$name))
                        ->get('settings')
                        ->row();
    }

}

/*Settings model ends*/