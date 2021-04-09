<?php

class Proxy
{

    public function doRequest($data)
    {
        $url = $data["url"];
        $usernamePassword = $data["usernamePassword"];
        $proxyIp = $data["proxyIp"];
        $proxyPort = $data["proxyPort"];
        $userAgent = $data["userAgent"];
        $requestType = $data["requestType"];
        // $curlType = $data["curlType"]; // socks5

        $ch = curl_init();

        if ($requestType == "POST") {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $postBody = (isset($data["postBody"])) ? $data["postBody"] : "";
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($ch, CURLOPT_PROXY, $proxyIp);
        curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $usernamePassword);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $curlResp = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        return $curlResp;
    }
}
