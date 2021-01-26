<?php
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isFormValid = true;

    if (isset($_POST['title']) && strlen($_POST['title']) === 0) {
        $isFormValid = false;
        $errors['title'] = 'Title field is empty';
    }

    if (!filter_var($_POST['url'], FILTER_VALIDATE_URL)) {
        $isFormValid = false;
        $errors['url'] = 'URL is not valid';
    }

    if (isset($_POST['description']) && strlen($_POST['description']) === 0) {
        $isFormValid = false;
        $errors['description'] = 'Description field is empty';
    }

    if ($isFormValid) {
        try {
            $database = new PDO('mysql:host=127.0.0.1;dbname=tutube;charset=utf8', 'root', '');
        } catch (Exception $e) {
            var_dump('Erreur : '.$e->getMessage());die;
        }

        $query = $database->prepare('INSERT INTO `video`(`title`, `url`, `description`) VALUES (:title,:url,:description)');
        $hasVideoBeenCreated = $query->execute([
            'title' => $_POST['title'],
            'url' => $_POST['url'],
            'description' => $_POST['description']
        ]);

        if ($hasVideoBeenCreated) {
            header('Location:index.php');
        } else {
            die('could not insert video');
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
    <h1>Add a video</h1>

    <form action="" method="POST">
        <label for="title">Title</label>
        <input type="text" name="title" id="title" placeholder="title" required>
        <?php if (isset($errors['title'])): ?>
            <span><?php echo $errors['title']; ?></span>
        <?php endif; ?>
        <label for="url">Url</label>
        <input type="text" name="url" id="url" placeholder="url" required>
        <?php if (isset($errors['url'])): ?>
            <span><?php echo $errors['url']; ?></span>
        <?php endif; ?>
        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>
        <?php if (isset($errors['description'])): ?>
            <span><?php echo $errors['description']; ?></span>
        <?php endif; ?>
        <input type="submit" value="Add a video">
    </form>
</body>
</html>
