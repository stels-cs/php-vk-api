# VK API Client

Штука для запросов к API Вконтакте

```bash
composer require stels-cs/php-vk-api
```

```php
$response = \Vk\Executor::api('users.get', [
                                            'user_ids' => '6492,2050', 
                                            'v'=>'5.85', 
                                            'access_token'=>'abcd34fac454bd....'
                                            ]);
```



```php
$accessToken = 'abcd34fac454bd....';
$version = '5.85';
$language = 'ru';
$timeout = 600; //Сколько секунд ждать товета от API
$executor = new \Vk\Executor($accessToken, $version, $language, $timeout);
$response = $executor->call('users.get', ['user_ids' => '6492,2050']);
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
