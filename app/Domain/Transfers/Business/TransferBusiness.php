<?php

namespace App\Domain\Transfers\Business;

use App\Domain\Transfers\Contracts\TransferInterface;
use App\Domain\Transfers\Service\TransferService;
use App\Domain\Transfers\Service\SendNotifications;
use App\Domain\Users\Contracts\UserInterface;
use Exception;

class TransferBusiness
{
    protected $transferRepository;
    protected $userRepository;
    protected $transferService;
    protected $notificationService;

    public function __construct(
        TransferInterface $transferRepository,
        UserInterface $userRepository,
        TransferService $transferService,
        SendNotifications $notificationService
    ) {
        $this->transferRepository = $transferRepository;
        $this->userRepository = $userRepository;
        $this->transferService = $transferService;
        $this->notificationService = $notificationService;
    }

    public function processTransfer(array $request): array
    {
        try {
            if (!$this->isPayerValid($request['payer'])) {
                throw new Exception('Lojista não pode realizar transferências.');
            }

            if (!$this->hasSufficientBalance($request['payer'], $request['value'])) {
                throw new Exception('Saldo insuficiente.');
            }

            if (!$this->isTransactionAuthorized()) {
                throw new Exception('Transação não autorizada tente novamente.');
            }

            $transfer = $this->executeTransfer($request);
            $this->notifyTransfer($transfer, $request['value']);

            return [
                'success' => true,
                'message' => 'Transferência realizada com sucesso.',
                'transfer' => $transfer,
            ];
        } catch (Exception $e) {
            throw new Exception('Falha na transferência: ' . $e->getMessage());
        }
    }

    private function executeTransfer(array $request)
    {
        return $this->transferRepository->initiateTransfer(
            $request,
            $this->userRepository->getUserWithWallet($request['payer']),
            $this->userRepository->getUserWithWallet($request['payeer'])
        );
    }

    private function notifyTransfer($transfer, $value)
    {
        $this->notificationService->sendNotification(
            $this->userRepository->getUserWithWallet($transfer->payer_id),
            $this->userRepository->getUserWithWallet($transfer->payee_id),
            $transfer->id,
            $value
        );
    }

    private function isPayerValid(string $payer): bool
    {
        $payer = $this->userRepository->getUserWithWallet($payer);

        return $payer->type !== 'shopkeeper';
    }

    private function hasSufficientBalance(string $payer, float $value): bool
    {
        $payer = $this->userRepository->getUserWithWallet($payer);

        if (!$payer->wallet) {
            throw new Exception('Carteira não encontrada para o usuário.');
        }

        return $payer->wallet->balance >= $value;
    }

    private function isTransactionAuthorized(): bool
    {
        return $this->transferService->isTransactionAuthorized();
    }
}
