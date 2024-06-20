<?php

namespace App\Domain\Users\Contracts;

interface UserInterface
{
    public function createUserWithWallet(array $request, string $type);
    public function getUserWithWallet($idUser);
}
