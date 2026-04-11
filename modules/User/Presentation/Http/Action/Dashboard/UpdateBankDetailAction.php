<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\ValueObject\BankDetailId;
use Modules\User\Presentation\Http\Presenter\BankDetailPresenter;
use Modules\User\Presentation\Http\Request\Dashboard\UpdateBankDetailRequest;

final readonly class UpdateBankDetailAction
{
    public function __construct(
        private BankDetailRepositoryInterface $bankRepository,
        private JsonResponder $responder,
    ) {}

    public function __invoke(UpdateBankDetailRequest $request, string $id): JsonResponse
    {
        $bankId = new BankDetailId($id);
        $bankDetail = $this->bankRepository->findById($bankId);

        if (!$bankDetail) {
            return $this->responder->notFound('Bank details not found');
        }

        $bankDetail->updateDetails(
            accountOwner: (string) $request->input('account_owner'),
            bankName: (string) $request->input('bank_name'),
            iban: (string) $request->input('iban'),
        );

        $this->bankRepository->save($bankDetail);

        return $this->responder->success(
            data: BankDetailPresenter::fromDomain($bankDetail),
            messageKey: 'messages.updated'
        );
    }
}
