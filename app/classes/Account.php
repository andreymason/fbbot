<?php


class Account
{
    private $path;
    private $userId;
    private $response;
    private $conn;

    function __construct($userId = null)
    {
        //set user id;
        $this->userId = (int)$userId;
        //set connection.
        if (!in_array("mysqlConnection", get_declared_classes())) {
            $this->setConnection();
        }
    }


    public function createAccount($data)
    {
        $token_check = $this->getExistVerify("facebook_token", $data["facebook_token"], $data["user_id"]);
        $id_check = $this->getExistVerify("facebook_id", $data["id"], $data["user_id"]);
        // $uid_check = $this->getAccount("created_by", $data["user_id"]);
        // var_dump($token_check);
        // var_dump($uid_check);

        if ($token_check === false) {
            if ($id_check === false) {
                $sql = 'INSERT INTO accounts (name, facebook_id, facebook_token, created_by, proxy_data) 
                VALUES (N\'' . $data["name"] . '\', 
                \'' . $data["id"] . '\', 
                \'' . $data["facebook_token"] . '\', 
                \'' . $data["user_id"] . '\',
                \'' . json_encode($data["proxy_data"]) . '\')';

                if (mysqli_query($this->conn, $sql)) {
                    return ["resp" => "true"];
                } else {
                    return ["resp" => "Error: " . $sql . "<br>" . mysqli_error($this->conn)];
                }
            } else {
                // we have this id, update token
                return $this->updateExistingClient($data);
            }
        } else {
            $this->updateExistingClient($data);
            return ["resp" => "token_exist", "data" => $token_check];
        }
    }

    public function updateExistingClient($data)
    {
        $sql = "UPDATE accounts
        SET 
        facebook_token = '" . $data["facebook_token"] . "'
        WHERE facebook_id = " . $data["id"];

        if (mysqli_query($this->conn, $sql)) {
            return ["resp" => "updated", "name" => $data["name"]];
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($this->conn);
        }
    }

    public function getAccountsByUid($uid)
    {
        $sql = "SELECT * FROM accounts WHERE created_by = '$uid'";
        $result = mysqli_query($this->conn, $sql);
        $allRecords = array();
        // to prevent ?????? ???? 
        mysqli_query($this->conn, "SET NAMES utf8");
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $allRecords[] = $row;
                }

                return $allRecords;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getAccount(String $by, $needle)
    {
        $sql = "SELECT * FROM accounts WHERE $by = '$needle'";
        $result = mysqli_query($this->conn, $sql);
        // to prevent ?????? ???? 
        mysqli_query($this->conn, "SET NAMES utf8");
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    public function getAccountSafe(String $by, $needle)
    {
        $sql = "SELECT * FROM accounts WHERE $by = '$needle' AND created_by = $this->userId";
        $result = mysqli_query($this->conn, $sql);
        // to prevent ?????? ???? 
        mysqli_query($this->conn, "SET NAMES utf8");
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function getExistVerify(String $by, $needle, $uid)
    {
        $sql = "SELECT * FROM accounts WHERE $by = '$needle' AND created_by = '$uid'";
        $result = mysqli_query($this->conn, $sql);
        // to prevent ?????? ???? 
        mysqli_query($this->conn, "SET NAMES utf8");
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    private function setConnection()
    {

        $Mysqli = new MysqlConnection();
        $this->conn = $Mysqli->getConn();
    }
}
