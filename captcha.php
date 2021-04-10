<?php
header('Content-Type: text/html; charset=utf-8');
session_start();
if(!empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['message']) and !empty($_POST['captcha'])){
  if($_POST['captcha']!=$_SESSION['captcha']){ 
    die('Kod captcha jest nieprawidłowy'); 
  }else{ 
    $email_odbiorcy = 'karmidka@hmail.com'; 
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
    if(mail($email_odbiorcy , $subject, $message, $header)){ 
      die('Wiadomość została wysłana'); 
    }else{ 
      die('Wiadomość nie została wysłana'); 
    } 
  }
}
?>


<?php
 
session_start();
$chars = '0123456789abc';       
$width = 120;         
$height = 30;          
$number_of_characters = 6;       
$str = '';            
 
for ($i = 0; $i < $number_of_characters; $i++)
    $str .= substr($chars, mt_rand(0, strlen($chars) -1), 1);
 
$string = $str;
$_SESSION['captcha'] = $string;

$im = imagecreate($width, $height);
 
$background     = imagecolorallocate($im,0,0,0);
$font   = imagecolorallocate($im,255,255,255);
$net   = imagecolorallocate($im,78,78,78);
$frame = imagecolorallocate ($im, 131, 131, 131);
 
imagefill($im,1,1,$background);

for($i=0; $i<1600; $i++){
    $rand1 = rand(0,$width);
    $rand2 = rand(0,$height);
    imageline($im, $rand1, $rand2, $rand1, $rand2, $net);
}

$x = rand(5, $width/(7/2));

imagerectangle($im, 0, 0, $width-1, $height-1, $frame);

for($a=0; $a < 7; $a++){
    imagestring($im, 6, $x, rand(4 , $height/5), substr($string, $a, 1), $font);
    $x += (5*3);
}

header("Content-type: image/gif");
imagegif($im);
imagedestroy($im);