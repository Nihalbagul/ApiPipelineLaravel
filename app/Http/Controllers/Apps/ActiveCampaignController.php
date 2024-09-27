<?php

namespace App\Http\Controllers\Apps;


use App\Shared\Apps\Controller\BaseController;
use App\Services\Apps\ActiveCampaign\Actions\EnableDisableEventTracking;


use Illuminate\Http\Request;
use App\Services\Apps\ActiveCampaign\Actions\GetContacts;
use App\Services\Apps\ActiveCampaign\Actions\GetMessages;
use App\Services\Apps\ActiveCampaign\Actions\GetTags;
use App\Services\Apps\ActiveCampaign\Actions\GetUsers;
use App\Services\Apps\ActiveCampaign\Actions\ListAllAccounts;
use App\Services\Apps\ActiveCampaign\Actions\CreateAccount;
use App\Services\Apps\ActiveCampaign\Actions\CreateContact;
use App\Services\Apps\ActiveCampaign\Actions\DeleteAccount;
use App\Services\Apps\ActiveCampaign\Actions\ActiveCampaignDeleteAccount;
class ActiveCampaignController extends BaseController
{
    protected function getConfigData(Request $request): array
    {
        return [
            'apiToken' => $request->header('Api-Token'),
        ];
    }

    public function getContacts(Request $request)
    {
        return $this->executeAction($request, new GetContacts());
    }

    public function getMessages(Request $request)
    {
        return $this->executeAction($request, new GetMessages());
    }

    public function getTags(Request $request)
    {
        return $this->executeAction($request, new GetTags());
    }

    public function getUsers(Request $request)
    {
        return $this->executeAction($request, new GetUsers());
    }

    public function listAllAccounts(Request $request)
    {
        return $this->executeAction($request, new ListAllAccounts());
    }
    // public function createAccount(Request $request)
    // {
    //     return $this->executeAction($request, new CreateAccount());
    // }
    public function createContact(Request $request)
    {
        return $this->executeAction($request, new CreateContact());
    }
    public function eventTracking(Request $request)
    {
        return $this->executeAction($request, new EnableDisableEventTracking());
    }
    public function deleteAccount(Request $request)
    {
        return $this->executeAction($request, new ActiveCampaignDeleteAccount());
    }
}
