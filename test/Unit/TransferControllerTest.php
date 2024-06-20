<?php

namespace Tests\Unit\Controllers;

use App\Domain\Transfers\Business\TransferBusiness;
use App\Domain\Transfers\Controllers\TransferController;
use App\Request\TransferRequest;
use Hyperf\HttpServer\Contract\ResponseInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class TransferControllerTest extends TestCase
{
    protected $transferBusinessMock;
    protected $responseMock;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->transferBusinessMock = Mockery::mock(TransferBusiness::class);

        $this->responseMock = Mockery::mock(ResponseInterface::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function it_executes_transfer_with_valid_data()
    {
        $validData = [
            'value' => 100.50,
            'payer' => 1,
            'payeer' => 2,
        ];

        $requestMock = Mockery::mock(TransferRequest::class);
        $requestMock->shouldReceive('all')->andReturn($validData);

        $this->transferBusinessMock->shouldReceive('processTransfer')
            ->once()
            ->with($validData)
            ->andReturn(['message' => 'Transferência realizada com sucesso']);

        $this->responseMock->shouldReceive('json')
            ->once()
            ->with(['message' => 'Transferência realizada com sucesso'], 201)
            ->andReturnUsing(function ($data, $status) {
                // Simulando retorno de um objeto de resposta real
                return new \Hyperf\HttpMessage\Server\Response(json_encode($data), $status, ['Content-Type' => 'application/json']);
            });

        $controller = new TransferController($this->transferBusinessMock, $this->responseMock);

        $response = $controller->executeTransfer($requestMock);

        $this->assertInstanceOf(\Hyperf\HttpMessage\Server\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function it_returns_error_response_with_invalid_data()
    {
        $invalidData = [
            'value' => -10,
            'payer' => 999,
            'payeer' => 2, 
        ];

        $requestMock = Mockery::mock(TransferRequest::class);
        $requestMock->shouldReceive('all')->andReturn($invalidData);

        $this->responseMock->shouldReceive('json')
            ->once()
            ->with(Mockery::type('array'), 400)
            ->andReturnUsing(function ($data, $status) {
                return new \Hyperf\HttpMessage\Server\Response(json_encode($data), $status, ['Content-Type' => 'application/json']);
            });

        $controller = new TransferController($this->transferBusinessMock, $this->responseMock);

        $response = $controller->executeTransfer($requestMock);

        $this->assertInstanceOf(\Hyperf\HttpMessage\Server\Response::class, $response);
        $responseData = json_decode($response->getBody()->getContents(), true);
    }
}
