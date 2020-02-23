<?php

class Remonline {
    private $apiKey;
    private $apiUrl = 'https://api.remonline.ru/';
    private $tokenInfo = [];

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    /*
     * Call Remonline API by methods
     * 
     * @param string $method, for example 'clients/'
     * @param array $params = [
     *  'type' => 'post' || 'get' || 'put', //query type
     *  'query' => true, // $httpBuildQuery = true || false
     *  'params' => [] // all params, if exists
     * ]
    */

    public function call($method, $params = []) {
        $this->checkToken();
        $url = $this->apiUrl.$method;
        if(!isset($params['type'])) {
            $params['type'] = 'get';
        }
        if(!isset($params['query'])) {
            $params['query'] = true;
        }
        $params['params']['token'] = $this->tokenInfo['token'];
        $result = $this->callCurl($url, $params['params'], $params['type'], $params['query']);
        return $result;
    }

    private function callCurl($url, $params = [], $type, $httpBuildQuery = false) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_REFERER => ''
        ]);
        if(!empty($params)) {            
            switch($httpBuildQuery) {
                case true:
                    $QueryParams = http_build_query($params);
                    break;
                case false:
                    $QueryParams = $params;
                    break;
            }            
        }
        switch($type) {
            case 'get':
                curl_setopt($ch, CURLOPT_URL, $url.'?'.$QueryParams);
                break;
            case 'post':
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $QueryParams);
                break;
            case 'put':
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $QueryParams);
                break;
        }
        $request = curl_exec($ch);
        $result = json_decode($request);
        return $result;
    }

    private function getToken() {
        $url = $this->apiUrl.'token/new';
        $params = [
            'api_key' => $this->apiKey
        ];
        $request = $this->callCurl($url, $params, 'post', false);
        if($request->success === true) {
            $this->tokenInfo = [
                'token' => $request->token,
                'ts' => time()
            ];
        }
    }

    private function checkToken() {
        if(!isset($this->tokenInfo['token']) || time() - $this->tokenInfo['ts'] >= 10*60) {
            $this->getToken();
        }
    }
}
