<?php

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
    <a href="/">Back to videos</a>
</body>
</html>
