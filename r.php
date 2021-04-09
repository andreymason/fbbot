<?php

$url = 'https://graph.facebook.com/v10.0/120330000088106717?fields=name,evaluation_spec,execution_spec,schedule_spec,status,account_id,created_by,created_time,id,updated_time&access_token=EAALj776y8u4BAKapjE3sFM6OVDFr5PxG8Ye7AZCqjfF8L3yu8w8qp2GHZBiQUZBMF65qXmyZB7pe6qZCYxHbc4dQtiBxTdeZBehg1r5w13JdwZCZAUBLKuqwMHPZACUE3edwdJbpguhF2g7FliokiAaMWBqYv5QTx0VzTNguZAvM1f4CjpAWSbRBleZA7zfZBRSAMccZD';
// $proxy = '213.166.82.159:53192';
// $proxyauth = '8u52uevp:JMpfN4Ky';

$loginpassw = 'JMpfN4Ky:8u52uevp';
$proxy_ip = '194.32.228.195';
$proxy_port = '61681';
$agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, $agent);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $loginpassw);
// curl_setopt($ch, CURLOPT_VERBOSE, true);

$data = curl_exec($ch);
curl_close($ch);

var_dump($data);


$proxy="194.32.228.195:61681";
$proxy_log_pass="JMpfN4Ky:8u52uevp";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_PROXY, $proxy);
curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy_log_pass);
curl_setopt($ch, CURLOPT_HEADER, true);
$curl_scraped_page = curl_exec($ch);
$error = curl_error($ch);
echo $error;
echo $curl_scraped_page;
curl_close($ch);