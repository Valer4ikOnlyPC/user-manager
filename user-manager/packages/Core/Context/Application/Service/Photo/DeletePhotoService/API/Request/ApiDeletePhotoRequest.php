<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\DeletePhotoService\API\Request;

use UserManager\Core\Context\Application\Service\Photo\DeletePhotoService\Request\DeletePhotoRequest;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Service\AuthenticationTokenTrait;

class ApiDeletePhotoRequest extends DeletePhotoRequest implements AuthenticationRequestInterface
{
    use AuthenticationTokenTrait;
}
