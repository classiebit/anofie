<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Messages Model
 *
 * This model handles messages module data
 *
 * @package     anofie
 * @author      classiebit
*/

class Messages_model extends CI_Model {

	/**
     * Constructor
     */
	function __construct()
    {
        parent::__construct();
    }

    /**
     * @vars
     */
    private $table = 'messages';

    /**
     * count_messages
     *
     * @return array
     * 
     **/

     // count messages
    public function count_messages($users_id = NULL, $type = 'r')
    {
        if($type === 'r')
        {
            $this->db->where(array(
                                "$this->table.m_to"             => $users_id, 
                                "$this->table.m_to_delete"      => 0, 
                                "$this->table.m_flag"           => 0,
                            ));
        }
        else if($type === 'f')
        {
            $this->db->where(array(
                                "$this->table.m_to"         => $users_id, 
                                "$this->table.m_to_delete"  => 0, 
                                "$this->table.m_flag"       => 0, 
                                "$this->table.m_favorite"   => 1, 
                            ));
        }                    
        else
        {
            $this->db->where(array(
                                "$this->table.m_from"           => $users_id, 
                                "$this->table.m_from_delete"    => 0, 
                                "$this->table.m_flag"           => 0,
                            ));
        }

        return $this->db->count_all_results($this->table);
    }    

    /**
     * count_messages_total
     *
     * @return array
     * 
     **/
    public function count_messages_total()
    {
        return $this->db->count_all_results($this->table);
    }    

    /**
     * count_top_receivers
     * get the users having highest messages MAX(m_to)
     */
    public function count_top_receivers()
    {
        return $this->db->query(
            "SELECT ms.m_to, COUNT(ms.m_to) AS `cnt`, (SELECT us.username FROM users us WHERE us.id = m_to) username 
            FROM messages ms GROUP BY ms.m_to ORDER BY `cnt` DESC LIMIT 10")
                        ->result();
    }


    /**
     * get_message_by_id
     *
     * @return array
     * 
     **/
    public function get_message_by_id($users_id = NULL, $id = NULL, $admin = FALSE, $sent = FALSE)
    {
        $this->db->select(array(
                            "$this->table.id",
                            "$this->table.m_from",
                            "$this->table.m_to",
                            "$this->table.m_favorite",
                            "$this->table.message",
                            "$this->table.m_flag",
                        ));

        if($admin)
        {
            $this->db->where(array("$this->table.id"=>$id));
        }
        else if($sent)
        {
            $this->db->where(array("$this->table.id"=>$id))
                     ->where("($this->table.m_to = $users_id OR $this->table.m_from = $users_id)");
                     
        }
        else
        {
            $this->db->where(array("$this->table.m_to"=>$users_id, "$this->table.id"=>$id));
        }
                        
        return  $this->db->get($this->table)
                         ->row();
    }

   
     /**
     * get sent | received | favorite 
     * @return array
     * 
     **/
    public function get_sent_messages($users_id = NULL, $type = NULL, $filters = array())
    {
        $this->db->select(array(
            "(messages.id) id",
            "(messages.m_from) m_from ",
            "(messages.m_to) m_to",
            "(messages.message) message",
            "(messages.m_flag) m_flag",
            "(messages.m_favorite) m_favorite",
            "(messages.m_to_delete) m_to_delete",
            "(messages.m_from_delete) m_from_delete",
            "(messages.added) added",
        ));
        
        if($type === 's')
        {
            $this->db->select(array(
                                "users.id user_id",
                                "users.fullname",
                                "users.username",
                                "users.image",
                            ))
                    ->join('users', "users.id = messages.m_to", 'left');
        }
       
        if($type === 'r') // get received messages
        {
           $this->db->where(array("messages.m_to"=>$users_id, "messages.m_to_delete"=>'0', "messages.m_flag"=>'0'));
        }   


        if($type === 'f') // get favorite messages
        {
            $this->db->where(array("messages.m_to"=>$users_id, "messages.m_favorite"=>'1', "messages.m_to_delete"=>'0', "messages.m_flag"=>'0', "messages.m_flag"=>'0'));
        }
        
        if($type === 's') // get sent messages
        {
            
            $this->db->where(array("messages.m_from"=>$users_id, "messages.m_from_delete"=>'0', "messages.m_flag"=>'0'));
        }    

        $this->db->order_by("added", 'DESC');
                        
        return $this->db->limit($filters['limit'])
                        ->offset($filters['offset'])
                        ->get('messages')
                        ->result();
    }


    /**
     * save_messages
     *
     * @return array
     * 
     **/
    public function save_messages($data = array())
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }


    /**
     * delete_messages
     *
     * @return array
     * 
     **/
    public function delete_messages($users_id = NULL, $result = array(), $sent = FALSE)
    {
        if($sent) // in case of deleting sent message
           $m_whom = ($users_id !== $result->m_from ? 'm_to_delete' : 'm_from_delete');
         
        else
            $m_whom = ($users_id === $result->m_from ? 'm_from_delete' : 'm_to_delete');

        $this->db->where(array("$this->table.id"=>$result->id))
                 ->update($this->table, array("$m_whom"=>1));

        return TRUE;
    }

    /**
     * delete_messages_permanent
     *
     * @return array
     * 
     **/
    public function delete_messages_permanent($id = NULL, $result = array())
    {
        $this->db->delete($this->table, array('id' => $id)); 
        
        return TRUE;
    }

    /**
     * report_messages
     *
     * @return array
     * 
     **/
    public function report_messages($users_id = NULL, $result = array())
    {
        $this->db->where(array("$this->table.id"=>$result->id))
                 ->update($this->table, array("m_flag"=>1));

        return TRUE;
    }

    /**
     * favorite_messages
     *
     * @return array
     * 
     **/
    public function favorite_messages($users_id = NULL, $result = array())
    {
        $is_fav = $result->m_favorite == 1 ? 0 : 1;

        $this->db->where(array("$this->table.id"=>$result->id, "$this->table.m_to"=>$users_id))
                 ->update($this->table, array("m_favorite"=>$is_fav));

        return TRUE;
    }

    
}

/* Messages model ends*/