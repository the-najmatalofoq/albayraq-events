<?php

declare(strict_types=1);

namespace Modules\DigitalSignature\Infrastructure\Persistence\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\EventContract\Infrastructure\Persistence\Eloquent\EventContractModel;
use Carbon\Carbon;

/**
 * Digital signature model
 *
 * @property string $id
 * @property string $contract_id
 * @property string $signature_svg
 * @property string $ip_address
 * @property string $user_agent
 * @property Carbon $signed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read EventContractModel $contract
 */
final class DigitalSignatureModel extends Model
{
    use HasUuids;

    protected $table = 'digital_signatures';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'contract_id',
        'signature_svg',
        'ip_address',
        'user_agent',
        'signed_at',
    ];

    protected function casts(): array
    {
        return [
            'signed_at' => 'datetime',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(EventContractModel::class, 'contract_id');
    }
}
