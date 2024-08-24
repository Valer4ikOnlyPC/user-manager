<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Lists;

use UserManager\Core\Common\Collection\SortedCollection;
use UserManager\Core\Common\Infrastructure\HasSurrogateIdTrait;

abstract class SortedItemList extends SortedCollection
{
    use HasSurrogateIdTrait;
}
