<?php

namespace App\Controller\LiveStream;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\LiveStream\LiveStreamFactory;
use App\Component\LiveStream\LiveStreamManager;
use App\Component\User\CurrentUser;
use App\Controller\Base\AbstractController;
use App\Entity\LiveStream;
use App\Service\AWSIVSService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class CreateLiveStreamAction extends AbstractController
{
    public function __construct(
        private readonly AWSIVSService     $awsServices,
        private readonly LiveStreamFactory $liveStreamFactory,
        private readonly LiveStreamManager $liveStreamManager,
        SerializerInterface                $serializer,
        ValidatorInterface                 $validator,
        CurrentUser                        $currentUser
    )
    {
        parent::__construct($serializer, $validator, $currentUser);
    }

    /**
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? 'Untitled Stream';

        $awsData = $this->awsServices->createChannel('stream-' . $this->getUser()->getId() . '-' . time());

        $stream = $this->liveStreamFactory->create(
            $title,
            $this->getUser(),
            $awsData['channel']['arn'],
            $awsData['channel']['ingestEndpoint'],
            $awsData['channel']['playbackUrl'],
            $awsData['streamKey']['value']
        );
        $this->liveStreamManager->save($stream, true);

        return $this->response($stream, Response::HTTP_CREATED);
    }
}