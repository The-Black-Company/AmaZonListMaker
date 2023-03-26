<?php

namespace App;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class AmazonResourceOwner implements ResourceOwnerInterface
{
    private $response;

    public function __construct(array $response)
    {
        $this->response = $response;
    }

    public function getId()
    {
        return $this->response['user_id'] ?? null;
    }

    public function toArray()
    {
        return $this->response;
    }
}
