<?php

namespace App\Http\Controllers\Token;
use App\Http\Controllers\Controller;
use \App\Services\Token\TokenService;

use App\Services\Token\Token as TokenRequest;

use App\Traits\RequestService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Cookie;


class Token extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        private readonly TokenRequest $tokenRequest
    )
    {
        parent::__construct();
    }


    public function addToken(
        Request $request
    ):Response
    {
        $response = $this->tokenRequest->addToken($request->all());
            $this->response->status(200);
            $this->response->data($response);
           ($response == "?user=&days=") ? $this->response->message('Токен не добавлен'):$this->response->message('Токен добавлен');
        return $this->response->get();
//        return \response("$response ")->header('Content-Type', 'text/html');
    }

    public function listToken(
        Request $request
    ): Response
    {
        $response = $this->tokenRequest->listToken($request->all());
        $this->response->status(200);
        $this->response->data($response);
        $this->response->message('Список получен');
        return $this->response->get();
//        return  \response("$response ")->header('Content-Type', 'text/html');
    }

    public function delToken(
        Request $request
    ):Response
    {
        $response = $this->tokenRequest->delToken($request->all());
        $this->response->status(200);
        $this->response->data($response);
        ($response == '0') ? $this->response->message('Токен не удален'):$this->response->message('Токен удален');
        return $this->response->get();
//        return \response("$response ")->header('Content-Type', 'text/html');
    }


    public function checkTokenAuth(
        Request $request
    ):Response
    {
        return \response("Access is allowed ")->header('Content-Type', 'text/html');
    }

}
