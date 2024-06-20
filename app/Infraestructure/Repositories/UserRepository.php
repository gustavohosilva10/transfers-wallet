<?php

namespace App\Infraestructure\Repositories;

use App\Model\User;
use App\Model\Wallet;
use App\Domain\Users\Contracts\UserInterface;

class UserRepository implements UserInterface
{
    public function createUserWithWallet(array $data, string $type)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'document' => $data['document'],
            'type' => $type
        ]);

        if (!$user) {
            throw new Exception('Falha ao registrar usuário.');
        }

        $this->initializeWallet($user->id);

        return $user;
    }

    private function initializeWallet($idUser):void
    {
        Wallet::create([
            'user_id' => $idUser,
            'balance' => 0,
        ]);
    }

    public function getUserWithWallet($idUser)
    {
        return User::with('wallet')->findOrFail($idUser);
    }
}
