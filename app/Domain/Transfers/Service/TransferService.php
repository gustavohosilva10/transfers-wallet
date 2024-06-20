<?php

namespace App\Domain\Transfers\Service;

use GuzzleHttp\Client;
use Hyperf\Contract\ConfigInterface;

class TransferService
{
    protected $client;
    protected $config;

    public function __construct(Client $client, ConfigInterface $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function isTransactionAuthorized(): bool
    {
        try {
            $authorization = $this->client->get($this->config->get('app_env_authorization'));
            if ($authorization->getStatusCode() !== 200) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
  
}
