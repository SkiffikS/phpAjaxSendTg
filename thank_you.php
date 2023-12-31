<?php
session_start();
if(!isset($_SESSION["name"])) {
  header("Location: index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Thank You</title>
  <link rel="icon" href="logo.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <style>
    body {
      background: url("../background.jpg") 0 0/100% 100% no-repeat;
      color: #fff;
    }
  </style>
</head>

<body>
  <main class="vh-100 d-flex align-items-center justify-content-center">
    <div class="container text-center">
      <h1 class="text-center fs-1 font-weight-bold">Thank you <span class="text-primary"><?php echo($_SESSION["name"]) ?></span> for the registration!</h1>
    </div>
  </main>
</body>
</html>