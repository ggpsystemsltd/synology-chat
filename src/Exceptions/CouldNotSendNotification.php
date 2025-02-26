<?php

namespace NotificationChannels\SynologyChat\Exceptions;

use GuzzleHttp\Exception\ClientException;

final class CouldNotSendNotification extends \Exception
{
    /**
     * Thrown when there's a bad request and an error is responded.
     *
     * @return static
     */
    public static function synologyChatRespondedWithAnError(ClientException $exception)
    {
        if (! $exception->hasResponse()) {
            return new self('Synology Chat responded with an error but no response body found');
        }

        $statusCode = $exception->getResponse()->getStatusCode();
        $description = $exception->getMessage();

        return new self("Synology Chat responded with an error `{$statusCode} - {$description}`");
    }

    /**
     * Thrown when we're unable to communicate with Microsoft Teams.
     *
     * @return static
     */
    public static function couldNotCommunicateWithSynologyChat(\Exception $exception)
    {
        return new self("The communication with Synology Chat failed. `{$exception->getMessage()}`");
    }

    /**
     * Thrown when there is no webhook url provided.
     *
     * @return static
     */
    public static function synologyChatWebhookUrlMissing()
    {
        return new self('Synology Chat webhook url is missing. Please add it as param over the SynologyChatMessage::to($url) method or return it in the notifiable model by providing the method Model::routeNotificationForSynologyChat().');
    }
}
