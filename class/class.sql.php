<?php
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
      $result = $conn->query($sql);   
      $ret=[];
      while ($row = $result->fetch_assoc()) {
        array_push($ret,(object)$row);
      }
      $conn->close(); 
      if(count($ret)==1){
        $ret=$ret[0];
      }
      return (object)$ret;
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
    public function getRow($sql){
      $conn = $this->connect();
      $result = $conn->query($sql);
      while ($row = $result->fetch_assoc()) {
        $_row=[];
        foreach ($row as $key => $value) {
          if(is_numeric($value)){
            $_row[$key]=(int)$value;
          }else{
            $_row[$key]=$value;
          }
        }
      }
      $conn->close(); 
      return $_row;
    }
    public function getCol($sql, $col){
      return $this->getRow($sql)[$col];
    }
    public function update($table, $values, $where){$set=[];
      $conn = $this->connect();
      foreach ($values as $key => $value){
        if(_is_double($value)){
          $set[]="{$key}={$value}";
        }else{
          $set[]="{$key}='{$value}'";
        }
      }
      $_set=implode(',', $set);
      $sql="UPDATE {$table} SET {$_set} WHERE {$where}";
      $result = $conn->query($sql);
      $conn->close(); 
      return ['result'=>$result, 'sql'=>$sql];
    }
    public function insert($table, $values){$set=$_values=[];
      $conn = $this->connect();
      foreach ($values as $key => $value){
        if(_is_double($value)){
          $_values[]="{$value}";
        }else{
          $_values[]="'{$value}'";
        }
        $set[]=$key;
      }
      $_set=implode(',', $set);
      $__values=implode(',', $_values);
      $sql="INSERT INTO {$table} ({$_set}) VALUES ({$__values})";
      $result = $conn->query($sql);
      $conn->close(); 
      return ['result'=>$result, 'sql'=>$sql];
    }    
    public function delete($table, $where){
      $conn = $this->connect();
      $sql="DELETE FROM {$table} WHERE {$where}";
      $result = $conn->query($sql);
      $conn->close(); 
      return ['result'=>$result, 'sql'=>$sql];
    }
  } 
  
?>