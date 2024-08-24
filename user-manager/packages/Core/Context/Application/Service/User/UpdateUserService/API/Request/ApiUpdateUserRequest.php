<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\UpdateUserService\API\Request;

use UserManager\Core\Context\Application\Service\User\UpdateUserService\Request\UpdateUserRequest;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Service\AuthenticationTokenTrait;

class ApiUpdateUserRequest extends UpdateUserRequest implements AuthenticationRequestInterface
{
    use AuthenticationTokenTrait;
}
