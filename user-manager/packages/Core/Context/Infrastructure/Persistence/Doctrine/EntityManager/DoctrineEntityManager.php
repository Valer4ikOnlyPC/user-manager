<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Infrastructure\Persistence\Doctrine\EntityManager;

use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;
use UserManager\Core\Context\Domain\Service\EntityManagerInterface;

class DoctrineEntityManager implements EntityManagerInterface
{
    /**
     * @var DoctrineEntityManagerInterface
     */
    private $em;

    public function __construct(DoctrineEntityManagerInterface $em)
    {
        $this->setEm($em);
    }

    private function setEm(DoctrineEntityManagerInterface $em): void
    {
        $this->em = $em;
    }

    private function em(): DoctrineEntityManagerInterface
    {
        return $this->em;
    }

    public function flush(): void
    {
        $this->em()->flush();
    }

    public function transactional(callable $func)
    {
        return $this->em()->transactional($func);
    }
}
