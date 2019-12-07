<?php

namespace simplerest\tests;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use simplerest\models\UsersModel;
use simplerest\libs\Database;
use simplerest\libs\Debug;

define('HOST', 'simplerest.lan');
define('BASE_URL', 'http://'. HOST .'/');

// API UNIT TEST
class ApiTest extends TestCase
{   

	function __construct() {
		parent::__construct();
        $this->config = include 'config/config.php';
    }

	private function login($credentials){
		$ch = curl_init();

        $data = json_encode($credentials);

        curl_setopt_array($ch, array(
        CURLOPT_URL => BASE_URL . "auth/login",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "Accept: */*",
            "Accept-Encoding: gzip, deflate",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Content-Length: ". strlen($data),
            "Content-Type: text/plain",
            "Host: " . HOST,
            "cache-control: no-cache"
            ),
        ));

		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
			$error_msg = curl_error($ch);
			throw new \Exception("$error_msg ($http_code)");
		}
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

		$res = json_decode($response, true);
		
        if (isset($res['error']) && !empty($res['error']))
            throw new \Exception($res['error']);

		$this->assertTrue(
            isset($res['data']['access_token']) && isset($res['data']['refresh_token']) && isset($res['data']['expires_in'])
        );		
        
        return [$res['data']['access_token'], $res['data']['refresh_token']];
    }
    
    private function get_me(string $at){
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/me",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

      
        if (!isset($res['data']['id']) || !isset($res['data']['email']))
            throw new \Exception("Empty uid or email");       

        // [id, username, emai,... ]
        return $res['data'];
    }

    /*
        /api/v1/me
        Case: OK
    */
	public function testgetme()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/me",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']['id']) && isset($res['data']['email'])
        );
    }
    
    /*
        /api/v1/products
    */
	public function testgetproducts()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where(['belongs_to', $this->get_me($at)['id']])->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }
    
    
    /*
        get
        Case: found
    */
    public function testgetproduct()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        $uid = $this->get_me($at)['id'];
        $id  = Database::table('products')->where(['belongs_to' => $uid])->value('id');

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $error_msg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {           
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['error']) && !isset($res['paginator'])
        );

        $this->assertTrue(
            empty($res['error'])
        );

        $item = Database::table('products')->where(['id', $id])->setFetchMode('ASSOC')->first();
        $this->assertEquals($item, $res['data']); 
    }

    /*
        get
        Case: not found
    */
    public function testgetproductnotfound()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products/10000000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $error_msg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($http_code != 404)
            throw new \Exception("Unexpected http code ($http_code) - $error_msg");
    }

    public function testpagesize1a()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $error_msg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {           
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['error']) && isset($res['paginator'])
        );

        $this->assertTrue(
            empty($res['error'])
        );

        $cnt = Database::table('products')->count();

        $this->assertTrue(
            count($res['data']) == min($this->config['paginator']['default_limit'], $cnt)
        );
    }

    public function testpagesize1b()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?pageSize=5",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $error_msg = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {           
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['error']) && isset($res['paginator'])
        );

        $this->assertTrue(
            empty($res['error'])
        );

        $cnt = Database::table('products')->count();

        $this->assertTrue(
            count($res['data']) == min(5, $cnt)
        );
    }

    public function testfilter001()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?name=Escalera",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where(['belongs_to' => $this->get_me($at)['id'], 'name' => 'Escalera'])->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter001b()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?description[eq]=metal",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['description', 'metal']
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter002()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?name=Escalera&cost=80",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where(['belongs_to' => $this->get_me($at)['id'], 
        'name' => 'Escalera', 'cost' => 80])->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter003()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?description[in]=metal,bronce,plastico",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['description', ['metal', 'bronce', 'plastico' ] ]
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter003b()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?description=metal,bronce,plastico",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['description', ['metal', 'bronce', 'plastico' ] ]
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter004()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?description[notIn]=metal,bronce,plastico",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['description', ['metal', 'bronce', 'plastico' ], 'NOT IN' ]
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter006()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?name[startsWith]=Caja%20",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['name', 'Caja %', 'LIKE']
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter007()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?name[notStartsWith]=Caja%20",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $res = json_decode($response, true);

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['name', 'Caja %', 'NOT LIKE']
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter008()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?name[endsWith]=ora",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response, true);

        //Debug::dd($res, 'Response:');

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);

            if (isset($res['error']))
                Debug::dd($res['error']);

            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['name', '%ora', 'LIKE']
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }

    public function testfilter009()
    {
        list($at, $rt) = $this->login(['email' => "tester3@g.c", "password" => "gogogo"]);  
        
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => BASE_URL . "api/v1/products?name[notEndsWith]=ora",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Authorization: Bearer $at",
                "Content-Type: text/plain",
                "Host: " . HOST,
                "cache-control: no-cache"
                ),
            ));

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $res = json_decode($response, true);

        //Debug::dd($res, 'Response:');

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);

            if (isset($res['error']))
                Debug::dd($res['error']);

            throw new \Exception("$error_msg ($http_code)");
        }
        
        if ($http_code != 200)
            throw new \Exception("Unexpected http code ($http_code)");

        $this->assertTrue(
            isset($res['data']) && isset($res['paginator'])
        );

        $model_arr = Database::table('products')->where([
            ['belongs_to', $this->get_me($at)['id']], 
            ['name', '%ora', 'NOT LIKE']
        ])
        ->setFetchMode('ASSOC')->limit($this->config['paginator']['default_limit'])->get();

        $this->assertEquals($model_arr,$res['data']); 
    }
}
