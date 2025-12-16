<?php
declare(strict_types=1);
namespace Toddstoker\KeapSdk\Requests\V1\Contacts;
use Saloon\Enums\Method;
use Saloon\Http\Request;
class GetContactModel extends Request {
    protected Method $method = Method::GET;
    public function resolveEndpoint(): string { return "/contacts/model"; }
}
