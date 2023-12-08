<?php

use LDAP\Result;

  require_once('objects/obj.result.php');
  class SQL{
    function connect(){
      if(is_dev()){
        $servername="localhost";
        $username="root";
        $password="root";
        $dbname="myBills";        
      }else{
        $servername="localhost";
        $username="id20713023_root";
        $password="123Testes@123";
        $dbname="id20713023_teoplace";
      }      
      $conn = new mysqli($servername, $username, $password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }else{
        return $conn;
      }
    }
    public function get($sql){
      $conn = $this->connect();
      $result=new \Result($conn->query($sql));
      $conn->close();
      return $result;
    }
    public function getArray($sql){
      $conn = $this->connect();
      $result = $conn->query($sql);   
      $ret=[];
      while ($row = $result->fetch_assoc()) {
        $_row=[];
        foreach ($row as $key => $value) {
          if(is_numeric($value)){
            $_row[$key]=(int)$value;
          }else{
            $_row[$key]=$value;
          }
        }
        array_push($ret,$_row);
      }
      $conn->close(); 
      return $ret;
    }
  } 
  
?>