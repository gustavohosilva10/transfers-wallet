#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OpenApi\Generator;

$openapi = Generator::scan([__DIR__ . '/../app']);
file_put_contents(__DIR__ . '/../public/swagger.json', $openapi->toJson());
echo "Swagger documentation generated successfully.\n";
