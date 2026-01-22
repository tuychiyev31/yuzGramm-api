<?php
namespace App\Service;

use Aws\IVS\IVSClient;
use Aws\Exception\AwsException;

class AWSIVSService
{
    private IVSClient $client;

    public function __construct()
    {
        $this->client = new IVSClient([
            'version' => 'latest',
            'region' => $_ENV['AWS_REGION'],
            'credentials' => [
                'key' => $_ENV['AWS_ACCESS_KEY_ID'],
                'secret' => $_ENV['AWS_SECRET_ACCESS_KEY']
            ]
        ]);
    }

    /**
     * Channel + StreamKey (AWS автоматик яратади)
     * @throws \Exception
     */
    public function createChannel(string $channelName): array
    {
        try {
            $result = $this->client->createChannel([
                'name' => $channelName,
                'latencyMode' => 'LOW',
                'type' => 'STANDARD',
                'authorized' => false
            ]);

            return [
                'channel' => $result['channel'],
                'streamKey' => $result['streamKey'], // 👈 МАНА ШУ ЕТАРЛИ
            ];

        } catch (AwsException $e) {
            throw new \Exception('AWS IVS Error: ' . $e->getAwsErrorMessage());
        }
    }

    public function stopStream(string $channelArn): void
    {
        try {
            $this->client->stopStream([
                'channelArn' => $channelArn
            ]);
        } catch (AwsException $e) {
        }
    }

    public function getStreamInfo(string $channelArn): ?array
    {
        try {
            $result = $this->client->getStream([
                'channelArn' => $channelArn
            ]);
            return $result['stream'];
        } catch (AwsException $e) {
            return null;
        }
    }

    public function deleteChannel(string $channelArn): void
    {
        try {
            $this->client->deleteChannel([
                'arn' => $channelArn
            ]);
        } catch (AwsException $e) {
            throw new \Exception('Cannot delete channel: ' . $e->getAwsErrorMessage());
        }
    }
}
