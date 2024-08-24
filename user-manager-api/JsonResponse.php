<?php
namespace UserManager\UserManagerApi;

class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    /**
     * @param array|string $data
     *
     * @return JsonResponse
     */
    public function setData($data = [])
    {
        if (true === is_string($data)) {
            $this->data = $data;
            return $this->update();
        }

        return parent::setData($data);
    }
}