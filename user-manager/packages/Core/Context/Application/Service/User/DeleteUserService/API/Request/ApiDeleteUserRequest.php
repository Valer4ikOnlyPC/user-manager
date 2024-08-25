<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\DeleteUserService\API\Request;

use UserManager\Core\Context\Application\Service\User\DeleteUserService\Request\DeleteUserRequest;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Service\AuthenticationTokenTrait;

class ApiDeleteUserRequest extends DeleteUserRequest implements AuthenticationRequestInterface
{
    use AuthenticationTokenTrait;
}
