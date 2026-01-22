<?php

namespace App\Controller\LiveStream;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\LiveStream\LiveStreamManager;
use App\Component\User\CurrentUser;
use App\Controller\Base\AbstractController;
use App\Repository\LiveStreamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class StartLiveStreamAction extends AbstractController
{
    public function __construct(
        private readonly LiveStreamRepository $repository,
        private readonly LiveStreamManager    $liveStreamManager,
        SerializerInterface                   $serializer,
        ValidatorInterface                    $validator,
        CurrentUser                           $currentUser
    )
    {
        parent::__construct($serializer, $validator, $currentUser);
    }

    public function __invoke(int $id): Response
    {
        $stream = $this->repository->find($id);

        $stream->setIsActive(true);
        $this->liveStreamManager->save($stream, true);

        return $this->response(['status' => 'streaming'], 200);
    }
}
