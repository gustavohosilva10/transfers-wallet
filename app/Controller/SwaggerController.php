<?php

namespace App\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Contract\ResponseInterface as HttpResponse;
use OpenApi\Generator;

/**
 * @Controller(prefix="/swagger")
 */
class SwaggerController
{
    /**
     * @RequestMapping(path="/json", methods="get")
     */
    public function json(HttpResponse $response): ResponseInterface
    {
        $openapi = Generator::scan([__DIR__ . '/../../app']);
        $json = $openapi->toJson();
        
        return $response->json(json_decode($json, true));
    }
}
