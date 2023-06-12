<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$viewData['title'] ?? ""?> - <?=APP_NAME?></title>
</head>
<body>
    <h1>Plantilla _Layout</h1>
    <main>
        <?php $this->renderBody(); ?>
    </main>
</body>
</html>