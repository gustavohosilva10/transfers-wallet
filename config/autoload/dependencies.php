<?php

declare(strict_types=1);

use App\Domain\Users\Contracts\UserInterface;
use App\Infraestructure\Repositories\UserRepository;

use App\Domain\Transfers\Contracts\TransferInterface;
use App\Infraestructure\Repositories\TransferRepository;
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    UserInterface::class => UserRepository::class, 
    TransferInterface::class => TransferRepository::class
];