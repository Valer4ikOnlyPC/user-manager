<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\User\GetUsersService\API\Request;

use UserManager\Core\Context\Application\Service\User\GetUsersService\Request\GetUsersRequest;
use UserManager\Core\Context\Domain\Model\Security\Authentication\AuthenticationRequest\AuthenticationRequestInterface;
use UserManager\Core\Context\Domain\Service\AuthenticationTokenTrait;

class ApiGetUsersRequest extends GetUsersRequest implements AuthenticationRequestInterface
{
    use AuthenticationTokenTrait;
}
