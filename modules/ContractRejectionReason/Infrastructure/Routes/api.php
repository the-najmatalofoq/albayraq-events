<?php
// modules/ContractRejectionReason/Infrastructure/Routes/api.php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\ContractRejectionReason\Presentation\Http\Action\ListContractRejectionReasonsAction;

Route::get('/', ListContractRejectionReasonsAction::class);
