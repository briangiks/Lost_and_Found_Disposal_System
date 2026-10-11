<?php

if (! function_exists('lnf_split_emails')) {
    function lnf_split_emails(?string $value): array
    {
        if (blank($value)) {
            return [];
        }

        return collect(explode(',', $value))
            ->map(fn (string $email) => trim($email))
            ->filter()
            ->values()
            ->all();
    }
}