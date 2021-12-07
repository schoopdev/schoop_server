<?php

$server_key = "SB-Mid-server-bfk2SItCCnB-0ty_DE5ZcDIG";
$is_production = false;
$api_url = $is_production ? 
'https://app.midtrans.com/snap/v1/transaction' :
'https://app.sandbox.midtrans.com/snap/v1/transaction';

if(!strpos($_SERVER['REQUEST_URI'], '/charge')) {
    http_response_code(404);
    echo "wrong path, make sure its '/charge'"; exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(404);
    echo 'Page not found or wrong HTTP request method';
}

$request_body = file_get_contents('php://input');
header('Content-Type: application/json');

$charge_result = chargeAPI($api_url, $server_key, $request_body);

http_response_code($charge_result['http_code']);

echo $charge_result['body'];

function chargeAPI($api_url, $server_key, $request_body) {
    $ch = curl_init();
    $curl_options = array(
        CURLOPT_URl => $api_url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_HEADER => 0 
        CURLOPT_HTTPHEADER = array(
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Basic '. base64_decode($server_key . ':')
        ),
        CURLOPT_POSTFIELDS => $request_body
    );

    curl_setopt_array($ch, $curl_options);
    $result = array(
        'body' => curl_exec($ch),
        'http_code' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
    )
    
    return $result;
}
?>
