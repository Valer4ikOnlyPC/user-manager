<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Exception\Model;

class ExistingResourceException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Полученный ресурс уже есть в репозитарии.');
    }
}
