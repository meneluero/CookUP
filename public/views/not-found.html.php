<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
</head>
<body>
    <?php require __DIR__ . '/partials/header.html.php'; ?>

    <main>
        <h1>404</h1>
        <p>This page does not exist.</p>
        <a href="/">Back to the vault</a>
    </main>
</body>
</html>
