<?php

declare(strict_types=1);

namespace midorikocak\nanoauth;

use RuntimeException;

class UnauthorizedException extends RuntimeException
{
    public function __construct($message = null, $code = 401)
    {
        if (empty($message)) {
            $message = 'Unauthorized';
        }
        parent::__construct($message, $code);
    }
}
