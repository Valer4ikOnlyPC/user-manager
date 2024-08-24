<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security;

use UserManager\Core\Context\Domain\Model\User\UserID;

interface UserInterface
{
    public function ID(): UserID;

    public function login(): string;

    public function password(): string;
}
