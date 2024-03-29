<?php
function checkIfEntityExists(string $entityName): bool
{
    return file_exists(".." . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Entity" . DIRECTORY_SEPARATOR . ucfirst($entityName) . ".php");
}


function createEntityTemplate(string $entityName, array $properties): string
{

    $propertyTemplate = '';
    $getterTemplate = '';
    $setterTemplate = '';
    foreach ($properties as $property) {
        $propertyTemplate .= createPropertyTemplate($property['type'], $property['name']);
        $getterTemplate .= createGetterTemplate($property['type'], $property['name']);
        $setterTemplate .= createSetterTemplate($property['type'], $property['name']);
    }
    return str_replace(
        ['@name', '@table', '@properties', '@getters', '@setters'],
        [ucfirst($entityName), strtolower($entityName), $propertyTemplate, $getterTemplate, $setterTemplate],
        getEntityTemplate()
    );
}
function getEntityTemplate(): string {
    return <<<EOT
    <?php
    
    namespace Api\Framework\App\Entity;
    use Api\Framework\Kernel\Attributes\ApiResource;
    
    #[ApiResource('@tables')]
    class @name
    {
        private ?int \$id = null;
        @properties
        
        public function getId(): ?int
        {
            return \$this->id;
        }
        @getters
        @setters
    }
    EOT;
}

function createPropertyTemplate(string $type, string $name): string
{
    $propertyTemplate = "private ?@type $@name = null;\n";
    $propertyTemplate = str_replace('@type', $type, $propertyTemplate);
    return str_replace('@name', $name, $propertyTemplate);
}

function createGetterTemplate(string $type, string $name): string
{
    $getterTemplate = <<<EOT
        public function get@^name(): ?@type
        {
            return \$this->@name;
        }
    EOT;
    $getterTemplate = str_replace('@type', $type, $getterTemplate);
    $getterTemplate = str_replace('@name', $name, $getterTemplate);
    return str_replace('@^name', ucfirst($name), $getterTemplate);
}

function createSetterTemplate(string $type, string $name): string
{
    $setterTemplate = <<<EOT
        public function set@^name(@type $@name): void
        {
            \$this->@name = $@name;
        }
    EOT;
    $setterTemplate = str_replace('@type', $type, $setterTemplate);
    $setterTemplate = str_replace('@name', $name, $setterTemplate);
    return str_replace('@^name', ucfirst($name), $setterTemplate);
}

function checkEntryType(string $type): bool
{
    if (!in_array($type, ['int', 'string', 'float', 'bool', 'array', 'null'])) {
        echo "Type invalide\n";
        return false;
    }
    return true;
}

function clearScreen(): void
{
    echo "\033[2J\033[;H";
}
function checkInput(string $input): bool
{
    if (empty($input)) {
        echo "Le nom de la propriété ne doit pas être vide\n";
        return false;
    } elseif (strlen($input) < 2) {
        echo "Le nom de la propriété doit contenir au moins 2 caractères\n";
        return false;
    } else if (preg_match('/\s/', $input)) {
        echo "Le nom de la propriété ne doit pas contenir d'espace\n";
        return false;
    } else if (preg_match('/[^a-zA-Z_]/', $input)) {
        echo "Le nom de la propriété ne doit contenir que des lettres ou des underscores\n";
        return false;
    }
    return true;
}


function createRepositoryTemplate(string $entityName): string
{
    return str_replace(
        ['@name', '@entity'],
        [ucfirst($entityName), strtolower($entityName)],
        getRepositoryTemplate()
    );
}

function getRepositoryTemplate(): string {
    return <<<EOT
    <?php
    
    namespace Api\Framework\App\Repository;
    
    use Api\Framework\Kernel\Abstract\AbstractRepository;
    
    class @nameRepository extends AbstractRepository
    {
        // Ici, on peut écrire des méthodes spécifiques à la classe Repository de l'entité @entity
    }
    EOT;
}
