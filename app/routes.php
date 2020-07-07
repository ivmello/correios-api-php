<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use FlyingLuscas\Correios\Client;
use FlyingLuscas\Correios\Service;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode(array(
            "data" => "Bem-vindo a API Correios",
        )));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/cep/{cep}', function (Request $request, Response $response, $args) {
        $cep = $args['cep'];
        $correios = new Client;
        $result = $correios->zipcode()->find($cep);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/frete', function (Request $request, Response $response, $args) {
        $params = $request->getQueryParams();
        $correios = new Client;
        $result = $correios->freight()
            ->origin($params['cep_origem'])
            ->destination($params['cep_destino'])
            ->services(Service::SEDEX, Service::PAC)
            ->item(16, 16, 16, $params['peso'], 1)
            ->calculate();
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
