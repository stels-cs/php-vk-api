<?php


namespace Vk;


class Executor
{
    protected $version;
    protected $timeout;
    protected $language;
    protected $accessToken;

    public function __construct($accessToken = null, $version = '5.69', $language = 'ru', $timeout = 600)
    {
        $this->version = $version;
        $this->timeout = $timeout;
        $this->language = $language;
        $this->accessToken = $accessToken;
    }

    public function execute(ApiRequest $request): ApiResponse
    {
        $params = $request->getParams();
        $params = $this->applyDefaultParams($params);
        $data = http_build_query($params);
        $opts = ['http' =>
            [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $data,
                'timeout' => $this->timeout,
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($opts);
        try {
            $result = file_get_contents('https://api.vk.com/method/' . $request->getMethod(), false, $context);
        } catch (\Exception $e) {
            $result = '';
        }
        $json = json_decode($result, true);
        $response = new ApiResponse($request);
        $response->setRawResponse($result);
        if (!$json && !is_array($json)) {
            $response->setCode(500);
            return $response;
        }
        if (isset($json['response'])) {
            $response->setCode(200);
            $response->setResponse($json['response']);
            if (isset($json['execute_errors'])) {
                $response->setExecuteErrors($json['execute_errors']);
            }
        } elseif (isset($json['error'])) {
            $code = $json['error']['error_code'];
            $message = $json['error']['error_msg'];
            $response->setCode((int)$code);
            $response->setMessage($message);
            $response->setCaptchaSig($json['error']['captcha_sid'] ?? null);
            $response->setCaptchaImg($json['error']['captcha_img'] ?? null);
        } else {
            $response->setCode(500);
        }
        return $response;
    }

    private function applyDefaultParams($params)
    {
        if (!isset($params['v'])) {
            $params['v'] = $this->version;
        }
        if (!isset($params['lang'])) {
            $params['lang'] = $this->language;
        }
        if (!isset($params['access_token']) && $this->accessToken) {
            $params['access_token'] = $this->language;
        }
        return $params;
    }

    public function canRetryLaterWithCode($code)
    {
        return in_array($code, [
            500,
            0,
            1,
            6,
            9,
            10,
            18
        ]);
    }
}