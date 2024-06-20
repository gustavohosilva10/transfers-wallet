<?php

namespace App\Domain\Transfers\Controllers;

use App\Domain\Transfers\Business\TransferBusiness;
use App\Request\TransferRequest;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

/**
 * @Controller()
 */
class TransferController 
{
    /**
     * @Inject
     * @var TransferBusiness
     */
    protected $transferBusiness;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @OA\Post(
     *     path="/transfer",
     *     operationId="transfer",
     *     tags={"Transferências"},
     *     summary="Realiza uma transferência",
     *     description="Este endpoint realiza uma transferência entre usuários.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TransferRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Transferência realizada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na transferência"
     *     )
     * )
     * @PostMapping(path="/transfer")
     */
    public function executeTransfer(TransferRequest $request)
    {
        try {
            $transfer = $this->transferBusiness->processTransfer($request->all());
            return $this->response->json($transfer, 201);
        } catch (\Exception $e) {
            return $this->response->json(['error' => $e->getMessage()], 400);
        }
    }
}
