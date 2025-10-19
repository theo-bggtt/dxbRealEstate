<?php

require_once __DIR__ . '/../db.php'; // Path to db.php in the root
$pdo = getPDO(); // Use the centralized getPDO() function

// Generate CSRF token if not set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$action = isset($_GET['action']) ? $_GET['action'] : 'login';
$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        // Verify reCAPTCHA v3
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (empty($recaptchaResponse)) {
            $error = "reCAPTCHA verification missing.";
        } else {
            // Use curl to verify reCAPTCHA
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'secret' => $_ENV['RECAPTCHA_SECRET_KEY'],
                'response' => $recaptchaResponse
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $recaptcha = curl_exec($ch);
            if ($recaptcha === false) {
                $error = "reCAPTCHA verification failed: " . curl_error($ch);
                error_log("reCAPTCHA curl error for IP " . $_SERVER['REMOTE_ADDR'] . ": " . curl_error($ch), 3, __DIR__ . "/../security.log");
                curl_close($ch);
            } else {
                curl_close($ch);
                $recaptcha = json_decode($recaptcha);

                if (!$recaptcha->success || $recaptcha->score < 0.9) { // Adjust threshold as needed
                    $error = "reCAPTCHA verification failed. Are you a bot?";
                    error_log("reCAPTCHA failed for IP " . $_SERVER['REMOTE_ADDR'] . " with score " . ($recaptcha->score ?? 'N/A'), 3, __DIR__ . "/../security.log");
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
                        } elseif (strlen($password) < 12 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
                            $error = "Password must be at least 12 characters with uppercase, number, and special char.";
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
            }
        }
    }
    // Regenerate CSRF token after POST
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<section class="connexion">
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY']); ?>"></script>
    <script>
        // Generate reCAPTCHA token for form submission
        grecaptcha.ready(function() {
            grecaptcha.execute('<?php echo htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY']); ?>', {action: '<?php echo $action; ?>'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
        });
    </script>

    <?php if ($action === 'register'): ?>

        <!-- REGISTER -->
        <form action="dashboard.php?action=register" method="post">
            <h1><?php echo $lang['LOGIN_REGISTER_TITLE'];?></h1>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <input type="text" id="username" name="username" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_USERNAME'];?>" required>
            <input type="email" id="email" name="email" placeholder="Email" required>
            <input type="password" id="password" name="password" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_PASSWORD'];?>" required>
            <input type="password" id="passwordConfirm" name="passwordConfirm" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_PASSWORD_CONFIRM'];?>" required>
            <button type="submit" name="register"><?php echo $lang['LOGIN_CREATE_ACCOUNT_BUTTON'];?></button>
            <p class="switch"><?php echo $lang['LOGIN_ALREADY_HAVE_ACCOUNT'];?><a href="dashboard.php?action=login"><?php echo $lang['LOGIN_LOGIN_HERE'];?></a></p>
        </form>

    <?php else: ?>

        <!-- LOGIN -->
        <form action="dashboard.php?action=login" method="post">
            <h1><?php echo $lang['LOGIN_LOGIN_TITLE'];?></h1>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <input type="text" id="username" name="username" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_USERNAME'];?>" required>
            <input type="password" id="password" name="password" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_PASSWORD'];?>" required>
            <button type="submit" name="login"><?php echo $lang['LOGIN_LOGIN'];?></button>
            <p class="switch"><?php echo $lang['NO_ACCOUNT_YET'];?><a href="dashboard.php?action=register&langue=<?php echo $_SESSION['langue']?>"><?php echo $lang['REGISTER_HERE'];?></a></p>
        </form>

    <?php endif; ?>
</section>