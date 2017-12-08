<?php

class PassiveTest extends PHPUnit_Framework_TestCase
{
    public function testUsersGet()
    {
        $executor = new \Vk\Executor();
        $response = $executor->execute( new \Vk\ApiRequest('users.get', ['user_ids' => '6492,2050']) );
        if ($response->isSuccess()) {
            $list = $response->getResponse();
            if (is_array($list) && count($list) == 2) {
                foreach ($list as $user) {
                    if (!in_array($user['id'], [6492,2050])) {
                        throw new \Exception("Bad user id ".$response->getRawResponse());
                    }
                }
                return true;
            } else {
                throw new \Exception("Bad response ".$response->getRawResponse());
            }
        } else {
            throw new \Exception($response->getRawResponse(), $response->getCode());
        }
    }
}