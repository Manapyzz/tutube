<?php

session_start();

if (isset($_GET['id'])) {
    try {
        $database = new PDO('mysql:host=127.0.0.1;dbname=tutube;charset=utf8', 'root', '');
    } catch (Exception $e) {
        var_dump('Erreur : '.$e->getMessage());die;
    }

    $query = $database->prepare('SELECT * FROM `video` WHERE id = :id');
    $query->execute([
        'id' => $_GET['id']
    ]);

    $video = $query->fetch(PDO::FETCH_ASSOC);

    if (!$video) {
        header('Location:index.php');
    }
} else {
    header('Location:index.php');
}


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $isFormValid = true;

    if (!isset($_POST['id'])) {
        header('Location:index.php');
    }

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
            var_dump('Erreur : '.$e->getMessage());
            die;
        }

        $query = $database->prepare(
            'UPDATE `video` SET `title`=:title,`url`=:url,`description`=:description WHERE id = :id'
        );
        $hasVideoBeenUpdated = $query->execute(
            [
                'id' => $_POST['id'],
                'title' => $_POST['title'],
                'url' => $_POST['url'],
                'description' => $_POST['description']
            ]
        );

        if (!$hasVideoBeenUpdated) {
            die('could not insert video');
        }
    }
}

function getEmbedURLFromInitialURL($initialURL)
{
    $videoId = substr($initialURL, 32);
    return sprintf('https://www.youtube.com/embed/%s', $videoId);
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
    <h1><?php echo $video['title']; ?></h1>
    <iframe width="560" height="315" src="<?php echo getEmbedURLFromInitialURL($video['url'])?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    <h4>Description:</h4>
    <div>
        <?php echo $video['description']; ?>
    </div>
    <?php if (isset($_SESSION['user'])): ?>
        <a href="/delete.php?id=<?php echo $video['id'] ?>">Delete video</a>
        <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $video['id']; ?>">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" placeholder="title" value="<?php echo $video['title']; ?>" required>
            <?php if (isset($errors['title'])): ?>
                <span><?php echo $errors['title']; ?></span>
            <?php endif; ?>
            <label for="url">Url</label>
            <input type="text" name="url" id="url" placeholder="url" value="<?php echo $video['url']; ?>" required>
            <?php if (isset($errors['url'])): ?>
                <span><?php echo $errors['url']; ?></span>
            <?php endif; ?>
            <label for="description">Description</label>
            <textarea id="description" name="description" required>
            <?php echo $video['description']; ?>"
        </textarea>
            <?php if (isset($errors['description'])): ?>
                <span><?php echo $errors['description']; ?></span>
            <?php endif; ?>
            <input type="submit" value="Edit video">
        </form>
    <?php endif; ?>
    <a href="/">Back to videos</a>
</body>
</html>
