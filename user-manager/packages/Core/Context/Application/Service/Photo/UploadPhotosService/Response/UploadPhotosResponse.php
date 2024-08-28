<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\UploadPhotosService\Response;

use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Domain\Service\Photo\DTO\UploadPhotosDirectoryDTO;

class UploadPhotosResponse implements ResponseInterface
{
    /**
     * @var UploadPhotosDirectoryDTO
     */
    private $result;

    public function __construct(UploadPhotosDirectoryDTO $result)
    {
        $this->setResult($result);
    }

    public function result(): UploadPhotosDirectoryDTO
    {
        return $this->result;
    }

    private function setResult(UploadPhotosDirectoryDTO $result): void
    {
        $this->result = $result;
    }
}
