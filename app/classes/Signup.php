<?php

/**
 *
 */
 include 'MysqlConnection.php';

class Signup{

  private $name;
  private $email;
  private $password;
  private $password2;

  function __construct($name, $email, $password, $password2){
    $this->name = $this->setClearString($name);
    $this->email = $this->setClearString($email);
    $this->password = $password;
    $this->password2 =$password2;

    $this->setConnection();
  }

  private function setClearString($string){
    return addslashes(stripslashes($string));
  }

  public function signUp(){
    //check if user already exist.
    if($this->checkUserExist()){
      return "you already have an account";
    }else{
      if($this->finishSignUp()){
        return "signedUp";
      }else{
        echo mysql_error($this->conn);
      }

    }
  }

  private function checkName(){
    if(ctype_alpha($this->name)){
      return true;
    }else{
      return false;
    }
  }

  private function checkEmail()
  {
    if(filter_var($this->email, FILTER_VALIDATE_EMAIL)){
      return true;
    }else{
      return false;
    }
  }

  private function checkPasswords()
  {
    if($this->password == $this->password2){
      if(strlen($this->password) > 6){
        return true;
      }else{
        die("password is too short");
      }
    }else{
      die("passwords are not the same");
    }
  }

  private function finishSignUp(){
    if($this->checkName()){
      //name passed
      if($this->checkEmail()){
        //email passed
        if($this->checkPasswords()){
          //passwords passed
          $sql = 'INSERT INTO users (name, email, pass, rank) VALUES ("'.$this->name.'", "'.$this->email.'", "'. hash("sha256", $this->password).'", "1")';

          if (mysqli_query($this->conn, $sql)) {
              return true;
          } else {
              echo "Error: " . $sql . "<br>" . mysqli_error($this->conn);
          }


        }
      }
    }else{
      die("Not valid name");
    }
  }

  private function setConnection(){
    $Mysqli = new MysqlConnection();
    $this->conn = $Mysqli->getConn();
  }

  private function checkUserExist(){

    $sql = "SELECT * FROM users WHERE email = '$this->email'";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      return true;
    } else {
      return false;
    }
  }


}
 ?>
