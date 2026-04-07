<?php
declare(strict_types=1);

namespace Modules\User\Presentation\Http\Action\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shared\Presentation\Http\JsonResponder;
use Modules\User\Domain\Repository\BankDetailRepositoryInterface;
use Modules\User\Domain\ValueObject\BankDetailId;
use Modules\User\Presentation\Http\Presenter\BankDetailPresenter;

// fix: it must be named UpdateBankDetailAction, UpdateBankDetailCommand 
final readonly class UpdateBankDetailCommand
{
    public function __construct(
        private BankDetailRepositoryInterface $bankRepository,
        private JsonResponder $responder,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $bankId = new BankDetailId($id);
        $bankDetail = $this->bankRepository->findById($bankId);

        if (!$bankDetail) {
            return $this->responder->notFound('Bank details not found');
        }

        // fix: use formRequest
        $request->validate([
            'account_owner' => ['required', 'string', 'max:255'],
            'bank_name' => ['required', 'string', 'max:255'],
            'iban' => ['required', 'string', 'max:34'],
        ]);

        $bankDetail->updateDetails(
            accountOwner: (string) $request->input('account_owner'),
            bankName: (string) $request->input('bank_name'),
            iban: (string) $request->input('iban'),
        );

        $this->bankRepository->save($bankDetail);

        return $this->responder->success(
            data: BankDetailPresenter::fromDomain($bankDetail),
            messageKey: 'bank.updated'
        );
    }
}
