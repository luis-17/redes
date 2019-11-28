<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General extends CI_Model
{

    /**
     *
     * Modelo: General
     * Relación con la BD MySQL
     *
     */
 
    /*protected $_table_name = '';*/
    /*protected $_order_by = 'id asc';*/

    protected $_primary_key = 'id';
    protected $_primary_filter = 'intval';
    public $rules = array();
    protected $_timestamps = true;
 
    function __construct()
    {
        parent::__construct();
    }
 
    public function array_from_post($fields)
    {
        $data = array();
        foreach ($fields as $field) {
            $data[$field] = $this->input->post($field);
        }
        return $data;
    }
 
    public function get($table_name, $id = null, $single = false, $order = false)
    {
 
        if ($id != null) {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->where($this->_primary_key, $id);
            $method = 'row';
        } elseif ($single == true) {
            $method = 'row';
        } else {
            $method = 'result';
        }
 
        if($order)
            $this->db->order_by($order);
        
        return $this->db->get($table_name)->$method();
    }
 
    public function get_by($table_name, $where, $single = false, $order = false, $limit = false, $offset = false)
    {
        if ($where != false)
        $this->db->where($where);
        
        if($limit)
        $this->db->limit($limit,$offset);
        
        return $this->get($table_name, null, $single, $order);
    }
 
    public function search($categorias, $articulos, $cadena, $title, $match, $order=false)
    {

        $this->db->like($title, $match); 

        if($order)
            $this->db->order_by($order);

        $this->db->select($cadena);
        $this->db->from($categorias);
        $this->db->join($articulos, $categorias.'.id = '.$articulos.'.'.$categorias.'_id');

        return $this->db->get()->result();
    }

    public function join($categorias, $articulos, $cadena, $where = FALSE, $order=false, $limit=false, $offset=false)
    {
        if ($where != false)
        $this->db->where($where);

        if($limit)
        $this->db->limit($limit,$offset);

        if($order)
        $this->db->order_by($order);

        $this->db->select($cadena);
        $this->db->from($categorias);
        $this->db->join($articulos, $categorias.'.id = '.$articulos.'.'.$categorias.'_id');

        return $this->db->get()->result();
    }

    public function distinct($table_name, $select=false, $where=false, $order = false)
    {
        if ($select != false)
            $this->db->distinct(); $this->db->select($select);

        if ($where != false)
            $this->db->where($where);

        if($order)
            $this->db->order_by($order);

        return $this->db->get($table_name)->result();
    }

    public function save($table_name, $data, $id = null)
    {

         // Set timestamps
        if ($this->_timestamps == true) {
            $user_now = $this->session->userdata('id');
            $id || $data['usuario_creacion'] = $user_now;
            $data['usuario_modificacion'] = $user_now;
        }

        // Set timestamps
        if ($this->_timestamps == true) {
            $now = date('Y-m-d H:i:s');
            $id || $data['fecha_creacion'] = $now;
            $data['fecha_modificacion'] = $now;
        }
 
        // Insert
        if ($id === null) {
            !isset($data[$this->_primary_key]) || $data[$this->_primary_key] = null;
            $this->db->set($data);
            $this->db->insert($table_name);
            $id = $this->db->insert_id();
        }
        // Update
        else {
            $filter = $this->_primary_filter;
            $id = $filter($id);
            $this->db->set($data);
            $this->db->where($this->_primary_key, $id);
            $this->db->update($table_name);
        }
 
        return $id;
    }
 
    public function delete($table_name, $id)
    {
        $filter = $this->_primary_filter;
        $id = $filter($id);
 
        if (!$id) {
            return false;
        }
        $this->db->where($this->_primary_key, $id);
        $this->db->limit(1);
        $this->db->delete($table_name);
    }

    public function delete_all($table_name, $where)
    {
        $this->db->where($where);
        $this->db->delete($table_name);
    }
}