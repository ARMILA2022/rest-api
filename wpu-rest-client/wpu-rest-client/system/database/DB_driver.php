<?php

defined('BASEPATH') OR exit('No direct script access allowed');

abstract class CI_DB_driver {

    public $hostname;
    public $username;
    public $password;
    public $database;
    public $dbdriver;
    public $dbprefix;
    public $char_set;
    public $dbcollat;
    public $encrypt = FALSE;
    public $swap_pre = '';
    public $port = '';
    public $pconnect = FALSE;
    public $conn_id = FALSE;
    public $result_id = FALSE;
    public $db_debug = FALSE;
    public $benchmark = 0;
    public $query_count = 0;
    public $bind_marker = '?';
    public $save_queries = TRUE;
    public $queries = [];
    public $query_times = [];
    public $data_cache = [];
    public $trans_enabled = TRUE;
    public $trans_strict = TRUE;
    public $_trans_depth = 0;
    public $_trans_status = TRUE;
    public $_trans_failure = FALSE;
    public $cache_on = FALSE;
    public $cachedir = '';
    public $cache_autodel = FALSE;
    public $CACHE;
    public $failover = []; // ✅ Ditambahkan untuk menghindari error deprecated

    public $conn;
    public $conn_id_write = NULL;
    public $conn_id_read = NULL;

    protected $_random_keyword;

    public function __construct($params)
    {
        foreach ($params as $key => $val)
        {
            if (property_exists($this, $key))
            {
                $this->$key = $val;
            }
        }

        log_message('debug', 'Database Driver Class Initialized');
    }

    abstract public function db_connect($persistent = FALSE);
    abstract public function reconnect();
    abstract public function db_select();
    abstract public function db_set_charset($charset);

    public function initialize()
    {
        $this->conn_id = $this->db_connect($this->pconnect);

        if ( ! $this->conn_id)
        {
            log_message('error', 'Unable to connect to the database');
            show_error('Unable to connect to your database server using the provided settings.');
        }

        if ( ! $this->db_select())
        {
            log_message('error', 'Unable to select database: '.$this->database);
            show_error('Unable to select database: '.$this->database);
        }

        if (isset($this->char_set) && ! $this->db_set_charset($this->char_set))
        {
            log_message('error', 'Unable to set database connection charset: '.$this->char_set);
            show_error('Unable to set database connection charset: '.$this->char_set);
        }

        return TRUE;
    }

    public function platform()
    {
        return $this->dbdriver;
    }

    public function version()
    {
        if (isset($this->conn_id->server_info))
        {
            return $this->conn_id->server_info;
        }
        return FALSE;
    }

    public function query($sql, $binds = FALSE, $return_object = NULL)
    {
        if ($sql === '')
        {
            log_message('error', 'Invalid query: '.$sql);
            return ($this->db_debug) ? $this->display_error('db_invalid_query') : FALSE;
        }

        if ($this->save_queries === TRUE)
        {
            $this->queries[] = $sql;
        }

        $this->_reset_write = TRUE;
        return $this->simple_query($sql);
    }

    public function simple_query($sql)
    {
        return $this->_execute($sql);
    }

    abstract protected function _execute($sql);

    public function trans_start()
    {
        $this->trans_begin();
    }

    public function trans_complete()
    {
        if ($this->_trans_status === FALSE)
        {
            $this->trans_rollback();
        }
        else
        {
            $this->trans_commit();
        }
    }

    public function trans_status()
    {
        return $this->_trans_status;
    }

    abstract public function trans_begin($test_mode = FALSE);
    abstract public function trans_commit();
    abstract public function trans_rollback();

    public function close()
    {
        if (is_resource($this->conn_id) OR is_object($this->conn_id))
        {
            $this->_close();
            $this->conn_id = FALSE;
        }
    }

    abstract protected function _close();

    protected function display_error($error = '', $swap = '', $native = FALSE)
    {
        $LANG =& load_class('Lang', 'core');
        $LANG->load('db');

        $message = ($swap !== '') ? str_replace('%s', $swap, $LANG->line($error)) : $LANG->line($error);

        show_error($message, 500, 'Database Error');
    }
}
