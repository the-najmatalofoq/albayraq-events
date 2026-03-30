<?php
declare(strict_types=1);

namespace Modules\DigitalSignature\Infrastructure\Persistence\Eloquent;

use Modules\DigitalSignature\Domain\DigitalSignature;
use Modules\DigitalSignature\Domain\Repository\DigitalSignatureRepositoryInterface;
use Modules\DigitalSignature\Domain\ValueObject\DigitalSignatureId;
use Modules\DigitalSignature\Infrastructure\Persistence\DigitalSignatureReflector;
use Modules\Shared\Domain\ValueObject\FilterCriteria;
use Modules\Shared\Domain\ValueObject\PaginationCriteria;
use Modules\Shared\Domain\ValueObject\SortCriteria;

final class EloquentDigitalSignatureRepository implements DigitalSignatureRepositoryInterface
{
    public function nextIdentity(): DigitalSignatureId
    {
        return DigitalSignatureId::generate();
    }

    public function save(DigitalSignature $signature): void
    {
        DigitalSignatureModel::updateOrCreate(
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

    public function findAll(?FilterCriteria $filters = null, ?SortCriteria $sort = null): array
    {
        $query = $this->applyFilters(DigitalSignatureModel::query(), $filters);
        $query = $this->applySort($query, $sort ?? new SortCriteria('signed_at', 'desc'));

        $models = $query->get();

        return array_map(
            fn($model) => DigitalSignatureReflector::fromModel($model),
            $models->all()
        );
    }

    public function findAllPaginated(
        PaginationCriteria $pagination,
        ?FilterCriteria $filters = null,
        ?SortCriteria $sort = null
    ): array {
        $query = $this->applyFilters(DigitalSignatureModel::query(), $filters);

        $total = $query->count();

        $query = $this->applySort($query, $sort ?? new SortCriteria('signed_at', 'desc'));

        $models = $query->skip($pagination->offset())
            ->take($pagination->perPage)
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

    private function applyFilters($query, ?FilterCriteria $filters)
    {
        if (!$filters) {
            return $query;
        }

        if ($filters->has('contract_id')) {
            $query->where('contract_id', $filters->get('contract_id'));
        }

        if ($filters->has('from_date')) {
            $query->where('signed_at', '>=', $filters->get('from_date'));
        }

        if ($filters->has('to_date')) {
            $query->where('signed_at', '<=', $filters->get('to_date'));
        }

        return $query;
    }

    private function applySort($query, SortCriteria $sort)
    {
        return $query->orderBy($sort->field, $sort->direction);
    }
}
