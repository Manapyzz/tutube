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

    if ($video) {
        $query = $database->prepare('DELETE FROM `video` WHERE id = :id');
        $query->execute([
            'id' => $video['id']
        ]);
    }
}

header('Location:index.php');