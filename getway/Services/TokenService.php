<?php

namespace App\Services\Token;

use Illuminate\Support\Facades\Http;

class TokenService
{

    /**
     * send token to
     *
     * @param string $token
     * @return \GuzzleHttp\Promise\PromiseInterface|\Illuminate\Http\Client\Response
     */
    public static function httpHeader(
       string $token
    ): string
    {
        // серверное
//        $http = config('services.user_management.base_uri').'check-token';
//        var_dump($http);
        // локально
//        $http = 'http://'.config('services.user_management.base_uri').'api/check-token';
        // занесено в env SERVICE_USER_MANAGEMENT_BASE_URI='http://localhost:3000/api'
        $http = config('services.user_management.base_uri').'check-token';
//        var_dump($http);

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer $token"])->get($http );
            $response =  $response->header('Authorization');
        } catch (\Exception $e) {
            $response = $e->getMessage();
            var_dump($response);
        }
        return $response;

    }



}
