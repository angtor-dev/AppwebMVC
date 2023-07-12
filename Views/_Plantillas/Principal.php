<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "" ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/AppwebMVC/public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/AppwebMVC/public/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/AppwebMVC/public/css/site.css">
</head>
<body>
    <h1>Plantilla _Layout</h1>
    <main>
        <?= $GLOBALS['view'] ?>
    </main>

    <script src="/AppwebMVC/public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/AppwebMVC/public/js/site.js"></script>
</body>
</html>