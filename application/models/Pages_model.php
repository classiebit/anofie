<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pages Model
 *
 * This model handles pages module data
 *
 * @package     anofie
 * @author      classiebit
*/

class Pages_model extends CI_Model {

    /**
     * @vars
     */
    private $table  = 'pages';
    private $select = [];
    
    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->select   = [
            "$this->table.id",
            "$this->table.page",
            "$this->table.title",
            "$this->table.content",
            "$this->table.date_added",
            "$this->table.date_updated",
        ];
    }

     /**
     * get_pages
     *
     * @return array
     * 
     **/
    public function get_pages()
    {
        return $this->db->select($this->select)
                        ->get($this->table)
                        ->result();
    }

    
    /**
     * get_pages_by_id
     *
     * @return array
     * 
     **/
    public function get_pages_by_id($id = FALSE)
    {
        $this->db->select($this->select);

        return  $this->db
                ->where(array('id'=>$id))
                ->get($this->table)
                ->row();
    }

    /**
     * get_page_by_any
     *
     * @return array
     * 
     **/
    public function get_page_by_any($where = [])
    {
        $this->db->select($this->select);

        return  $this->db
                ->where($where)
                ->get($this->table)
                ->row();
    }

    /**
     * save_pages
     *
     * @return array
     * 
     **/
    public function save_pages($data = array(), $id = FALSE)
    {
        if($id) // update
        {
            $this->db->where(array('id'=>$id))
                     ->update($this->table, $data);
            return $id;
        }
        else // insert
        {
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        }
        
    }
}

/*Pages model ends*/