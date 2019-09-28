<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Library Datatables
 *
 * This library handles datatables related functionality
 *
 * @package     Classes
 * @author      DK
**/

class Datatables {

    /**
     * @vars
     */
    var $table          = '';
    var $columns        = array(); // columns to select
    var $column_order   = array(); // columns fetch order
    var $column_search  = array(); // columns for search
    var $order          = array(); // default order     
    var $sort_table2    = '';      // order by other table
    
    function __construct()
    {
        $this->CI_LIB =& get_instance();
    }

    /**
     * get_datatables
     *
     * @return array
     * 
     **/
    public function get_datatables($table = '', $columns = array(), $column_order = array(), $column_search = array(), $order = array(), $where = array(), $sort_table2 = '')
    {
        $this->table            = $table;
        $this->columns          = $columns;
        $this->column_order     = $column_order;
        $this->column_search    = $column_search;
        $this->order            = $order;
        $this->sort_table2      = $sort_table2;
        
        $this->_get_datatables_query();

        if($this->CI_LIB->input->post('length') != -1)
            $this->CI_LIB->db->limit($this->CI_LIB->input->post('length'), $this->CI_LIB->input->post('start'));

        if(!empty($where))
            $this->CI_LIB->db->where($where);

        return $this->CI_LIB->db->get()
                        ->result();
    }

    /**
     * _get_datatables_query
     *
     * @return array
     * 
     **/
    private function _get_datatables_query()
    {
        $this->CI_LIB->db->select($this->columns)         
                         ->from($this->table);

        if($this->sort_table2) // join the other table in case of order by other table
            if($this->sort_table2 === 'group_id') // join the users_groups table 
                $this->CI_LIB->db->join('users_groups', 'users.id = users_groups.user_id');
            
        $i = 0;
     
        foreach ($this->column_search as $item) // loop column 
        {
            if($this->CI_LIB->input->post('search')['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->CI_LIB->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->CI_LIB->db->like($item, $this->CI_LIB->input->post('search')['value']);
                }
                else
                {
                    $this->CI_LIB->db->or_like($item, $this->CI_LIB->input->post('search')['value']);
                }
 
                if(count($this->column_search) - 1 == $i) //last loop
                    $this->CI_LIB->db->group_end(); //close bracket
            }
            $i++;
        }
         
        if(isset($_POST['order'])) // here order processing
        {
            if($this->sort_table2) // in case of order by other table
            {
                if($this->sort_table2 === 'group_id') // order by group_id in users table
                    $this->CI_LIB->db->order_by('users_groups.group_id', $this->CI_LIB->input->post('order')['0']['dir']);    
            }
            else // default case
            {
                $this->CI_LIB->db->order_by($this->column_order[$this->CI_LIB->input->post('order')['0']['column']], $this->CI_LIB->input->post('order')['0']['dir']);
            }
            
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->CI_LIB->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    
    /**
     * count_filtered
     *
     * @return array
     * 
     **/
    public function count_filtered($where = array())
    {
        $this->_get_datatables_query();

        if(!empty($where))
            $this->CI_LIB->db->where($where);

        return $this->CI_LIB->db->get()
                                ->num_rows();
    }
    
    /**
     * count_all
     *
     * @return array
     * 
     **/ 
    public function count_all($where = array())
    {
        if(!empty($where))
            $this->CI_LIB->db->where($where);

        return $this->CI_LIB->db->from($this->table)
                                ->count_all_results();
    }

}
/* End Datatables Library */