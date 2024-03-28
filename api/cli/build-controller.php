<?php

include 'func.php';
$controllerTemplate = <<<PHP
<?php

namespace Api\Framework\App\Controller;

use Api\Framework\Kernel\Http\JsonResponse;
use Api\Framework\Kernel\Attributes\Endpoint;
use Api\Framework\Kernel\Abstract\AbstractController;

class @^nameController extends AbstractController
{
    #[Endpoint(path: '/@name', requestMethod: 'GET')]
    public function index(): JsonResponse
    {
        \$message = "Hello World";
        return \$this->send([
            'message' => \$message
        ]);
    }
}
PHP;
clearScreen();
$input = readline("Veuillez entrer le nom du controller à créer : ");
$input = ucfirst($input);
$controller = fopen(DIRECTORY_SEPARATOR. "..". DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Controller" . DIRECTORY_SEPARATOR . $input . "Controller.php", "w");
$controllerTemplate = str_replace('@^name', $input, $controllerTemplate);
$controllerTemplate = str_replace('@name', strtolower($input), $controllerTemplate);
fwrite($controller, $controllerTemplate);
fclose($controller);
echo $input."Controller créé avec succès\n";

