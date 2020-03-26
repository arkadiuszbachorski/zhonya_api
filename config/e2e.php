<?php

return [
    'allow' => env('E2E_TEST_ALLOW', false),
    'token' => env('E2E_TEST_TOKEN'),
    'email' => env('E2E_TEST_EMAIL', 'e2e@test.com'),
    'password' => env('E2E_TEST_PASSWORD', 'test1234'),
];
