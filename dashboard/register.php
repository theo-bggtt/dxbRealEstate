<?php
require_once '../start.php';
require_once '../include/config/config.php';
require_once '../include/config/db.php';
require_once '../include/locale/' . $_SESSION['langue'] . '.php';

$pdo = getPDO();
$error = '';

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (empty($recaptchaResponse)) {
            $error = "reCAPTCHA verification missing.";
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'secret' => $_ENV['RECAPTCHA_SECRET_KEY'],
                'response' => $recaptchaResponse
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $recaptcha = curl_exec($ch);
            curl_close($ch);

            $recaptcha = json_decode($recaptcha);
            if (!$recaptcha->success || $recaptcha->score < 0.2) {
                $error = "reCAPTCHA verification failed. Are you a bot?";
            } else {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                $passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                if (empty($username) || empty($email) || empty($password) || empty($passwordConfirm)) {
                    $error = "All fields are required.";
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = "Invalid email format.";
                } elseif ($password !== $passwordConfirm) {
                    $error = "Passwords do not match.";
                } elseif (strlen($password) < 12 || !preg_match('/[A-Z]/', $password) ||
                          !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
                    $error = "Password must be at least 12 characters with uppercase, number, and special char.";
                } else {
                    // Check username/email uniqueness
                    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();
                    if ($stmt->rowCount() > 0) {
                        $error = "Username or email already exists.";
                    } else {
                        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_DEFAULT;
                        $options = ($algo === PASSWORD_ARGON2ID) ? [
                            'memory_cost' => 65536,
                            'time_cost' => 4,
                            'threads' => 1
                        ] : ['cost' => 12];

                        $hashedPassword = password_hash($password, $algo, $options);
                        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':password', $hashedPassword);
                        $stmt->execute();

                        session_regenerate_id(true);
                        $_SESSION['username'] = $username;
                        header("Location: ../dashboard");
                        exit;
                    }
                }
            }
        }
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title><?php echo $lang['LOGIN_REGISTER_TITLE']; ?></title>
</head>
<body>
<header>
    <?php require('../include/header.php'); ?>
</header>
<main>
    <section class="connexion">
        <script src="https://www.google.com/recaptcha/api.js?render=<?php echo htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY']); ?>"></script>
        <script>
            grecaptcha.ready(function () {
                grecaptcha.execute('<?php echo htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY']); ?>', { action: 'register' })
                    .then(function (token) {
                        document.getElementById('g-recaptcha-response').value = token;
                    });
            });
        </script>

        <form action="register" method="post">
            <h1><?php echo $lang['LOGIN_REGISTER_TITLE']; ?></h1>
            <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <input type="text" name="username" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_USERNAME']; ?>" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_PASSWORD']; ?>" required>
            <input type="password" name="passwordConfirm" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_PASSWORD_CONFIRM']; ?>" required>
            <button type="submit" name="register"><?php echo $lang['LOGIN_CREATE_ACCOUNT_BUTTON']; ?></button>
            <p class="switch"><?php echo $lang['LOGIN_ALREADY_HAVE_ACCOUNT']; ?>
                <a href="login"><?php echo $lang['LOGIN_LOGIN_HERE']; ?></a>
            </p>
        </form>
    </section>
</main>
</body>
</html>
