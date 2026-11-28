<!doctype html>
<html lang="en">
<head>
    <?php require __DIR__ . '/partials/head.html.php'; ?>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main class="page">
        <div class="page-intro">
            <h1><?= $statusCode ?></h1>
            <p><?= htmlspecialchars($message) ?></p>
        </div>
        <p><a href="/" class="button button--primary">Back to the vault</a></p>
    </main>
</body>
</html>
