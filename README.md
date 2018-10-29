# VK API Client 1.1.1

Клиент для запросов к API Вконтакте

```bash
composer require stels-cs/php-vk-api
```

```php
$response = \Vk\Executor::api("users.get", ["user_ids" => "6492,2050"]);
if ($response->isSuccess()) {
    $list = $response->getResponse();
        //$list = [
        //  [
        //      [id] => 6492
        //      [first_name] => Андрей
        //      [last_name] => Рогозов
        //  ],[
        //      [id] => 2050
        //      [first_name] => Катя
        //      [last_name] => Лебедева
        //  ]
        //]
} else {
    $code = $response->getCode(); //int
    $message = $response->getMessage(); //string
    $canRetry = $executor->canRetryLaterWithCode($code); //bool
    // if $canRetry == true it "soft" error like network error or vk is down
}
```

```php

$appId = 43251123;
$appSecret = "AFwetrvasfawer";
$redirectUrl = "https://mysite.com/auth";
$code = "caefrvrtsvakmcaoer";

$response = \Vk\Executor::getAccessToken($appId, $appSecret, $redirectUrl, $code);
if ($response->isSuccess()) {
    $data = $response->getData();
    $accessToken = $data["access_token"];
    $expiresIn = $data["expires_in"];
    $userId = $data["user_id"];
} else {
    $code = $response->getCode(); //int
    $message = $response->getMessage(); //string
}

```


```php
$executor = new \Vk\Executor();
$response = $executor->execute( new \Vk\ApiRequest('users.get', ['user_ids' => '6492,2050']) );
if ($response->isSuccess()) {
    $list = $response->getData();
    //$list = [
    //  [
    //      [id] => 6492
    //      [first_name] => Андрей
    //      [last_name] => Рогозов
    //  ],[
    //      [id] => 2050
    //      [first_name] => Катя
    //      [last_name] => Лебедева
    //  ]
    //]
} else {
    $code = $response->getCode(); //int
    $message = $response->getMessage(); //string
    $canRetry = $executor->canRetryLaterWithCode($code); //bool
    // if $canRetry == true it "soft" error like network error or vk is down
}
```