<?php
  class Result{
    private $result;
    private $isEmpty;
    public function __construct($fetch) {$ret=[];
      while ($row = $fetch->fetch_assoc()) {
        array_push($ret,(object)$row);
      }
      if(count($ret)>0){
        $this->result=$ret;
        $this->isEmpty=false;
      }else{
        $this->isEmpty=true;
      }
    }
    public function isEmpty(){
      return $this->isEmpty;
    }
  }
?>