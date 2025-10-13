<?php

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$_SESSION['username'] = $username;

function register() {
  echo("register");
}

?>

<section class="connexion">

  <form action="" method="post">
    <h1>Login</h1>
    <input type="text" name="username" id="username" placeholder="Username" required>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <input type="submit" name="submit" id="login">
  </form>

  <form action="" method="post">
    <h1>Register</h1>
    <input type="text" name="username" id="username" placeholder="Username" required>
    <input type="email" name="email" id="email" placeholder="Email" required>
    <input type="password" name="password" id="password" placeholder="Password" required>
    <input type="submit" name="submit" id="register">
  </form>
</section>