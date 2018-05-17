<?php

$to      = 'username@cs.odu.edu';
$subject = 'the subject';
$message = 'hello';
$headers = 'From: username@cs.odu.edu' . "\r\n" .
    'Reply-To: reply-to@cs.odu.edu' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);




?>

