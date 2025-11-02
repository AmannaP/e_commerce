<?php
// settings/db_class.php

require_once 'db_cred.php';

/**
 *@version 1.1
 */
if (!class_exists('db_conn')) {
    class db_conn
    {
        //properties
        public $db = null;
        public $results = null;

        public function __construct() {
        try {
            $this->db = new PDO(
                "mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD
            );
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("DB Connection failed: " . $e->getMessage());
        }
        }

        //connect
        /**
         * Database connection
         * @return boolean
         **/
        function db_connect()
        {
            //connection
            $this->db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            //test the connection
            if (mysqli_connect_errno()) {
                return false;
            } else {
                return true;
            }
        }

        function db_conn()
        {
            //connection
            $this->db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            //test the connection
            if (mysqli_connect_errno()) {
                return false;
            } else {
                return $this->db;
            }
        }

        //execute a query for SELECT statements
        /**
         * Query the Database for SELECT statements
         * @param string $sqlQuery
         * @return boolean
         **/
        function db_query($sqlQuery)
        {
            if (!$this->db_connect()) {
                return false;
            } elseif ($this->db == null) {
                return false;
            }

            //run query 
            $this->results = mysqli_query($this->db, $sqlQuery);

            if ($this->results == false) {
                return false;
            } else {
                return true;
            }
        }

        //execute a query for INSERT, UPDATE, DELETE statements
        /**
         * Query the Database for INSERT, UPDATE, DELETE statements
         * @param string $sqlQuery
         * @return boolean
         **/
        function db_write_query($sqlQuery)
        {
            if (!$this->db_connect()) {
                return false;
            } elseif ($this->db == null) {
                return false;
            }

            //run query 
            $result = mysqli_query($this->db, $sqlQuery);

            if ($result == false) {
                return false;
            } else {
                return true;
            }
        }

        //fetch a single record
        /**
         * Get a single record
         * @param string $sql
         * @return array|false
         **/
        function db_fetch_one($sql)
        {
            // if executing query returns false
            if (!$this->db_query($sql)) {
                return false;
            }
            //return a record
            return mysqli_fetch_assoc($this->results);
        }

        //fetch all records
        /**
         * Get all records
         * @param string $sql
         * @return array|false
         **/
        function db_fetch_all($sql)
        {
            // if executing query returns false
            if (!$this->db_query($sql)) {
                return false;
            }
            //return all records
            return mysqli_fetch_all($this->results, MYSQLI_ASSOC);
        }

        //count data
        /**
         * Get count of records
         * @return int|false
         **/
        function db_count()
        {
            //check if result was set
            if ($this->results == null) {
                return false;
            } elseif ($this->results == false) {
                return false;
            }

            //return count
            return mysqli_num_rows($this->results);
        }

        function last_insert_id()
        {
            return mysqli_insert_id($this->db);
        }
    }
}
?>