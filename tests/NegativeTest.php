<?php

class NegativeTest extends PHPUnit_Framework_TestCase
{
    public function testMessagesSend()
    {
        $executor = new \Vk\Executor();
        $response = $executor->execute( new \Vk\ApiRequest('messages.send', ['message' => 'test']) );
        if ($response->isSuccess()) {
            throw new \Exception("Wtf? correct response? ".$response->getRawResponse());
        } else {
            if ($response->getCode() === 5) {
                return true;
            } else {
                throw new \Exception($response->getRawResponse());
            }
        }
    }
}