<?php
session_start();

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
</body>
</html>
