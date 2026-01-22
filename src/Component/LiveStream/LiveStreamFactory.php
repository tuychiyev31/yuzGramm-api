<?php

namespace App\Component\LiveStream;

use App\Entity\LiveStream;
use App\Entity\User;

class LiveStreamFactory
{
    public function create(
        string $title,
        User   $user,
        string $channelArn,
        string $ingestEndpoint,
        string $playbackUrl,
        string $streamKey
    ): LiveStream
    {
        $stream = new LiveStream();
        $stream->setTitle($title);
        $stream->setUser($user);
        $stream->setChannelArn($channelArn);
        $stream->setIngestEndpoint($ingestEndpoint);
        $stream->setPlaybackUrl($playbackUrl);
        $stream->setStreamKey($streamKey);
        $stream->setIsActive(true);

        return $stream;
    }
}
