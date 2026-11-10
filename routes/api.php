<?php
declare(strict_types=1);

use App\Controllers\EventController;
use App\Controllers\TournamentController;
use App\Controllers\SportController;
use App\Requests\PaginationRequest;
use Bramus\Router\Router;
use Psr\Container\ContainerInterface;

/**
 * @var Router $router
 * @var ContainerInterface $container
 */

$router->mount('/api', function () use ($router, $container) {
    $router->mount('/tournaments', function () use ($router, $container) {
        $router->get('/event-rich',   fn () => $container->get(TournamentController::class)->getEventRich());
    });

    $router->mount('/sports', function () use ($router, $container) {
        $router->get('/event-count',   fn () => $container->get(SportController::class)->getEventCount());
    }); //getBySport

    $router->mount('/events', function () use ($router, $container) {
        $router->get('/',   fn () => $container->get(EventController::class)->getAll($container->get(PaginationRequest::class)));
        $router->get(
            '/(\d+)',
            fn (string $id) => $container->get(EventController::class)->getById((int) $id, $container->get(PaginationRequest::class))
        );
        $router->get(
            '/sport/(\d+)',
            fn (string $id) => $container->get(EventController::class)->getBySport((int) $id, $container->get(PaginationRequest::class))
        );
        $router->get(
            '/tournament/(\d+)',
            fn (string $id) => $container->get(EventController::class)->getByTournament((int) $id, $container->get(PaginationRequest::class))
        );
    });
});
