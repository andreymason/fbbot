<?php

/**
 * Files class to handle everything what have to do with files
 */
 include 'MysqlConnection.php';


class Files
{

  private $path;
  private $userId;
  private $response;
  private $conn;


  function __construct($userId = null){

    //set user id;
    $this->userId = $userId;

    //set connection.
    $this->setConnection();

  }

  public function getAllClients() {
    $allRecords = [];

    $sql = "SELECT * FROM users WHERE rank = 1";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
        $allRecords[] = $row;
        }
        return $allRecords;
    } else {
        return false;
    }
  }

  public function updateTheFile($data) {

    $sql = "UPDATE files SET 
    fileName='".$data["fileName"]."',
    result='".$data["result"]."',
    type='".$data["type"]."',
    contactPerson='".$data["contactPerson"]."',
    clientId='".$data["clientId"]."'
    WHERE id = ".$data["fileId"];

    if (mysqli_query($this->conn, $sql)) {
        return true;
    } else {
        echo "Error updating record: " . mysqli_error($this->conn);
    }
  }
  #=================================================================================================================
  #=================================================================================================================

  private function deleteFromServer($fileId){

    $path = "../../uploads/".$this->userId."/";

      $fileName = $this->getFilesByFileId($fileId);
      $fileName = $fileName["fileName"];

      if(unlink($path.$fileName)){
        return true;
      }
  }

  #=================================================================================================================
  #=================================================================================================================

  public function deleteFile($fileId){
    //delete file from server
    $this->deleteFromServer($fileId);
    $this->deleteFromDb($fileId);

    return true;
  }

  #=================================================================================================================
  #=================================================================================================================

  private function deleteFromDb($fileId){

      //delete frile from DB
      $sql = "DELETE FROM files WHERE id = '$fileId' AND userId = '$this->userId'";

      if (mysqli_query($this->conn, $sql)) {
        return true;

      } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($this->conn);
      }
  }
  #=================================================================================================================
  #=================================================================================================================

  public function checkUserUploadDir(){
    //define path.
    $this->path = "../../uploads/".$this->userId."/";

    //check if path exist.
    if(file_exists($this->path)){
      //path exist, return true.
      $this->response = true;
      return true;
    }else{
      //paht doesn´t exist, create one.
      if(mkdir($this->path) && chmod($this->path, 0777)){
        //return that it´s the user´s first upload.
        $this->response = "first";
        return true;

      }else{
        echo "error by creating folder";
      }
    }
  }
  #=================================================================================================================
  #=================================================================================================================

  public function uploadFile($fileLocation, $fileName, $fileSize, $data){

    //second check the size.
    if($fileSize > 10485760){
      die("file is to big = ".$fileSize);
    }

    //define new path
    $newPath = $this->path.$fileName;


    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    if (move_uploaded_file($fileLocation, $newPath)) {
      if($this->setFileInDb($newPath, $fileName, $data)){
        return $this->response;
      }

    } else {
        echo "error";
    }
  }

  #=================================================================================================================
  #=================================================================================================================
  private function generateRandomString($length = 100) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }
  #=================================================================================================================
  #=================================================================================================================

  private function setFileInDb($newPath, $fileName, $data){
    $date_now = "10-10-2020";
    $theHash = $this->generateRandomString();

    $sql = 'INSERT INTO files (
      userId, 
      fileName,
      filePath, 
      status,
      clientId,
      fileDownloadId,
      type,
      contactPerson,
      date,
      result
      ) VALUES (
        "'.$this->userId.'",
        "'.$fileName.'" ,
        "'.substr($newPath, 3).'",
        "uploading",
        "'.$data["clientId"].'",
        "'.$theHash.'",
        "'.$data["type"].'",
        "'.$data["contactPerson"].'",
        "'.$date_now.'",
        "'.$data["result"].'"
        )';

    if (mysqli_query($this->conn, $sql)) {
      if($this->setStatus("new", $theHash)){
        return true;
      }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($this->conn);
    }

  }

  #=================================================================================================================
  #=================================================================================================================

  private function setConnection(){
    $Mysqli = new MysqlConnection();
    $this->conn = $Mysqli->getConn();
  }
 #=================================================================================================================
  #=================================================================================================================


  public function getAllForOneUser($userId) {
    $allRecords = [];

    $sql = "SELECT * FROM files WHERE userId = ".$userId;
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $allRecords[] = $row;
      }
      return $allRecords;
    } else {
      return false;
    }
  }
  #=================================================================================================================
  #=================================================================================================================

  public function getAll(){
    $allRecords = [];

    $sql = "SELECT * FROM files";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $allRecords[] = $row;
      }
      return $allRecords;
    } else {
      return false;
    }
  } 
  
  #=================================================================================================================
  #=================================================================================================================

  public function getOneFile($fileId){
    $allRecords = [];

    $sql = "SELECT * FROM files WHERE id = '$fileId'";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        return $row;
      }
    } else {
      return false;
    }
  }

  #=================================================================================================================
  #=================================================================================================================

  public function getOneFilesd(){
    $allRecords = [];

    $sql = "SELECT * FROM files WHERE userId = '$this->userId'";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
        $allRecords[] = $row;
      }
      return $allRecords;
    } else {
      return false;
    }
  }

  #=================================================================================================================
  #=================================================================================================================

  public function getFilesByFileId($fileId){

    $sql = "SELECT * FROM files WHERE userId = '$this->userId' AND id = ".$fileId;

    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $row = mysqli_fetch_assoc($result);

        return $row;
    }

  }
  #=================================================================================================================
  #=================================================================================================================
  public function getDownloadLink($hash){
    $sql = "SELECT * FROM files WHERE fileDownloadId = '$hash'";
    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        $row = mysqli_fetch_assoc($result);

        if($row["downloadLinkStatus"] == 1){
          return $row;
        }else{
          return "link-off";
        }

    }else{
      return "no-file";
    }
  }
  #=================================================================================================================
  #=================================================================================================================

  public function getNewFiles($lastFileId){
    $sql = "SELECT * FROM files WHERE userId = '$this->userId' AND id > '$lastFileId'";

    $result = mysqli_query($this->conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if($this->setStatus("finished", $row["downloadLink"])){
          return $row;
        }
    }else{
      return "empt";

    }
  }
  #=================================================================================================================
  #=================================================================================================================
  public function setStatus($status, $hash){
    $sql = "UPDATE files SET status='$status' WHERE userId = '$this->userId' AND fileDownloadId = '$hash'";

    if (mysqli_query($this->conn, $sql)) {
        return true;
    } else {
        echo "Error updating record: " . mysqli_error($this->conn);
    }
  }
  #=================================================================================================================
  #=================================================================================================================
  public function setDownloadLinkStatus($fileDownloadId, $status){
    $sql = "UPDATE files SET downloadLinkStatus='$status' WHERE userId = '$this->userId' AND fileDownloadId = '$fileDownloadId'";

    if (mysqli_query($this->conn, $sql)) {
        return true;
    } else {
        echo "Error updating record: " . mysqli_error($this->conn);
    }
  }
  #=================================================================================================================
  #=================================================================================================================

  public function deleteDownloadLink($fileDownloadId){
    if($this->setDownloadLinkStatus($fileDownloadId, "0")){
      return $fileDownloadId;
    }
  }
  #=================================================================================================================
  #=================================================================================================================

  public function createDownloadLink($fileDownloadId){
    if($this->setDownloadLinkStatus($fileDownloadId, "1")){
      return $fileDownloadId;
    }
  }
  #=================================================================================================================
  #=================================================================================================================


}
 ?>
