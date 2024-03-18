<?php

$choice = readline("Voulez-vous créer un controller ou une entité ? (controller/entity) : ");
if ($choice === 'controller') {
    include 'build-controller.php';
} elseif ($choice === 'entity') {
    include 'build-entity.php';
} else {
    echo "Choix invalide\n";
}