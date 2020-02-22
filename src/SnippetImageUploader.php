<?php


namespace Vk;


use Vk\Exceptions\VkException;

abstract class SnippetImageUploader extends ImageUploader
{

    protected $token;
    protected $ownerId;

    public function __construct($token, $ownerId)
    {
        $this->token = $token;
        $this->ownerId = $ownerId;
    }

    abstract public function getUploadServerMethod();

    abstract public function getImageSaveMethod();

    protected function getAccessToken()
    {
        return $this->token;
    }

    /**
     * @param $params
     * @return mixed
     * @throws Exceptions\VkException
     */
    protected function saveImageAtVk($params) {
        $params['owner_id'] = $this->ownerId;
        $params['image'] = $params['photo'];
        return $this->saveImage($params);
    }

//    /**
//     * @param string $accessToken
//     * @param int $ownerId
//     * @param string $path
//     * @return mixed
//     * @throws Exceptions\FileNotFoundException
//     * @throws Exceptions\VkException
//     * @throws \GuzzleHttp\Exception\GuzzleException
//     */
//    public static function upload(string $accessToken, int $ownerId, string $path) {
//        $uploader = new self($accessToken, $ownerId);
//        return $uploader->uploadImage($path);
//    }

    protected function getVkImageType($path) {
        return null;
    }

    /**
     * @param $imageType
     * @return mixed
     * @throws VkException
     * @throws Exceptions\VkException
     */
    protected function getUploadServer($imageType)
    {
        return $this->getUploadServerRequest([
            'access_token' => $this->getAccessToken(),
            'owner_id' => $this->ownerId,
        ]);
    }

    protected function getFileNameInPostRequest() {
        return 'photo';
    }
}