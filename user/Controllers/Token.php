<?php

namespace App\Http\Controllers\Token;
use App\Http\Controllers\Controller;



use App\Core\Application\Service\TokenService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;



class Token extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    //http://localhost:3000/api/del-token?id=5


    /**
     * delete token SSL
     *
     * @param Request $request
     * @return Response
     */
    public function delTokenSSL(
        Request $request
    ): Response
    {
        $del = TokenService::delTokenSSL($request->post('uuid'));
        return \response("$del")->header('Content-type', 'text/html');
    }

    /**
     * return json token list
     *
     * @param Request $request
     * @return Response
     */
    public function listTokenSSL(
        Request $request
    ): Response
    {
        $tokens = TokenService::listTokenSSL();
        return \response("$tokens")->header('Content-type', 'application/json');
    }

    /**
     *  create and add token to the database
     *  http://is-user-management-api-laravel/api/add-token?user=asdf&days=3
     * @param Request $request
     * @return Response
     *
     */

    public function addTokenSSL(
        Request $request
    ): Response
    {
        // данные для токена
        $user = $request->get('user')?$request->get('user'):"";     // id пользователя
        $days = $request->get('days');                                  // время действия в днях

        if(!$days)  return \response("?user=&days=")
            ->header('Content-Typed', 'text/html');

        $release = TokenService::releaseToken($user,$days);
        return \response("released: $release; user: $user;")
            ->header('Content-Typed', 'text/html');
    }

    /**
     * create and add token to the database post method
     *
     * @param Request $request
     * @return Response
     */
    public function PostAddTokenSSL(
        Request $request
    ): Response
    {
        // данные для токена
        $user = $request->post('user')?$request->post('user'):"";     // id пользователя
        $days = $request->post('days');                                   // время действия в днях

        if(!$days)  return \response("?user=&days=")
            ->header('Content-Typed', 'text/html');

        $release = TokenService::releaseToken($user,$days);
        return \response("released: $release; user: $user;")
            ->header('Content-Typed', 'text/html');

    }


    public function checkTokenSSL(
        Request $request
    ): Response
    {

        // ответ в is-getway-api-lumen
        $check = TokenService::checkTokenSSL($request->bearerToken());
        return $check ? \response('')->header('Authorization','success'): \response('')->header('Authorization','no');
    }


    public function checkConnection(
        Request $request
    ):Response
    {
        $connect = TokenService::checkConnection();
        return \response("$connect")->header('Content-Type', 'text/html');
    }


}
