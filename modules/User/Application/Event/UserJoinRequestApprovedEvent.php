<?php

declare(strict_types=1);

namespace Modules\User\Application\Event;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\User\Domain\ValueObject\UserId;

final class UserJoinRequestApprovedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly UserId $userId) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId->value),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.join_request.approved';
    }
}
