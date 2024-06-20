<?php

namespace App\Domain\Transfers\Contracts;

interface TransferInterface
{
    public function initiateTransfer(array $request, $payer, $payeer);
}
