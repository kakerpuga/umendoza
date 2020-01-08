<?php
//get json

$input = file_get_contents('php://input');

//get into an array

$lead = json_decode($input,true);

//sanitize strings

$fullName=$lead["firstName"];
$fullMobilePhone=$lead["mobilePhone"];
$leadProduct=$lead["leadProduct"];
$leadSource=$lead["leadSource"];
$utmCampaign=$lead["utmCampaign"];
$utmContent=$lead["utmContent"];
$utmMedium=$lead["utmMedium"];
$utmSource=$lead["utmSource"];
$utmTerm=$lead["utmTerm"];

//define timezone

date_default_timezone_set('UTC');

//create generation date of lead

$dateCreated = date("Y-m-d H:i:s");

//generate an uid for lead

function generate_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x4000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

$uid=generate_uuid();

//POST to API

$APIkey='c78dfe328fa2a032e148286ad54cbe8c';

//API Url
$APIurl = 'https://'.$APIkey.'@api.masterdealer.co/leads';
 
//Initiate cURL.
$ch = curl_init($APIurl);
 
//The JSON data.
$jsonData = array(
    'id' => $uid,
    'first_name' => $fullName,
    'phone_mobile' => $fullMobilePhone,
    'primary_address_state' => 'Mendoza',
    'lead_source' => $leadSource,
    'assigned_user_id' => 'ece75cd5-5be0-0f75-8062-5dee551f5b8f',
    'date_entered' => $dateCreated,
    'status' => '02.Nuevo',
    'phone_fax' => '',
    'producto_c' => $leadProduct,
    'utm_campaign_c' => $utmCampaign,
    'utm_source_c' => $utmSource,
    'utm_content_c' => $utmContent,
    'utm_medium_c' => 'LandingPage',
    'utm_term_c' => $utmTerm
);
 
//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);
 
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
 
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
 
//Execute the request
$result = curl_exec($ch);

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if($httpcode=="201"){

  echo "success";

}else{

  echo "HTTP Code: ".$httpcode."|| Result: ".$result;

}


?>
