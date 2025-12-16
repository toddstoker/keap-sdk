<?php
declare(strict_types=1);
namespace Toddstoker\KeapSdk\Requests\V1\Contacts;
use Saloon\Enums\Method;
use Saloon\Http\Request;
class GetContactEmails extends Request {
    protected Method $method = Method::GET;
    public function __construct(protected readonly int $contactId) {}
    public function resolveEndpoint(): string { return "/contacts/{$this->contactId}/emails"; }
}
