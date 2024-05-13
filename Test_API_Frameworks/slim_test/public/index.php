<?php
/**
 * @OA\Info(title="My API", version="1.0")
 */

require __DIR__ . '/../vendor/autoload.php';
include ("website.php");
use OpenApi\Annotations as OA;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = AppFactory::create();
$app->add(function ($request, $handler) {
    $route = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
    if ($route !== 'http://localhost:8000') {
        echo $route;
        return new \Slim\Exception\HttpNotFoundException($request);
    }
    return $handler->handle($request);
});

/**
 * @OA\Get(
 *     path="/hello-world",
 *     summary="Get Hello World",
 *     description="Returns a greeting message for the world",
 *     @OA\Response(response="200", description="Successful operation"),
 * )
 */
$app->get('/get_token/{payload}', function (Request $request, Response $response, array $args) {
    $jwtToken = (new jwt_token())->createToken($args['payload']);
    $response->getBody()->write($jwtToken);
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
})->setName('create_token');

/**
 * @OA\Get(
 *     path="/hello/{name}",
 *     @OA\Response(response="200", description="Hello {name}"),
 *     @OA\Parameter(
 *         name="name",
 *         in="path",
 *         description="The name of the person to greet",
 *         required=true,
 *         @OA\Schema(type="string")
 *     )
 * )
 */
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

$app->get('/api/protected', function ($request, $response, $args) {
    $class_jwt = new jwt_token();
    $token = $request->getHeader('Authorization')[0];
    if (!$class_jwt->validateToken($token)) {
        return $response->withStatus(401);
    }
    $response->getBody()->write("HOPPAAAA");
    return $response;
});

$app->run();
