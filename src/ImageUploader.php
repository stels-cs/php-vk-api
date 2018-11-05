<?php

namespace Vk;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use Vk\Exceptions\FileNotFoundException;
use Vk\Exceptions\VkException;


abstract class ImageUploader
{
    abstract protected function getUploadServerMethod();

    abstract protected function getImageSaveMethod();

    abstract protected function getAccessToken();

    /**
     * @param $path
     * @return mixed
     * @throws FileNotFoundException
     * @throws VkException
     */
    public function uploadImage($path)
    {
        $this->validate($path);
        $imageType = $this->getVkImageType($path);
        $uploadServer = $this->getUploadServer($imageType);
        $saveImageParams = $this->sendImageToVk($uploadServer, $path);
        return $this->saveImageAtVk($saveImageParams);
    }

    /**
     * @param $imageType
     * @return mixed
     * @throws VkException
     */
    private function getUploadServer($imageType)
    {
        $response = Executor::api($this->getUploadServerMethod(), [
                'access_token' => $this->getAccessToken(),
                'image_type' => $imageType,
            ]
        );
        if (!$response->isSuccess()) {
            throw new VkException($response->getMessage(), $response->getCode());
        } else {
            $response = $response->getData();
        }
        if (empty($response['upload_url'])) {
            throw new VkException('Empty upload_url ' . $this->getUploadServerMethod().' '.json_encode($response->getRawResponse()));
        }
        return $response['upload_url'];
    }

    /**
     * @param $uploadServer
     * @param $path
     * @return mixed
     * @throws VkException
     */
    private function sendImageToVk($uploadServer, $path)
    {
        $client = new Client();
        $multipart = new MultipartStream([
            [
                'name' => 'file',
                'contents' => fopen($path, 'r')
            ],
        ]);
        $request = new Request(
            'POST',
            $uploadServer,
            [],
            $multipart
        );
        $response = $client->send($request);
        $response = json_decode($response->getBody()->getContents(), true);
        if (empty($response['hash'])) {
            throw new VkException('Empty hash on image uploading response');
        }
        if (empty($response['image'])) {
            throw new VkException('Empty image on image uploading response');
        }
        return $response;
    }

    /**
     * @param $saveImageParams
     * @return mixed
     * @throws VkException
     */
    private function saveImageAtVk($saveImageParams)
    {
        $saveImageParams['access_token'] = $this->getAccessToken();
        $result = Executor::api($this->getImageSaveMethod(), $saveImageParams);
        if (!$result->isSuccess()) {
            throw new VkException($result->getMessage(), $result->getCode());
        } else {
            $response = $result->getResponse();
        }
        if (empty($response['id'])) {
            throw new VkException('Vk not return image id ' . $this->getImageSaveMethod().' '.json_encode($result->getRawResponse()));
        }
        return $response['id'];
    }

    /**
     * @param $path
     * @throws FileNotFoundException
     */
    private function validate($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException();
        }
    }

    private function getVkImageType($path)
    {
        $imageSize = getimagesize($path);
        $size = $imageSize[0] / 3 . 'x' . $imageSize[1] / 3;
        return $size;
    }
}