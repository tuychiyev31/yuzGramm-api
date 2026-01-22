<?php

namespace App\Controller\LiveStream;

use ApiPlatform\Validator\ValidatorInterface;
use App\Component\User\CurrentUser;
use App\Controller\Base\AbstractController;
use App\Entity\LiveStream;
use App\Repository\LiveStreamRepository;
use App\Service\AWSIVSService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class LiveStreamActiveAction extends AbstractController
{
    public function __construct(
        private readonly LiveStreamRepository $liveStreamRepository,
        private readonly AWSIVSService        $awsIVSService,
        SerializerInterface                   $serializer,
        ValidatorInterface                    $validator,
        CurrentUser                           $currentUser
    )
    {
        parent::__construct($serializer, $validator, $currentUser);
    }

    public function __invoke(): Response
    {
        $streams = $this->liveStreamRepository->findBy(['isActive' => true]);

        $result = array_map(function (LiveStream $stream) {
            $streamInfo = $this->awsIVSService->getStreamInfo($stream->getChannelArn());

            return [
                'id' => $stream->getId(),
                'title' => $stream->getTitle(),
                'host' => [
                    'id' => $stream->getUser()->getId(),
                    'username' => $stream->getUser()->getUsername()
                ],
                'playbackUrl' => $stream->getPlaybackUrl(),
                'viewerCount' => $streamInfo['viewerCount'] ?? 0,
                'createdAt' => $stream->getCreatedAt()->format('Y-m-d H:i:s')
            ];
        }, $streams);

        return $this->response($result, 200);
    }
}
