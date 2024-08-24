<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Security\Authentication\Token;

use UserManager\Core\Context\Domain\Model\Security\UserInterface;

interface TokenBuilderInterface
{
    public function createToken(UserInterface $user): string;
}
