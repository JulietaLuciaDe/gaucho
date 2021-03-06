<?php

class Database{
    private $rutaConfig;
    private $connection;

 public function __construct($rutaConfig){
    $this->rutaConfig=$rutaConfig;
    $this->connection=false;
    $this->connect();

 }

public function __destruct(){
    $this->disconnect();
}


 private function connect(){
    $config=parse_ini_file($this->rutaConfig);
    $connection = mysqli_connect($config["host"],$config["user"],$config["pass"],$config["database"]);
    if(!$connection){
       die('No se ha podido conectar con la base de datos. Contacte al administrador');
    }
    $this->connection = $connection;
}

private function disconnect(){
    mysqli_close($this->connection);
}

public function query($sql){
    return mysqli_query($this->connection, $sql);
}

public function queryResult($sql){
     $result = mysqli_query($this->connection, $sql);
     $resultado = mysqli_fetch_all($result , MYSQLI_ASSOC);
     return $resultado;
}
}


?>