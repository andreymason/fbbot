<?php

/**
 *
 */
class MysqlConnection
{
  private $conn;

  function __construct()
  {    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "traffbraza_db";

    // Create connection
    $this->conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($this->conn, 'utf8');
    // Check connection
    if (!$this->conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
  }

  public function getConn()
  {
    return $this->conn;
  }
}
 ?>
