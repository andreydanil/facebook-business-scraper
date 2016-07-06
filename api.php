<?php
header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

error_reporting(E_ALL);

require 'config.php';

$_POST = (array) json_decode(file_get_contents("php://input"));
$query = @urlencode(@trim(strip_tags($_POST['query'])));
$limit = @trim(strip_tags($_POST['limit'])) OR 20;
$type = @trim(strip_tags($_POST['type'])) OR 'page';
$userAccessToken = "&access_token=" . @trim(strip_tags($_POST['accessToken'])) OR null;
$accessToken = "&access_token=" . FACEBOOK_APP_ID . "|" . FACEBOOK_APP_SECRET . "";

if ($type != 'page' && $type != 'place')
    $accessToken = $userAccessToken;
$res = ['type' => $type,'data'=>[],'error' => false];
$fields = null;
if($type == 'page'){
    $fields = '&fields=id,name,contact_address,phone,emails,website,fan_count,link,is_verified,about,picture';
    $res['fields'] = ['ID', 'Name', 'Address','Phone','Emails','Website', 'Likes'];
    $res['eFields'] = ['ID', 'Name', 'Address','Phone','Emails','Website', 'Likes','Link','Is Verified','About','Picture'];
}
elseif($type == 'group') {
    $fields = '&fields=id,icon,name,description,email,privacy,cover';
    $res['fields'] = ['ID', 'Name', 'Description','Email','Privacy'];
    $res['eFields'] = ['ID', 'Name', 'Description','Email','Privacy'];
}
elseif($type == 'user') {
    $fields = '&fields=id,name,birthday,bio,email,gender,interested_in,is_verified,link,location,meeting_for,religion,relationship_status,website,work,cover,devices,education,hometown,languages,picture,age_range';
    $res['fields'] = ['ID', 'Name', 'Age Range','Email','Gender','Devices'];
    $res['eFields'] = ['ID', 'Name', 'Birthday','Bio','Email','Gender', 'interested in','is verified','link','location','meeting for','religion','Relationship status','Website','Work','Cover','Devices','Education','Hometown','Languages','Picture', 'Age Range'];
}
elseif($type == 'event') {
    $fields = '&fields=id,name,attending_count,noreply_count,maybe_count,interested_count,declined_count,owner,place,category,can_guests_invite,cover,start_time,end_time,type,ticket_uri';
    $res['fields'] = ['ID', 'Name', 'Attending Count','Place','Owner','Type', 'Time'];
    $res['eFields'] = ['ID', 'Name', 'Attending Count','noreply_count','Place','maybe_count','interested_count','declined_count', 'owner','place','category','can_guests_invite','cover','start_time','end_time','type','ticket_uri'];
}
elseif($type == 'place') {
    $fields = '&fields=id,name,location';
    $res['fields'] = ['ID', 'Name', 'Location', 'Map'];
    $res['eFields'] = ['ID', 'Name', 'Location'];
}

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/v2.6/search?q=$query&type=$type&limit=$limit" . $accessToken.$fields); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);      

$items = json_decode($output);

$res['data'] = $items->data;

echo json_encode($res);
