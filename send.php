<?php

$request = $_REQUEST;

$firstName = trim($request["firstName"]);
$lastName = trim($request["lastName"]);
$email = trim($request["email"]);
$phoneNumber = trim($request["phoneNumber"]);
$time = date('H:i d.m.Y');
$content = "";

define("API_TOKEN", "6098860072:AAFp9MoK5m_nA1nj8yIsWUO2O5U84--Q3Uc");
define("CHAT_ID", "@SkiffikS_testWebMessages");

if (strlen($firstName) < 5) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 11,
      "message" => "<span class='text-danger'>*</span>Too shorted first name (less than <span class='text-danger'>5</span> characters)"
    ]
  ]);
  exit;
} elseif (strlen($firstName) > 30) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 12,
      "message" => "<span class='text-danger'>*</span>Too long first name (more than <span class='text-danger'>30</span> characters)"
    ]
  ]);
  exit;
} elseif (!preg_match("/^[a-zA-Z]*$/", $firstName)) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 13,
      "message" => "<span class='text-danger'>*</span>First name contains extra characters"
    ]
  ]);
  exit;
}

if (strlen($lastName) < 5) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 21,
      "message" => "<span class='text-danger'>*</span>Too shorted last name (less than <span class='text-danger'>5</span> characters)"
    ]
  ]);
  exit;
} elseif (strlen($lastName) > 50) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 22,
      "message" => "<span class='text-danger'>*</span>Too long last name (more than <span class='text-danger'>50</span> characters)"
    ]
  ]);
  exit;
} elseif (!preg_match("/^[a-zA-Z]*$/", $lastName)) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 23,
      "message" => "<span class='text-danger'>*</span>Last name contains extra characters"
    ]
  ]);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 31,
      "message" => "<span class='text-danger'>*</span>Incorrect email adress"
    ]
  ]);
  exit;
}

$phoneNumber = str_replace(' ', '', $phoneNumber);
if (substr($phoneNumber, 0, 1) !== '+') {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 41,
      "message" => "<span class='text-danger'>*</span>Incorrect phone number (t must start with '<span class='text-danger'>+</span>')"
    ]
  ]);
  exit;
} elseif (strlen($phoneNumber) <= 8) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 42,
      "message" => "<span class='text-danger'>*</span>Phone number must be more than <span class='text-danger'>8</span> digits')"
    ]
  ]);
  exit;
} elseif (strlen($phoneNumber) >= 15) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 42,
      "message" => "<span class='text-danger'>*</span>Phone numbermust be less <span class='text-danger'>15</span> digits')"
    ]
  ]);
  exit;
}


// send functions
$fields = [
  "Name" => htmlspecialchars($firstName),
  "LastName" => htmlspecialchars($lastName),
  "Email" => htmlspecialchars($email),
  "Phone" => htmlspecialchars($phoneNumber),
  "Time" => htmlspecialchars($time)
];

foreach ($fields as $key => $value) {
  $content .= "<b>$key:</b> $value\n";
}

$data = [
  "chat_id" => CHAT_ID,
  "text" => $content,
  "parse_mode" => "HTML"
];

$response = file_get_contents("https://api.telegram.org/bot" . API_TOKEN . "/sendMessage?" . http_build_query($data));

if (!$response) {
  echo json_encode([
    "status" => false,
    "error" => [
      "code" => 51,
      "message" => "<span class='text-danger'>Server error</span>: incorrect telegram data)"
    ]
  ]);
  exit;
}

session_start();
$_SESSION["name"] = $firstName;
echo json_encode([
  "status" => true,
  "error" => null
]);
exit;
