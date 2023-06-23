<?php

namespace App\Core\Application\Service;

// фасад выкидывает ошибку при работе с DB не конектится

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use function Symfony\Component\String\b;

class TokenService
{
    /** id user  @var string  */
    private static string $user;
    /** time interval @var int  */
    private static int $interval;
    /** current time @var int  */
    private static int $currentTime;
    /** random string for token @var string  */
    private static string $randomString;


    /**
     * insert token SLL into Database
     *
     * @param $user
     * @param $token
     * @param $codekey
     * @param $iv
     * @return bool
     */


    /**
     * delete token
     *
     * @param $token
     * @return string
     */
    public static function delTokenSSL($uuid):string {
        try {
            $del = DB::table('tokenkeys')->where('uuid', '=', $uuid)->delete();
        }catch (\Exception $e) {
            var_dump($e);
        }
        return (string)$del;
    }

    /**
     * return list tokens
     *
     * @return string
     */
    public static function listTokenSSL():string {
        $select = DB::table('tokenkeys')->select('id','uuid','user_id','token','key','iv','ip')->get();
        return $select->toJson();
    }

    /**
     * add token to base
     *
     * @param $user
     * @param $token
     * @param $key
     * @param $iv
     * @return bool
     */
    public static function insertTokenSSL($user,$token,$key,$iv): bool
    {

        $uuidCheck = true;
        while($uuidCheck) {
            $uuid = uuid_create();
            $uuidCheck = DB::table('tokenkeys')->select('uuid')->where('uuid', "$uuid")->exists();
        }

        try {
            $usersInsert = DB::table('tokenkeys')
                ->insert([
                    'uuid' => "$uuid",
                    'user_id' => ($user == "")?null:$user,
                    'token' => "$token",
                    'key' => "$key",
                    'iv' => "$iv"
                ]);
            return $usersInsert;
        }  catch (\Exception $e) {
            var_dump($e);
            return false;
        }

    }

    /**
     * get parametrs,  create  $currentTime $randomString for token
     *
     * @param $user
     * @param $days
     * @return void
     *
     */
    public static function setParam($user,$days):void{
        self::$user = $user;
        self::$interval = $days * 24 * 60 * 60;
        self::$currentTime  = date('U');
        self::$randomString = Str::random(5);
    }

    /**
     * get date token, create token, insert to database
     * @return void
     */
    public static function releaseToken($user,$days):bool {

        self::setParam($user,$days);

        $plaintext = self::$randomString . '|'. self::$user . '|' . self::$interval . '|' . self::$currentTime;
        // закодировать
        $key = openssl_random_pseudo_bytes(16);
        // вектор
        $cipher = 'AES-128-CBC';
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $token = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        // кодирование для сохранения в базу
        $token = base64_encode($token);
        $iv    = base64_encode($iv);
        $key = base64_encode($key);

        $insert = self::insertTokenSSL($user,$token,$key,$iv);
        return $insert;


    }

    /**
     * check exists token in database, decode, check
     *
     * @param $token
     * @return bool
     */
    public static function checkTokenSSL($token):bool
    {

        $tokenCheck = DB::table('tokenkeys')->select('token')->where('token',"$token")->exists();
        if(!$tokenCheck) return false;

        $tokenValue = DB::table('tokenkeys')->select('token','iv','key')->where('token',"$token")->first();

        $tokenClean = $tokenValue->token;
        // декодируем для того что рабоать с информацие
        $token = base64_decode($tokenValue->token);
        $iv    = base64_decode($tokenValue->iv);
        $codekey = base64_decode($tokenValue->key);
        $original_plaintext = openssl_decrypt($token, "AES-128-CBC", $codekey, OPENSSL_RAW_DATA, $iv);

        $arrToken = explode('|',$original_plaintext);
        $tokenPeriod = (int)$arrToken[2] + (int)$arrToken[3]; //interval + created time = seconds

        if( date('U') > $tokenPeriod)  return false;
        return true;
    }

    /**
     * check database connection
     *
     * @return string
     */
    public static function checkConnection():string{
        try
        {
            DB::connection()->getDatabaseName('is_users');
            DB::connection()->table('tokenkeys')->insert(['user' => 'qwerqwre']);
            return "connection is success";
        } catch (\Exception $e) {
            return "$e";
        }

    }




}
