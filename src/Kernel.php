<?php

declare(strict_types=1);

namespace App;

use App\Commands\ListenSocketCommand;
use App\Interfaces\ImportInterface;
use App\Interfaces\ParserInterface;
use App\Services\ImporterService;
use App\Services\ParserService;
use Bramus\Router\Router;
use DI\Container;
use DI\ContainerBuilder;
use Exception;
use PDO;
use Symfony\Component\Console\Application;
use function DI\autowire;

readonly class Kernel
{
    public Container $containerBuilder;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->containerBuilder = new ContainerBuilder()->addDefinitions([
            ParserInterface::class => autowire(ParserService::class),
            ImportInterface::class => autowire(ImporterService::class),
            PDO::class => new PDO(dsn: $_ENV['DB_DSN'], username: $_ENV['DB_USER'], password: $_ENV['BD_PASSWORD']),
        ])->build();
    }

    /**
     * @throws Exception
     */
    public function runApplication(): never
    {
        $app = new Application();
        $app->addCommands([$this->containerBuilder->get(ListenSocketCommand::class)]);
        $app->run();

        exit;
    }

    public function runRouter(): void
    {
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Origin: http://localhost:3000");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit();
        }

        $router = new Router();
        $container = $this->containerBuilder;

        require_once __DIR__ . '/../routes/api.php';

        $router->run();
    }
}
