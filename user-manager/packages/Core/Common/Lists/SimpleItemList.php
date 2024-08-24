<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Lists;

use UserManager\Core\Common\Collection\Collection;
use UserManager\Core\Common\Infrastructure\HasSurrogateIdTrait;

abstract class SimpleItemList extends Collection
{
    use HasSurrogateIdTrait;
}
