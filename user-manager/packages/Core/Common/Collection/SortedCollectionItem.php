<?php

declare(strict_types=1);

namespace UserManager\Core\Common\Collection;

class SortedCollectionItem
{
    /**
     * @var int
     */
    protected $position;

    /**
     * @var mixed
     */
    protected $element;

    /**
     * @param mixed $element
     */
    public function __construct($element, int $position)
    {
        $this->setElement($element);
        $this->setPosition($position);
    }

    public function position(): int
    {
        return $this->position;
    }

    protected function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function updatePosition(int $position): void
    {
        $this->position = $position;
    }

    /**
     * @return mixed
     */
    public function element()
    {
        return $this->element;
    }

    /**
     * @param mixed $element
     */
    protected function setElement($element): void
    {
        $this->element = $element;
    }
}
