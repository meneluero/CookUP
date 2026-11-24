<!doctype html>
<html lang="en">
<head>
    <?php require __DIR__ . '/partials/head.html.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main>
        <h1><?= $statusCode ?></h1>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="/">Back to the vault</a>
    </main>
</body>
</html>
