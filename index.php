<?php
session_start();

try {
    $database = new PDO('mysql:host=127.0.0.1;dbname=tutube;charset=utf8', 'root', '');
} catch (Exception $e) {
    var_dump('Erreur : '.$e->getMessage());die;
}

$query = $database->prepare('SELECT * FROM `video` WHERE 1');
$query->execute();

$videos = $query->fetchAll(PDO::FETCH_ASSOC);

// same as below just with explode function
//function getEmbedURLFromInitialURL($initialURL)
//{
//    $explodedUrl = explode('watch?v=', $initialURL);
//
//    $videoId = $explodedUrl[1];
//    return sprintf('https://www.youtube.com/embed/%s', $videoId);
//}

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
    <h1>Welcome on tutube</h1>

    <?php if (isset($_SESSION['user'])): ?>
        <h2>Welcome back <?php echo $_SESSION['user']['pseudo']?></h2>
    <?php endif; ?>

    <a href="/add-video.php">Add a video</a>

    <ul>
        <?php foreach ($videos as $video): ?>
            <li>
                Title: <?php echo $video['title'] ?><br>
                <iframe width="560" height="315" src="<?php echo getEmbedURLFromInitialURL($video['url'])?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br>
                <a href="/video.php?id=<?php echo $video['id'] ?>">Voir</a>
            </li>
        <?php endforeach; ?>
    </ul>


</body>
</html>
