<?php

namespace NotificationChannels\SynologyChat;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\SynologyChat\Exceptions\CouldNotSendNotification;
use Psr\Http\Message\ResponseInterface;

class SynologyChat
{
    /**
     * API HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct(HttpClient $http)
    {
        $this->httpClient = $http;
    }

    /**
     * Send a message to a SynologyChat channel.
     *
     *
     * @throws CouldNotSendNotification
     */
    public function send(string $url, array $data): ?ResponseInterface
    {
        if (! $url) {
            throw CouldNotSendNotification::synologyChatWebhookUrlMissing();
        }
        // convert associative array to numeric array in sections (since ms is otherwise not acception the body structure)
        if (isset($data['sections'])) {
            $data['sections'] = array_values($data['sections']);
        }
        try {
            $response = $this->httpClient->post($url, [
                'json' => $data,
            ]);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::synologyChatRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::couldNotCommunicateWithSynologyChat($exception);
        }

        return $response;
    }
}
