<?php
session_start();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $isFormValid = true;

    if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $isFormValid = false;
        $errors['email'] = 'Email is not valid';
    }

    if (isset($_POST['password']) && strlen($_POST['password']) < 6) {
        $isFormValid = false;
        $errors['password'] = 'Password is incorrect';
    }

    if ($isFormValid) {
        try {
            $database = new PDO('mysql:host=127.0.0.1;dbname=tutube;charset=utf8', 'root', '');
        } catch (Exception $e) {
            var_dump('Erreur : '.$e->getMessage());die;
        }

        $query = $database->prepare('SELECT * FROM `user` WHERE email = :email AND password = :password');
        $query->execute([
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ]);

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user'] = [
                'email' => $user['email'],
                'pseudo' => $user['pseudo'],
            ];

            header('Location:index.php');
        } else {
            $errors['incorrectCredentials'] = 'Credentials are incorrect';
        }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tutube</title>
</head>
<body>
    <h1>Sign in on Tutube</h1>

    <form action="" method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="email" required>
        <?php if (isset($errors['email'])): ?>
            <span><?php echo $errors['email']; ?></span>
        <?php endif; ?>
        <label for="password">Password</label>
        <input type="text" name="password" id="password" placeholder="password" required>
        <?php if (isset($errors['password'])): ?>
            <span><?php echo $errors['password']; ?></span>
        <?php endif; ?>
        <input type="submit" value="Sign in">
        <?php if (isset($errors['incorrectCredentials'])): ?>
            <span><?php echo $errors['incorrectCredentials']; ?></span>
        <?php endif; ?>
    </form>
</body>
</html>
