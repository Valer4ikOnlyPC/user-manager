<?php

declare(strict_types=1);

namespace UserManager\Core\Context\Application\Service\Photo\UploadPhotosService;

use UserManager\Core\Context\Application\Service\ApplicationService;
use UserManager\Core\Context\Application\Service\Photo\UploadPhotosService\Request\UploadPhotosRequest;
use UserManager\Core\Context\Application\Service\Photo\UploadPhotosService\Response\UploadPhotosResponse;
use UserManager\Core\Context\Application\Service\RequestInterface;
use UserManager\Core\Context\Application\Service\ResponseInterface;
use UserManager\Core\Context\Domain\Model\User\UserRepositoryInterface;
use UserManager\Core\Context\Domain\Service\Photo\Uploader\PhotoUploaderInterface;

/**
 * @method UploadPhotosResponse execute(UploadPhotosRequest $request);
 */
class UploadPhotosService extends ApplicationService
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PhotoUploaderInterface
     */
    private $photoUploader;

    public function __construct(UserRepositoryInterface $userRepository, PhotoUploaderInterface $photoUploader)
    {
        $this->userRepository = $userRepository;
        $this->photoUploader = $photoUploader;
    }

    protected function supports(RequestInterface $request): bool
    {
        return $request instanceof UploadPhotosRequest;
    }

    /**
     * @param UploadPhotosRequest $request
     *
     * @return UploadPhotosResponse
     */
    protected function process(RequestInterface $request): ResponseInterface
    {
        $user = $this->userRepository->find($request->userID());
        $photos = $this->photoUploader->uploadUserImages($request->photos(), $user);

        return new UploadPhotosResponse($photos);
    }
}
