<?php
include 'func.php';
clearScreen();
$entityName = readline("Veuillez entrer le nom de l'entité à créer: ");
$properties = [];
while (true) {
    clearScreen();
    if (!empty($properties)) {
        echo "Propriétés ajoutées:\n";
        foreach ($properties as $property) {
            echo $property['name'] . " : " . $property['type'] . "\n";
        }
    }
    $property = readline("Veuillez entrer une propriété de votre entité " . $entityName . " (exit pour terminer): ");
    if ($property === 'exit') {
        break;
    }
    while (!checkInput($property)) {
        $property = readline("Veuillez entrer une propriété (exit pour terminer): ");
        if ($property === 'exit') {
            break 2;
        }
    }
    $type = readline("Veuillez entrer le type de la propriété: ");
    while (!checkEntryType($type)) {
        $type = readline("Veuillez entrer le type de la propriété: ");
    }
    $properties[] = [
        'name' => $property,
        'type' => $type
    ];

}
clearScreen();
$entityTemplate = createEntityTemplate($entityName, $properties);
$repositoryTemplate = createRepositoryTemplate($entityName);
fwrite(fopen("..". DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Repository" . DIRECTORY_SEPARATOR . ucfirst($entityName) . "Repository.php", "w"), $repositoryTemplate);
fwrite(fopen("..". DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Entity" . DIRECTORY_SEPARATOR . ucfirst($entityName) . ".php", "w"), $entityTemplate);
echo ucfirst($entityName) . " créé avec succès\n";



