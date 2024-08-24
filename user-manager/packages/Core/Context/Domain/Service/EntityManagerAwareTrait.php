<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Domain\Service;

trait EntityManagerAwareTrait
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function setEntityManager(EntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    protected function em(): EntityManagerInterface
    {
        return $this->em;
    }
}
