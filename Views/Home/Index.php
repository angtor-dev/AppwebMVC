<?php $title = "Inicio" ?>

<h2>Esta es la vista Home/Index</h2>

<?php if (isset($result)): ?>
<p>resultado desde el controlador <b>/Home/SumarController.php</b> <?=$result?></p>
<?php endif ?>