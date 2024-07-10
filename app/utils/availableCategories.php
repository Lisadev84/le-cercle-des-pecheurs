<?php

$availableCategories = ['Histoire','Technique', 'Respect de la pratique', 'Matériel', 'Astuces'];
$coll = collator_create('fr_FR');
uasort($availableCategories, fn ($a, $b) => collator_compare($coll, $a, $b));

return $availableCategories;
?>