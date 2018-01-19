<?php
require '../initialization/dbconnection.php';
require_once 'FB_API/vendor/autoload.php';
require 'fb-config.php';

session_start();


$helper = $fb->getRedirectLoginHelper();

if (isset($_GET['state'])) { 
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}

try {  
  $accessToken = $helper->getAccessToken('http://photolio.com/google-login/fb-callback.php');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit();
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit();
}

if (! isset($accessToken)) {
  if ($helper->getError()) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Error: " . $helper->getError() . "\n";
    echo "Error Code: " . $helper->getErrorCode() . "\n";
    echo "Error Reason: " . $helper->getErrorReason() . "\n";
    echo "Error Description: " . $helper->getErrorDescription() . "\n";
  } else {
    header('HTTP/1.0 400 Bad Request');
    echo 'Bad request';
  }
  exit();
}
 
 // Logged in


$oAuth2Client = $fb->getOAuth2Client();
/*
//The OAuth 2.0 client handler helps us manage access tokens
// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($accessToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('384437665319826'); // Replace {app-id} with your app id
// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');


  
 * 
 */

if (!$accessToken->isLongLived()) {
  // Exchanges a short-lived access token for a long-lived one
  try {
    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
  } catch (Facebook\Exceptions\FacebookSDKException $e) {
    echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit();
  }

  echo '<h3>Long-lived</h3>';
  var_dump($accessToken->getValue());
}

$_SESSION['fb_access_token'] = (string)$accessToken;
$response = $fb->get("/me?fields=id,birthday,first_name,last_name,email",$accessToken);
$userData = $response->getGraphNode()->asArray();

global $mysqli;

$firstname = $userData['first_name'];
$lastname = $userData['last_name'];
$email = $userData['email'];
$email = filter_var($email,FILTER_SANITIZE_STRING);
$fbpass = $userData['id'];
$fbpass = hash('sha256',$fbpass);
echo $email;
$result = $mysqli->query("SELECT id, email FROM login WHERE email = '$email'");
if(!$result->num_rows){
   $query1 = "INSERT INTO login  (firstname, lastname, email, password) VALUES('$firstname', '$lastname', '$email', '$fbpass');";
   if(!$mysqli->query($query1)){
        die($mysqli->error);
        $error = "error in mysql!";
    } else{
        $result2 = $mysqli->query($query);
    }
}
$obj=$result->fetch_object();
$_SESSION['current_user'] = $obj->id;

$mysqli->close();
session_write_close();

header('Location: index.php?user='. $_SESSION['current_user']);
exit();
//header('Location: https://photolio.com/google-login/index.php');
?>
