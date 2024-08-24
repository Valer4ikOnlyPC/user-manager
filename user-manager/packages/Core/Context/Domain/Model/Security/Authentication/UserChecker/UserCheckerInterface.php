<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\UserChecker;

use UserManager\Core\Common\Exception\AuthenticationException;
use UserManager\Core\Context\Domain\Model\Security\UserInterface;

interface UserCheckerInterface
{
    /**
     * @throws AuthenticationException
     */
    public function checkUser(?UserInterface $user): void;
}
