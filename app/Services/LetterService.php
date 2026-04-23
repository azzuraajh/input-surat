<?php

namespace App\Services;

use App\Models\Letter;

class LetterService
{
    public function generateLetterNo(?\DateTimeInterface $date = null): string
    {
        $date = $date ? now()->setDate($date->format('Y'), $date->format('m'), $date->format('d')) : now();

        $prefix = sprintf('IS/%s/%s/', $date->format('Y'), $date->format('m'));

        $last = Letter::withTrashed()
            ->where('letter_no', 'like', $prefix.'%')
            ->orderByDesc('letter_no')
            ->value('letter_no');

        $nextNumber = 1;

        if ($last) {
            $parts = explode('/', $last);
            $seq = (int) end($parts);
            $nextNumber = $seq + 1;
        }

        do {
            $candidate = $prefix.str_pad((string) $nextNumber, 4, '0', STR_PAD_LEFT);
            $exists = Letter::withTrashed()->where('letter_no', $candidate)->exists();
            $nextNumber++;
        } while ($exists);

        return $candidate;
    }
}
