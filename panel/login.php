<?php

require_once __DIR__ . '/../db.php'; // Adjusted path to correctly point to db.php in the root

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pdo = getPDO(); // Use the centralized getPDO() function

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (isset($_POST['login'])) {
            // Handle login
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['username'] = $username;
                // Redirect to dashboard or home page after successful login
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } elseif (isset($_POST['register'])) {
            // Handle registration
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
                $error = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "Invalid email format.";
            } elseif ($password !== $passwordConfirm) {
                $error = "Passwords do not match.";
            } else {
                // Check if username exists
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $error = "Username already exists.";
                } else {
                    // Check if email exists
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        $error = "Email already exists.";
                    } else {
                        // Use Argon2id if available, otherwise fall back to bcrypt with higher cost
                        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
                        $options = [];

                        if ($algo === PASSWORD_ARGON2ID) {
                            $options = [
                                'memory_cost' => 65536,  // 64MB
                                'time_cost' => 4,
                                'threads' => 1
                            ];
                        } elseif ($algo === PASSWORD_DEFAULT) {
                            $options = ['cost' => 12];  // Higher cost for bcrypt
                        }

                        $hashedPassword = password_hash($password, $algo, $options);
                        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':password', $hashedPassword);
                        $stmt->execute();

                        session_regenerate_id(true); // Prevent session fixation
                        $_SESSION['username'] = $username;
                        header("Location: dashboard.php");
                        exit;
                    }
                }
            }
        }
    }
    // Regenerate CSRF token after POST
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<section class="connexion">
  <?php if ($action === 'register') : ?>

    <!-- REGISTER -->
    <form action="dashboard.php?action=register" method="post">
      <h1>Register</h1>
      <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="passwordConfirm" placeholder="Confirm Password" required>
      <button type="submit" name="register">Create Account</button>
      <p class="switch">Already have an account? <a href="dashboard.php?action=login">Login here</a></p>
    </form>

  <?php else : ?>

    <!-- LOGIN -->
    <form action="dashboard.php?action=login" method="post">
      <h1>Login</h1>
      <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
      <?php endif; ?>
      <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" name="login">Login</button>
      <p class="switch">No account yet? <a href="dashboard.php?action=register">Register here</a></p>
    </form>

  <?php endif; ?>
</section>