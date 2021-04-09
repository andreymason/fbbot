<?php

// include 'MysqlConnection.php';
include 'Proxy.php';

class Adset
{

    function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function getAdsetById($adsetId, $accountData)
    {

        $proxy = new Proxy();
        // var_dump($accountData);
        $proxy_data_db = json_decode($accountData["proxy_data"], true);

        $request_data = [];
        $request_data["url"] = "https://graph.facebook.com/v10.0/" . trim($adsetId) . "?fields=name,adrules_governed,status&access_token=".$accountData['facebook_token'];
        $request_data["usernamePassword"] = $proxy_data_db["proxyUsername"].":".$proxy_data_db["proxyPassword"];
        $request_data["proxyIp"] = $proxy_data_db["proxyIp"];
        $request_data["proxyPort"] = $proxy_data_db["proxyPort"];
        $request_data["userAgent"] = $proxy_data_db["proxyUserAgent"];
        $request_data["requestType"] = "GET";
        $resp = $proxy->doRequest($request_data);

        return $resp;
    }
}
