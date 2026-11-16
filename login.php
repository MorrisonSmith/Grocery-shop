<?php
session_start();
require_once __DIR__ . "/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!isset($conn) || !($conn instanceof mysqli)) {
        $error = "Database connection is unavailable.";
    } else {
    $sql = "SELECT * FROM users WHERE username = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

            if (
            $user &&
            $user['status'] === 'active' &&
            password_verify($password, $user['password'])
        ) {

        if (password_verify($password, $user['password'])) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Incorrect username or password.";
        }

    } else {
        $error = "Incorrect username or password.";
    }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>SMARTBIZ Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<div class="login-box">

    <h1>SMARTBIZ</h1>
    <h2>Grocery Shop Login</h2>

    <form method="POST">

        <input type="text"
               name="username"
               placeholder="Enter Username"
               required>

        <input type="password"
               name="password"
               placeholder="Enter Password"
               required>

        <button type="submit">Login</button>

    </form>

    <p class="error">
        <?php echo htmlspecialchars($error); ?>
    </p>

</div>

</body>
</html>