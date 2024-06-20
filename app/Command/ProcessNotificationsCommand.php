<?php

declare(strict_types=1);

namespace App\Command;

use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Psr\Container\ContainerInterface;
use App\Domain\Transfers\Service\SendNotifications;
use Hyperf\Contract\ConfigInterface;

#[Command]
class ProcessNotificationsCommand extends HyperfCommand
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct('notifications:process');
        $this->container = $container;
    }

    public function configure()
    {
        $this->setDescription('Processo de envio de notificações.');
    }

    public function handle()
    {
        $transferNotification = $this->container->get(SendNotifications::class);
        $transferNotification->processNotifications();
    }
}
