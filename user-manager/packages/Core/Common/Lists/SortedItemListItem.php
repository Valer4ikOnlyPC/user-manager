<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Lists;

use UserManager\Core\Common\Collection\SortedCollectionItem;
use UserManager\Core\Common\Infrastructure\HasSurrogateIdTrait;

abstract class SortedItemListItem extends SortedCollectionItem
{
    use HasSurrogateIdTrait;
}
