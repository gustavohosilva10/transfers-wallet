<?php

namespace App\Domain\Transfers\Service;

use GuzzleHttp\Client;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Redis\RedisFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

class SendNotifications
{
    protected $config;
    protected $redis;
    protected $logger;

    public function __construct(ConfigInterface $config, RedisFactory $redisFactory, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->redis = $redisFactory->get('default');
        $this->logger = $logger;
    }

    public function sendNotification($paye, $payee, $transferId, $value): void
    {
        try {
            $this->redis->lpush('notifications', json_encode([
                'transaction_id' => $transferId,
                'amount' => $value,
                'payer' => $paye->name,
                'payee' => $payee->name,
            ]));
        } catch (\Throwable $e) {
            $this->logger->error('Falha ao enfileirar a notificação', ['exception' => $e->getMessage()]);
        }
    }

    public function processNotifications(): void
    {
        while (true) {
            try {
                $notification = $this->redis->rpop('notifications');

                if ($notification !== null) {
                    $this->sendHttpRequest(json_decode($notification, true));
                } else {
                    sleep(10);
                }
            } catch (\Throwable $e) {
                $this->logger->error('Falha ao processar envio de notificação', ['exception' => $e->getMessage()]);
            }
        }
    }

    protected function sendHttpRequest(array $data): void
    {
        try {
            $response = $this->client->post($this->config->get('app_env_notification_url'), [
                'json' => $data,
            ]);

            if ($response->getStatusCode() !== 200) {
                $this->logger->error('Notification failed', ['response' => $response->getBody()->getContents()]);
            }
        } catch (\Throwable $e) {
            $this->logger->error('Notification exception', ['exception' => $e->getMessage()]);
        }
    }
}
