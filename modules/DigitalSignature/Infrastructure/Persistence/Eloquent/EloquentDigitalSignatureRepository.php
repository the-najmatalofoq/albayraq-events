<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Infrastructure\Persistence\Eloquent;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\DigitalSignature\Infrastructure\Persistence\DigitalSignatureReflector;

final class EloquentDigitalSignatureRepository implements DigitalSignatureRepositoryInterface
{
    public function nextIdentity(): DigitalSignatureId
    {
        return DigitalSignatureId::generate();
    }

    public function save(DigitalSignature $signature): void
    {
        $model = DigitalSignatureModel::updateOrCreate(
            ['id' => $signature->uuid->value],
            [
                'contract_id' => $signature->contractId,
                'signature_svg' => $signature->signatureSvg,
                'ip_address' => $signature->ipAddress,
                'user_agent' => $signature->userAgent,
                'signed_at' => $signature->signedAt,
                'created_at' => $signature->createdAt,
                'updated_at' => $signature->updatedAt,
            ]
        );
    }

    public function findById(DigitalSignatureId $id): ?DigitalSignature
    {
        $model = DigitalSignatureModel::where('id', $id->value)->first();

        return $model ? DigitalSignatureReflector::fromModel($model) : null;
    }

    public function findByContractId(string $contractId): ?DigitalSignature
    {
        $model = DigitalSignatureModel::where('contract_id', $contractId)->first();

        return $model ? DigitalSignatureReflector::fromModel($model) : null;
    }

    public function delete(DigitalSignatureId $id): void
    {
        DigitalSignatureModel::where('id', $id->value)->delete();
    }

    public function findAll(): array
    {
        $models = DigitalSignatureModel::all();

        return array_map(
            fn($model) => DigitalSignatureReflector::fromModel($model),
            $models->all()
        );
    }

    public function findAllPaginated(
        int $page = 1,
        int $perPage = 15,
        array $filters = [],
        string $orderBy = 'signed_at',
        string $orderDirection = 'desc'
    ): array {
        $query = DigitalSignatureModel::query();

        // Apply filters
        if (isset($filters['contract_id'])) {
            $query->where('contract_id', $filters['contract_id']);
        }

        if (isset($filters['from_date'])) {
            $query->where('signed_at', '>=', $filters['from_date']);
        }

        if (isset($filters['to_date'])) {
            $query->where('signed_at', '<=', $filters['to_date']);
        }

        $total = $query->count();

        $models = $query->orderBy($orderBy, $orderDirection)
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $data = array_map(
            fn($model) => DigitalSignatureReflector::fromModel($model),
            $models->all()
        );

        return [
            'data' => $data,
            'total' => $total,
        ];
    }
}
