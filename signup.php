<?php

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isFormValid = true;

    if (isset($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $isFormValid = false;
        $errors['email'] = 'Email is not valid';
    }

    if (isset($_POST['pseudo']) && strlen($_POST['pseudo']) === 0) {
        $isFormValid = false;
        $errors['pseudo'] = 'Pseudo field is empty';
    }

    if (isset($_POST['password']) && strlen($_POST['password']) < 6) {
        $isFormValid = false;
        $errors['password'] = 'Password should have at least 6 characters';
    }

    if ($isFormValid) {
        try {
            $database = new PDO('mysql:host=127.0.0.1;dbname=tutube;charset=utf8', 'root', '');
        } catch (Exception $e) {
            var_dump('Erreur : '.$e->getMessage());die;
        }

        $query = $database->prepare('INSERT INTO `user`(`email`, `pseudo`, `password`) VALUES (:email,:pseudo,:password)');
        $hasUserBeenCreated = $query->execute([
            'email' => $_POST['email'],
            'pseudo' => $_POST['pseudo'],
            'password' => $_POST['password']
        ]);

        if ($hasUserBeenCreated) {
            header('Location:signin.php');
        } else {
            die('could not insert user');
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
    <h1>Sign Up on Tutube</h1>
    <form action="" method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="email" required>
        <?php if (isset($errors['email'])): ?>
        <span><?php echo $errors['email']; ?></span>
        <?php endif; ?>
        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" placeholder="pseudo" required>
        <?php if (isset($errors['pseudo'])): ?>
            <span><?php echo $errors['pseudo']; ?></span>
        <?php endif; ?>
        <label for="password">Password (min 6 characters)</label>
        <input type="text" name="password" id="password" placeholder="password" required>
        <?php if (isset($errors['password'])): ?>
            <span><?php echo $errors['password']; ?></span>
        <?php endif; ?>
        <input type="submit" value="Create your account">
    </form>
</body>
</html>
