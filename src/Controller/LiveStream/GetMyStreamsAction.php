<?php

namespace App\Controller\LiveStream;

use App\Controller\Base\AbstractController;
use App\Repository\LiveStreamRepository;
use Symfony\Component\HttpFoundation\Response;

class GetMyStreamsAction extends AbstractController
{
    public function __invoke(LiveStreamRepository $repository): Response
    {
        $streams = $repository->findBy(['host' => $this->getUser()]);

        return $this->response($streams, 200);
    }
}
