<?php

use App\Exceptions\Handler;
use Illuminate\Support\Facades\Request;
use Illuminate\Testing\TestResponse;

it('converts an exception into a JSON:API spec error response', function () {
    $handler = app(Handler::class);

    $request = Request::create('/test', 'GET');
    $request->headers->set('accept', 'application/vnd.api+json');

    $exception = new \Exception('Test exception');

    $response = $handler->render($request, $exception);

    TestResponse::fromBaseResponse($response)->assertJson([
        'errors' => [
            [
                'title' => 'Exception',
                'details' => 'Test exception'
            ]
        ]
    ]);
});

it('converts an exception into a json api spec error response', function () {
    /** @var Handler $handler */
    $handler = app(Handler::class);
    $request = Request::create('/test', 'GET');
    $request->headers->set('accept', 'application/vnd.api+json');
    $exception = new \Exception('Test exception');
    $response = $handler->render($request, $exception);
    TestResponse::fromBaseResponse($response)->assertJson([
        'errors' => [
            [
                'title' => 'Exception',
                'details' => 'Test exception',
            ]
        ]
    ])->assertStatus(500);
})->only();
