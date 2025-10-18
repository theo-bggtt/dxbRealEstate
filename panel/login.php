<?php
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['login'])) {
        // TODO: Handle login
        $_SESSION['username'] = $username;
    } elseif (isset($_POST['register'])) {
        // TODO: Handle registration
        $_SESSION['username'] = $username;
    }
}
?>

<section class="connexion">
  <?php if ($action === 'register') : ?>

    <!-- REGISTER FORM -->
    <form action="" method="post">
      <h1>Register</h1>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="passwordConfirm" placeholder="Confirm Password" required>
      <button type="submit" name="register">Create Account</button>
      <p class="switch">Already have an account? <a href="dashboard.php?action=login">Login here</a></p>
    </form>

  <?php else : ?>

    <!-- LOGIN FORM -->
    <form action="" method="post">
      <h1>Login</h1>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <p class="switch">No account yet? <a href="dashboard.php?action=register">Register here</a></p>
    </form>

  <?php endif; ?>
</section>
