<?php

namespace App\Domain\Users\Business;

use App\Domain\Users\Contracts\UserInterface;
use Exception;

class UserBusiness
{
    protected $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUserWithWallet(array $request): array
    {
        try {
            $user = $this->userRepository->createUserWithWallet($request, $this->typeUser($request['document']));
            return [
                'success' => true,
                'user' => $user
            ];
        } catch (Exception $e) {
            throw new Exception('Falha ao registrar-se: ' . $e->getMessage());
        }
    }

    private function typeUser(string $document): string
    {
        return strlen($document) > 11 ? 'shopkeeper' : 'user';
    }
}
