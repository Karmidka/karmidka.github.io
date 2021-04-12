<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

if(!empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['message'])){

  $verify = curl_init(); curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
  curl_setopt($verify, CURLOPT_POST, true); 
  curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query([
    'secret' => '<6Le7mKQaAAAAADL3KpRZ3yQsd114C3h_wRx_zmAh> ',
    'response' => $_POST['recaptcha_response'] 
  ])); 
  curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($verify, CURLOPT_RETURNTRANSFER, true); 
  $response = json_decode(curl_exec($verify),true);

  if($response['success'] == false){
    die('Kod captcha jest nieprawidłowy');
    $input = $_POST;
  }else{
    $email_odbiorcy = 'karmidka@gmail.com';

    $header = 'Reply-To: <'.$_POST['email']."> \r\n";
    $header .= "MIME-Version: 1.0 \r\n";
    $header .= "Content-Type: text/html; charset=UTF-8";

    $wiadomosc = "<p>Dostałeś wiadomość od:</p>";
    $wiadomosc .= "<p>Imie i nazwisko: " . $_POST['name'] . "</p>";
    $wiadomosc .= "<p>Email: " . $_POST['email'] . "</p>";
    $wiadomosc .= "<p>Wiadomość: " . $_POST['message'] . "</p>";

    $message = '<!doctype html><html lang="pl"><head><meta charset="utf-8">'.$wiadomosc.'</head><body>';

    $subject = 'Wiadomość ze strony...';
    $subject = '=?utf-8?B?'.base64_encode($subject).'?=';

    if(mail($email_odbiorcy, $subject, $message, $header)){
      die('Wiadomość została wysłana');
    }else{
      die('Wiadomość nie została wysłana');
    }
  }
}
?>


