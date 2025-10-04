<?php
// Basic security & validation
function clean($v) { return trim(filter_var($v, FILTER_SANITIZE_STRING)); }

$naam      = clean($_POST['naam'] ?? '');
$email     = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$telefoon  = clean($_POST['telefoon'] ?? '');
$bericht   = trim($_POST['bericht'] ?? '');
$hp        = $_POST['_gotcha'] ?? ''; // honeypot
$toest     = isset($_POST['toestemming']);

if ($hp !== '' || !$toest || !$email || $naam === '' || $bericht === '') {
  header('Location: /contact/bedankt.html?status=error');
  exit;
}

// Configure your recipient
$to      = 'mail@betterbi.nl';
$subject = 'Nieuw contactformulier via website';
$body    = "Naam: $naam\nE-mail: $email\nTelefoon: $telefoon\n\nBericht:\n$bericht\n";
$headers = [];
$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/plain; charset=UTF-8';
$headers[] = 'From: Website <no-reply@betterbi.nl>';
$headers[] = 'Reply-To: ' . $email;

$ok = @mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, implode("\r\n", $headers));

if ($ok) {
  header('Location: /contact/bedankt.html?status=ok');
} else {
  header('Location: /contact/bedankt.html?status=error');
}
