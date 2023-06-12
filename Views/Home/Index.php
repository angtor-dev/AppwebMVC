<?php
$result = $viewData['result'] ?? null;
?>

<h2>Esta es la vista Home/Index</h2>

<?php if (isset($result)): ?>
<p>resultado desde la acción Sumar(): <?=$result?></p>
<?php endif ?>

<?php $this->renderPartialView("_PartialExample"); ?>