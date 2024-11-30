<?php
  include("components/db.php");

  if (isset($_SESSION["userID"])) {
    $userID = $_SESSION["userID"];
  } else {
    $userID = "";
  }

  //register
  if (isset($_POST["submit"])) {
    $name = filter_var(trim($_POST["name"]), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $pw = trim($_POST["password"]);
    $cpw = trim($_POST["cpassword"]);
    $hashedPassword = password_hash($pw, PASSWORD_BCRYPT);

    if (strlen($pw) < 8) {
      echo "Password must be at least 8 characters long";
      return;
    }

    if ($pw !== $cpw) {
      echo "Passwords do not match";
      return;
    }

    try {
      $checkUser = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $checkUser->execute([$email]);

      if ($checkUser->rowCount() > 0) {
        echo "Email already exists.";
        return;
      }

      $id = uniqueID();
      $insertUser = $conn->prepare("INSERT INTO `users` (id, name, email, password) VALUES (?, ?, ?, ?)");
      $insertUser->execute([$id, $name, $email, $hashedPassword]);

      echo "User registered successfully.";

      $fetchUser = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $fetchUser->execute([$email]);
      $row = $fetchUser->fetch(PDO::FETCH_ASSOC);

      if ($fetchUser->rowCount() > 0) {
        session_start();
        $_SESSION["userID"] = $row["id"];
        $_SESSION["userName"] = $row["name"];
        $_SESSION["userEmail"] = $row["email"];
      }

      header("location: login.php");
    } catch (PDOException $event) {
      echo "An error occurred: " . $event->getMessage();
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Register page</title>
</head>
<body>
  <section class="form-container">
    <h1 class="title">register now</h1>

    <form method="post">
      <div class="input-field">
        <label>your name</label>
        <input type="text" name="name" required placeholder="enter your name" maxlength="50">
      </div>

      <div class="input-field">
        <label>your email</label>
        <input type="email" name="email" required placeholder="enter your email" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>  

      <div class="input-field">
        <label>your password</label>
        <input type="password" name="password" required placeholder="enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <div class="input-field">
        <label>confirm password</label>
        <input type="password" name="cpassword" required placeholder="re-enter your password" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <input type="submit" name="submit" value="register now" class="btn reg-btn">
      <p class="acc">already have an account?
        <a href="login.php">login now</a>
      </p>
    </form>
  </section>
</body>
</html>