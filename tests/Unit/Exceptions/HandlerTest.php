<?php

use App\Exceptions\Handler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Request;
use Illuminate\Testing\TestResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('converts an exception into a JSON:API spec error response', function () {
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
});

it('converts an http exception into a json api spec error response', function () {
    /** @var Handler $handler */
    $handler = app(Handler::class);
    $request = Request::create('/test', 'GET');
    $request->headers->set('accept', 'application/vnd.api+json');

    $exception = new HttpException(404, 'Not found');

    $response = $handler->render($request, $exception);
    TestResponse::fromBaseResponse($response)->assertJson([
        'errors' => [
            [
                'title' => 'Http Exception',
                'details' => 'Not found',
            ]
        ]
    ])->assertStatus(404);
});

it('converts an http exception into a json api spec error', function () {
    /** @var Handler $handler */
    $handler = app(Handler::class);
    $request = Request::create('/test', 'GET');

    $request->headers->set('accept', 'application/vnd.api+json');

    $exception = new HttpException(404, 'Not found');
    $response = $handler->render($request, $exception);
    TestResponse::fromBaseResponse($response)->assertJson([
        'errors' => [
            [
                'title' => 'Http Exception',
                'details' => 'Not found',
            ]
        ]
    ])->assertStatus(404);
});

it('converts an unauthenticated exception into a json api spec error response', function () {
    /** @var Handler $handler */
    $handler = app(Handler::class);
    $request = Request::create('/test', 'GET');

    $request->headers->set('accept', 'application/vnd.api+json');

    $exception = new AuthenticationException();
    $response = $handler->render($request, $exception);
    TestResponse::fromBaseResponse($response)->assertJson([
        'errors' => [
            [
                'title' => 'Unauthenticated',
                'details' => 'You are not authenticated.',
            ]
        ]
    ]);
});
