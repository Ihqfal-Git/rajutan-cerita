<?php

namespace App\Notifications;

use App\Models\AccessRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AccessRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public AccessRequest $accessRequest) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'access_request_id' => $this->accessRequest->id,
            'memory_id'         => $this->accessRequest->memory_id,
            'memory_title'      => $this->accessRequest->memory->title,
            'memory_slug'       => $this->accessRequest->memory->slug,
            'guest_name'        => $this->accessRequest->guest_name ?? 'Seseorang',
            'guest_token'       => $this->accessRequest->guest_token,
            'requested_at'      => $this->accessRequest->created_at->format('d M Y H:i'),
        ];
    }
}