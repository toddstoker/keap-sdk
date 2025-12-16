<?php

namespace Toddstoker\KeapSdk\Resources;

use Toddstoker\KeapSdk\Keap;

interface Resource
{
    public function __construct(
        Keap $connector
    );
}
