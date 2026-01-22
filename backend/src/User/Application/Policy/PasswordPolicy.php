<?php

namespace Dadinaks\User\Application\Policy;

final class PasswordPolicy
{
    public function isValid(string $password): bool
    {
        return (bool) preg_match(
            '/^(?=.*[a-z])
                (?=.*[A-Z])
                (?=.*\d)
                (?=.*[^A-Za-z0-9])
                .{8,}$/x',
            $password
        );
    }
}
