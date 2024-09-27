<?php

namespace App\Http\Controllers\Apps;

use App\Shared\Apps\Controller\BaseController;
use App\Services\Apps\EmailValidation\Actions\EmailValidationInfo;
use Illuminate\Http\Request;

class EmailValidationController extends BaseController
{
    protected function getConfigData(Request $request): array
    {
        return [
            'apiKey' => $request->header('apiKey')
        ];
    }

    public function validateEmail(Request $request)
    {
        return $this->executeAction($request, new EmailValidationInfo());
    }
}
