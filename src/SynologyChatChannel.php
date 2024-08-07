<?php

namespace NotificationChannels\SynologyChat;

use Illuminate\Notifications\Notification;

class SynologyChatChannel
{
    /**
     * @var SynologyChat
     */
    protected $synologyChat;

    /**
     * Channel constructor.
     *
     * @param SynologyChat $synologyChat
     */
    public function __construct(SynologyChat $synologyChat)
    {
        $this->synologyChat = $synologyChat;
    }

    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSynologyChat($notifiable);

        if (! $message instanceof SynologyChatMessage) {
            return;
        }

        // if the recipient is not defined get it from the notifiable object
        if ($message->toNotGiven()) {
            $to = $notifiable->routeNotificationFor('synologyChat', $notification);

            $message->to($to);
        }

        $response = $this->synologyChat->send($message->getWebhookUrl(), $message->toArray());

        return $response;
    }
}
