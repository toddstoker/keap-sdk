<?php

declare(strict_types=1);

namespace Toddstoker\KeapSdk\Enums\Contact;

/**
 * Source type for contact creation
 *
 * Indicates how the contact was originally created in Keap.
 *
 * @see https://developer.keap.com/docs/restv2/
 */
enum SourceType: string
{
    case SOURCE_TYPE_UNSPECIFIED = 'SOURCE_TYPE_UNSPECIFIED';
    case API = 'API';
    case APPOINTMENT = 'APPOINTMENT';
    case FORM_API_HOSTED = 'FORM_API_HOSTED';
    case FORM_API_INTERNAL = 'FORM_API_INTERNAL';
    case IMPORT = 'IMPORT';
    case INTERNAL_FORM = 'INTERNAL_FORM';
    case LANDING_PAGE = 'LANDING_PAGE';
    case MANUAL = 'MANUAL';
    case OTHER = 'OTHER';
    case UNKNOWN = 'UNKNOWN';
    case WEBFORM = 'WEBFORM';

    /**
     * Check if the contact was created via API
     */
    public function isApiCreated(): bool
    {
        return match ($this) {
            self::API,
            self::FORM_API_HOSTED,
            self::FORM_API_INTERNAL => true,
            default => false,
        };
    }

    /**
     * Check if the contact was created via a form
     */
    public function isFormCreated(): bool
    {
        return match ($this) {
            self::FORM_API_HOSTED,
            self::FORM_API_INTERNAL,
            self::INTERNAL_FORM,
            self::LANDING_PAGE,
            self::WEBFORM => true,
            default => false,
        };
    }

    /**
     * Get a human-readable label for the source type
     */
    public function label(): string
    {
        return match ($this) {
            self::SOURCE_TYPE_UNSPECIFIED => 'Unspecified',
            self::API => 'API',
            self::APPOINTMENT => 'Appointment',
            self::FORM_API_HOSTED => 'Hosted Form (API)',
            self::FORM_API_INTERNAL => 'Internal Form (API)',
            self::IMPORT => 'Import',
            self::INTERNAL_FORM => 'Internal Form',
            self::LANDING_PAGE => 'Landing Page',
            self::MANUAL => 'Manual Entry',
            self::OTHER => 'Other',
            self::UNKNOWN => 'Unknown',
            self::WEBFORM => 'Web Form',
        };
    }
}
