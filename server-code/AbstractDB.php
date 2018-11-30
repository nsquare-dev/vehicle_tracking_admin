<?php

ini_set("display_errors", 0);

class AbstractDB {

    const HOST = 'localhost';        //host name
    const USER = 'root';             //user name DB location
    const PASS = 'root';
    const DB = 'e_scooter_new_db_1';
    const SUFFIX = 'es_';

    public function __construct() {
        $this->mysql = mysqli_connect(self::HOST, self::USER, self::PASS, self::DB);
    }

    public function query($sql) {
         mysqli_query($this->mysql, "set global character_set_results=utf8");
         $this->result  =   mysqli_query($this->mysql, $sql);
         if($this->result!=1){
             return mysqli_error($this->mysql);
         }else{
             return $this->result;
         }
    }

    public function getRow() {
        if(mysqli_num_rows($this->result)!=0){          
            if ($this->row = mysqli_fetch_assoc($this->result)) {
                return true;
            }          
        }
        return false;
    }

    public function getObj($className, $fileName) {
        require_once($fileName . '.php');
        $this->obj[$className] = new $className();
    }

    public function getField($filedName) {
        return $this->row[$filedName];
    }

    public function numofrows() {
        return mysqli_num_rows($this->result);
    }

    public function getInsertedId() {
        return mysqli_insert_id($this->mysql);
    }

    public function escape_string($str) {
        return mysqli_real_escape_string($this->mysql, $str);
    }

    public function multy_result() {
        $arr = NULL;
        while ($this->row = mysqli_fetch_assoc($this->result)) {
            $arr[] = $this->row;
        }
        return $arr;
    }

}

?>
