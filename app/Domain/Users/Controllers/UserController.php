<?php

declare(strict_types=1);

namespace App\Domain\Users\Controllers;

use App\Domain\Users\Business\UserBusiness;
use App\Request\StoreUserRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\HttpServer\Annotation\Middleware;
use App\Middleware\SwaggerJsonResponseMiddleware;

class UserController
{
    protected $userBusiness;
    protected $response;

    public function __construct(UserBusiness $userBusiness, ResponseInterface $response)
    {
        $this->userBusiness = $userBusiness;
        $this->response = $response;
    }
    
   /**
     * @OA\Post(
     *     path="/register",
     *     operationId="registerUser",
     *     tags={"Usuários"},
     *     summary="Registra um novo usuário",
     *     description="Este endpoint registra um novo usuário.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro no registro"
     *     )
     * )
     */
    public function registerUser(StoreUserRequest $request)
    {
        try {
            $register = $this->userBusiness->createUserWithWallet($request->all());
            return $this->response->json($register, 200);
        } catch (\Exception $e) {
            return $this->response->json(['error' => $e->getMessage()], 400);
        }
    }
}
