<?php
  $bd_name = "mysql:host=localhost;dbname=financeDB";
  $db_user = "root";
  $db_pass = "20030408N95x";

  try {
      $conn = new PDO($bd_name, $db_user, $db_pass);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // echo "Connection established";
  } catch (PDOException $event) {
      echo "Connection failed: " . $event->getMessage();
  }

  function uniqueID() {
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charLength = strlen($chars);
    $randomString = "";

    for ($i = 0; $i < 20; $i++) {
        $randomString .= $chars[mt_rand(0, $charLength - 1)];
    }
    return $randomString;
  }
?>