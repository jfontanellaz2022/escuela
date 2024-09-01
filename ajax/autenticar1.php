<?php

define("RECAPTCHA_V3_SECRET_KEY", '6Ld6Y6oaAAAAAIsgbDUIvOKDK3-auQeQeqYTHC4-');
 
if (isset($_POST['inputUsuario']) && $_POST['inputUsuario']) {
    $usuario = filter_var($_POST['inputUsuario'], FILTER_SANITIZE_STRING);
} else {
    // set error message and redirect back to form...
    header('location: subscribe_newsletter_form.php');
    exit;
}
 

$token = $_POST['token'];
$action = $_POST['action'];
//die($token);
// call curl to POST request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => RECAPTCHA_V3_SECRET_KEY, 'response' => $token)));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

curl_close($ch);
$arrResponse = json_decode($response, true);
//var_dump($response);die;
 
// verify the response
if($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5) {
    // valid submission
    // go ahead and do necessary stuff
    
    echo "valido hacer algo";
} else {
    // spam submission
    // show error message
    echo "Error";
}

?>