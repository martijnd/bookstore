<?php

use App\Http\Middleware\EnsureCorrectAPIHeaders;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Response;

it('aborts request if accept headers does not adhere to JSON:API spec', function () {
    $request = Request::create('/test', 'GET');

    $middleware = new EnsureCorrectAPIHeaders;

    /** @var Response $response */
    $response = $middleware->handle($request, function ($request) {
        $this->fail('Did not abort request because of invalid Accept header');
    });

    $this->assertEquals(406, $response->status());
});

it('accepts request if accept header adheres to json api spec', function () {
    $request = Request::create('/test', 'GET');
    $request->headers->set('accept', 'application/vnd.api+json');

    $middleware = new EnsureCorrectAPIHeaders;

    /** @var Response $response */
    $response = $middleware->handle($request, function ($request) {
        return new Response();
    });
    $this->assertEquals(200, $response->status());
});

it('aborts post request if content type header does not adhere to json api spec', function () {
    $request = Request::create('/test', 'POST');
    $request->headers->set('accept', 'application/vnd.api+json');

    $middleware = new EnsureCorrectAPIHeaders;

    /** @var Response $response */
    $response = $middleware->handle($request, function ($request) {
        $this->fail('Did not abort request because of invalid Content-Type header');
    });
    $this->assertEquals(415, $response->status());
});

it('aborts patch request if content type header does not adhere to json api spec', function () {
    $request = Request::create('/test', 'PATCH');
    $request->headers->set('accept', 'application/vnd.api+json');

    $middleware = new EnsureCorrectAPIHeaders;

    /** @var Response $response */
    $response = $middleware->handle($request, function ($request) {
        $this->fail('Did not abort request because of invalid Content-Type header');
    });
    $this->assertEquals(415, $response->status());
});

it('accepts post request if content type header adheres to json api spec', function () {
    $request = Request::create('/test', 'POST');
    $request->headers->set('accept', 'application/vnd.api+json');
    $request->headers->set('content-type', 'application/vnd.api+json');

    $middleware = new EnsureCorrectAPIHeaders;

    /** @var Response $response */
    $response = $middleware->handle($request, function ($request) {
        return new Response();
    });
    $this->assertEquals(200, $response->status());
});

it('accepts patch request if content type header adheres to json api spec', function () {
    $request = Request::create('/test', 'PATCH');
    $request->headers->set('accept', 'application/vnd.api+json');
    $request->headers->set('content-type', 'application/vnd.api+json');

    $middleware = new EnsureCorrectAPIHeaders;

    /** @var Response $response */
    $response = $middleware->handle($request, function ($request) {
        return new Response();
    });
    $this->assertEquals(200, $response->status());
});
