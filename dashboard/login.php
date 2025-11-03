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
    // CSRF check
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid CSRF token.";
    } else {
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        if (empty($recaptchaResponse)) {
            $error = "reCAPTCHA verification missing.";
        } else {
            // Verify reCAPTCHA
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
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // LOGIN
                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['username'] = $username;
                    header("Location: ../dashboard");
                    exit;
                } else {
                    $error = "Invalid username or password.";
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
    <title><?php echo $lang['LOGIN_LOGIN_TITLE']; ?></title>
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
                grecaptcha.execute('<?php echo htmlspecialchars($_ENV['RECAPTCHA_SITE_KEY']); ?>', { action: 'login' })
                    .then(function (token) {
                        document.getElementById('g-recaptcha-response').value = token;
                    });
            });
        </script>

        <form action="login" method="post">
            <h1><?php echo $lang['LOGIN_LOGIN_TITLE']; ?></h1>
            <?php if ($error): ?><p class="error"><?php echo $error; ?></p><?php endif; ?>
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
            <input type="text" name="username" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_USERNAME']; ?>" required>
            <input type="password" name="password" placeholder="<?php echo $lang['LOGIN_PLACEHOLDER_PASSWORD']; ?>" required>
            <button type="submit" name="login"><?php echo $lang['LOGIN_LOGIN']; ?></button>
            <p class="switch"><?php echo $lang['NO_ACCOUNT_YET']; ?>
                <a href="register"><?php echo $lang['REGISTER_HERE']; ?></a>
            </p>
        </form>
    </section>
</main>
</body>
</html>
