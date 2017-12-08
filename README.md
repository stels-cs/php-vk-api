# VK API Client

Штука для запросов к API Вконтакте

```php
$executor = new \Vk\Executor();
$response = $executor->execute( new \Vk\ApiRequest('users.get', ['user_ids' => '6492,2050']) );
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