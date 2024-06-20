<?php

namespace Tests\Unit\Controllers;

use App\Domain\Users\Business\UserBusiness;
use App\Domain\Users\Controllers\UserController;
use App\Request\StoreUserRequest;
use Hyperf\HttpServer\Contract\ResponseInterface;
use PHPUnit\Framework\TestCase;
use Mockery;

class UserControllerTest extends TestCase
{
    protected $userBusinessMock;
    protected $responseMock;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->userBusinessMock = Mockery::mock(UserBusiness::class);

        $this->responseMock = Mockery::mock(ResponseInterface::class);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }

    /** @test */
    public function it_registers_user_with_valid_data()
    {
        $validData = [
            'name' => 'John Doe',
            'document' => '12345678901', 
            'email' => 'john.doe@example.com',
        ];

        $requestMock = Mockery::mock(StoreUserRequest::class);
        $requestMock->shouldReceive('all')->andReturn($validData);

        $this->userBusinessMock->shouldReceive('createUserWithWallet')
            ->once()
            ->with($validData)
            ->andReturn(['user_id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com']);

        $this->responseMock->shouldReceive('json')
            ->once()
            ->with(['user_id' => 1, 'name' => 'John Doe', 'email' => 'john.doe@example.com'], 201)
            ->andReturnUsing(function ($data, $status) {
                return new \Hyperf\HttpMessage\Server\Response(json_encode($data), $status, ['Content-Type' => 'application/json']);
            });

        $controller = new UserController($this->userBusinessMock, $this->responseMock);

        $response = $controller->registerUser($requestMock);

        $this->assertInstanceOf(\Hyperf\HttpMessage\Server\Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
