<?php

/**
 *
 */
 include 'MysqlConnection.php';

class Signin
{
  private $email;
  private $password;
  private $conn;

  function __construct($email, $password){
    $this->email = $this->setClearString($email);
    $this->password = $password;

    $this->setConnection();
  }

  private function setClearString($string){
    return addslashes(stripslashes($string));
  }

  public function finish_auth($cookies){

      $sql = "SELECT * FROM users WHERE email = '$this->email'";
      $result = mysqli_query($this->conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          if($row["pass"] == hash("sha256", $this->password)){

            if($cookies == 1){
              setcookie("UserEmail", $row["email"], time()+(3600 * 24 * 30), "/");
              setcookie("UserId", $row["id"], time()+(3600* 24 * 30), "/");

            }else{
              $_SESSION["UserEmail"] = $row["email"];
              $_SESSION["UserId"] = $row["id"];
              $_SESSION["Rank"] = $row["rank"];
            }
            if ( $row["rank"] == "1" ) {
              header("Location: /home");
            }

            if ( $row["rank"] == "2" ) {
              header("Location: /AdminHome");
            }

            die("redirecting...");
          }else{
            header("Location: /signin?msg=wrong password");
            die("redirect, wrong password.");
          }
        }
      } else {
        header("Location: /signin?msg=email doesn't exist");
        die("redirect, wrong email.");
      }
    }


  private function setConnection(){

    $Mysqli = new MysqlConnection();
    $this->conn = $Mysqli->getConn();
  }

}
 ?>
