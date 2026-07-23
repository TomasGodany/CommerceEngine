<?php

namespace App\Services;

use App\Enums\DocumentType;
use App\Models\Document;
use Illuminate\Support\Facades\DB;

class DocumentNumberGenerator
{
    /**
     * Generate the next document number for the given type in the current year.
     */
    public function generate(DocumentType $type): string
    {
        return DB::transaction(function () use ($type) {
            $year = now()->year;
            $prefix = $type->prefix();

            $lastNumber = Document::where('type', $type->value)
                ->where('document_number', 'like', "{$prefix}-{$year}-%")
                ->lockForUpdate()
                ->orderByDesc('document_number')
                ->value('document_number');

            $sequence = 1;

            if ($lastNumber) {
                $lastSequence = (int) substr($lastNumber, strrpos($lastNumber, '-') + 1);
                $sequence = $lastSequence + 1;
            }

            return sprintf('%s-%d-%04d', $prefix, $year, $sequence);
        });
    }
}
