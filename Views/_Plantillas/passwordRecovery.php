<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/site.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/choicesjs/choices.css">
</head>

<body>
    <?= $GLOBALS['view'] ?>
 
    <script src="<?= LOCAL_DIR ?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/sweetalert2.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/jQuery/jquery-3.7.0.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/choicesjs/choices.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/jsencrypt/bin/jsencrypt.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/recoveryToken.js"></script>
   
</body>

</html>