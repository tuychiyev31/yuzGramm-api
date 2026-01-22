<?php

namespace App\Controller\LiveStream;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\LiveStream\LiveStreamManager;
use App\Component\User\CurrentUser;
use App\Controller\Base\AbstractController;
use App\Entity\LiveStream;
use App\Repository\LiveStreamRepository;
use App\Service\AWSIVSService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class StopLiveStreamAction extends AbstractController
{
    public function __construct(
        private readonly LiveStreamManager $liveStreamManager,
        private readonly AWSIVSService     $awsServices,
        SerializerInterface                $serializer,
        ValidatorInterface                 $validator,
        CurrentUser                        $currentUser
    )
    {
        parent::__construct($serializer, $validator, $currentUser);
    }

    public function __invoke(LiveStreamRepository $repository, int $id): Response
    {
        $stream = $repository->find($id);

        $stream->setIsActive(false);
        $this->liveStreamManager->save($stream,true);

        $this->awsServices->stopStream($stream->getChannelArn());

        return $this->response(['status' => 'stopped'], 200);
    }
}
