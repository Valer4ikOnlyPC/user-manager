<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Model\Photo;

use UserManager\Core\Context\Domain\Model\ResourceInterface;

class Photo implements ResourceInterface
{
    /**
     * @var PhotoID
     */
    private $ID;

    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $webDir;

    public function __construct(PhotoID $ID, string $dir, string $webDir)
    {
        $this->setID($ID);
        $this->setDir($dir);
        $this->setWebDir($webDir);
    }

    public function ID(): PhotoID
    {
        return $this->ID;
    }

    private function setID(PhotoID $ID): void
    {
        $this->ID = $ID;
    }

    public function dir(): string
    {
        return $this->dir;
    }

    private function setDir(string $dir): void
    {
        $this->dir = $dir;
    }

    public function webDir(): string
    {
        return $this->webDir;
    }

    private function setWebDir(string $webDir): void
    {
        $this->webDir = $webDir;
    }
}
