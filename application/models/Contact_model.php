<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Contact Model
 *
 * This model handles contacts module data
 *
 * @package     classiebit
 * @author      prodpk
*/

class Contact_model extends CI_Model {

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
        $this->_db = 'contacts';
    }


    /**
     * Save generated CAPTCHA to database
     *
     * @param  array $data
     * @return boolean
     */
    public function save_captcha($data = array())
    {
        // CAPTCHA data required
        if ($data)
        {
            // insert CAPTCHA
            $query = $this->db->insert_string('captcha', $data);
            $this->db->query($query);

            // return
            return TRUE;
        }

        return FALSE;
    }


    /**
     * Verify CAPTCHA
     *
     * @param  string $captcha
     * @return boolean
     */
    public function verify_captcha($captcha = NULL)
    {
        // CAPTCHA string required
        if ($captcha)
        {
            // remove old CAPTCHA
            $expiration = time() - 7200; // 2-hour limit
            $this->db->query("DELETE FROM captcha WHERE captcha_time < {$expiration}");

            // build query
            $sql = "
                SELECT
                    COUNT(*) AS count
                FROM captcha
                WHERE word = " . $this->db->escape($captcha) . "
                    AND ip_address = '" . $this->input->ip_address() . "'
                    AND captcha_time > '{$expiration}'
            ";

            // execute query
            $query = $this->db->query($sql);

            // return results
            if ($query->row()->count > 0)
            {
                return TRUE;
            }
        }

        return FALSE;
    }


    /**
     * Save and email contact message
     *
     * @param  array $data
     * @param  array $settings
     * @return boolean
     */
    public function save_and_send_message($data=array(), $settings=array())
    {
        // post data and settings required
        if ($data && $settings)
        {
            // build query
            $sql = "
                INSERT INTO {$this->_db} (
                    name, email, title, message, created
                ) VALUES (
                    " . $this->db->escape($data['name']) . ",
                    " . $this->db->escape($data['email']) . ",
                    " . $this->db->escape($data['title']) . ",
                    " . $this->db->escape($data['message']) . ",
                    '" . date('Y-m-d H:i:s') . "'
                )
            ";

            // execute query
            $this->db->query($sql);
            
            if ($id = $this->db->insert_id())
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }

        return FALSE;
    }


    /**
     * Get list of non-deleted users
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $filters
     * @param  string $sort
     * @param  string $dir
     * @return array|boolean
     */
    function get_all($limit = 0, $offset = 0, $filters = array(), $sort = 'created', $dir = 'DESC')
    {
        // start building query
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS *
            FROM {$this->_db}
            WHERE 1 = 1
        ";

        // apply filters
        if ( ! empty($filters))
        {
            foreach ($filters as $key=>$value)
            {
                $value = $this->db->escape('%' . $value . '%');
                $sql .= " AND {$key} LIKE {$value}";
            }
        }

        // continue building query
        $sql .= " ORDER BY {$sort} {$dir}";

        // add limit and offset
        if ($limit)
        {
            $sql .= " LIMIT {$offset}, {$limit}";
        }

        // execute query
        $query = $this->db->query($sql);

        // define results
        if ($query->num_rows() > 0)
        {
            $results['results'] = $query->result_array();
        }
        else
        {
            $results['results'] = NULL;
        }

        // get total count
        $sql = "SELECT FOUND_ROWS() AS total";
        $query = $this->db->query($sql);
        $results['total'] = $query->row()->total;

        // return results
        return $results;
    }


    /**
     * Set email message as read
     *
     * @param  int $id
     * @param  int $read_by
     * @return boolean
     */
    public function read($id = NULL, $read_by = NULL)
    {
        // data required
        if ($id and $read_by)
        {
            // build query string
            $sql = "
                UPDATE {$this->_db}
                SET `read` = '" . date('Y-m-d H:i:s') . "',
                    read_by = {$read_by}
                WHERE id = {$id}
            ";

            // execute query
            $this->db->query($sql);

           // return results
            if ($this->db->affected_rows())
            {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * get_contacts_by_id
     *
     * @return array
     * 
     **/
    public function get_contacts_by_id($id = FALSE)
    {
        $this->db->select("*");

        return  $this->db
                ->where(array('id'=>$id))
                ->get('contacts')
                ->row();
    }

    /**
     * delete_contacts
     *
     * @return array
     * 
     **/
    public function delete_contacts($id = NULL, $data = array())
    {
        if($id && !empty($data)) // update
            $this->db->delete('contacts', array('id' => $id)); 
        
        if($this->db->affected_rows())
            return TRUE;

        return FALSE;
    }

}

/*Contact model ends*/