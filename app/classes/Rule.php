<?php

class Rule
{
    private $userId;
    private $conn;

    function __construct($userId = null)
    {
        //set user id;
        $this->userId = $userId;
        //set connection.
        if (!in_array("mysqlConnection", get_declared_classes())) {
            $this->setConnection();
        }
    }

    public function saveRule($ruleData, $uid, $token)
    {
        $name = json_decode($ruleData)->name;
        $sql = 'INSERT INTO rules_templates (name, created_by, rule_data, token) 
        VALUES (N\'' . $name . '\', \'' . $uid . '\', \'' . $ruleData . '\', \'' . $token . '\')';

        if (mysqli_query($this->conn, $sql)) {
            return ["resp" => "true"];
        } else {
            return ["resp" => "Error: " . $sql . "<br>" . mysqli_error($this->conn)];
        }
    }

    public function getRulesByUidFromDb($uid)
    {
        $sql = "SELECT * FROM rules_templates WHERE created_by = '$uid'";
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

    public function getRuleInfoByIdFromFacebook($ruleId, $accountData)
    {
        $proxy = new Proxy();
        $proxy_data_db = json_decode($accountData["proxy_data"], true);

        $request_data = [];
        $request_data["url"] = "https://graph.facebook.com/v10.0/" . $ruleId . "?fields=name,evaluation_spec,execution_spec,schedule_spec,status,account_id,created_by,created_time,id,updated_time&access_token=".$accountData['facebook_token'];
        $request_data["usernamePassword"] = $proxy_data_db["proxyUsername"].":".$proxy_data_db["proxyPassword"];
        $request_data["proxyIp"] = $proxy_data_db["proxyIp"];
        $request_data["proxyPort"] = $proxy_data_db["proxyPort"];
        $request_data["userAgent"] = $proxy_data_db["proxyUserAgent"];
        $request_data["requestType"] = "GET";
        $resp = $proxy->doRequest($request_data);

        return $resp;
    }

    public function getAllStatuses()
    {
        return array(
            "DELETED",
            "ENABLED",
            "DISABLED"
        );
    }

    public function getAllEvaluationOperators()
    {
        return array(
            "LESS_THAN",
            "GREATER_THAN",
            "EQUAL",
            "NOT_EQUAL",
            "IN_RANGE",
            "NOT_IN_RANGE",
            "IN",
            "NOT_IN",
            "CONTAIN",
            "NOT_CONTAIN",
            "ANY",
            "ALL",
            "NONE"
        );
    }

    public function getAllExecutionTypes()
    {
        return array("PING_ENDPOINT", "NOTIFICATION", "PAUSE", "REBALANCE_BUDGET", "CHANGE_BUDGET", "CHANGE_BID", "ROTATE", "UNPAUSE", "CHANGE_CAMPAIGN_BUDGET", "ADD_INTEREST_RELAXATION", "ADD_QUESTIONNAIRE_INTERESTS", "INCREASE_RADIUS", "UPDATE_CREATIVE");
    }

    public function getAllScheduleTypes()
    {
        return array("DAILY", "HOURLY", "SEMI_HOURLY", "CUSTOM");
    }

    public function getAllEvaluationTypes()
    {
        return array("SCHEDULE", "TRIGGER");
    }

    private function setConnection()
    {
        $Mysqli = new MysqlConnection();
        $this->conn = $Mysqli->getConn();
    }
}
