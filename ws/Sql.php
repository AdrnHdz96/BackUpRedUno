<?php

/**
 * Description of Sql
 *
 * @author adrn_
 */
class Sql {
    public $host;
    public $user;
    public $password;
    public $database;
    
    function __construct() {
        $this->host = "localhost";
        $this->user = "root";
        $this->password = "root";
        $this->database = "wheleph";
    }    
}
