<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use Hyperf\HttpServer\Router\Router;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Contract\ResponseInterface;

Router::addRoute(['GET', 'POST', 'HEAD'], '/', 'App\Controller\IndexController@index');

Router::get('/swagger/json', [\App\Controller\SwaggerController::class, 'json']);

Router::get('/swagger', function (ResponseInterface $response) {
    $filePath = BASE_PATH . '/public/swagger-ui.html'; // Caminho para o seu arquivo HTML na pasta public
    
    if (file_exists($filePath)) {
        $htmlContent = file_get_contents($filePath);
        return $response->withHeader('Content-Type', 'text/html')->withBody(new SwooleStream($htmlContent));
    } else {
        return 'Arquivo HTML não encontrado';
    }
});

Router::addGroup('/api', function () {
    Router::post('/register', [\App\Domain\Users\Controllers\UserController::class, 'registerUser']);
    Router::post('/transfer', [\App\Domain\Transfers\Controllers\TransferController::class, 'executeTransfer']);
});