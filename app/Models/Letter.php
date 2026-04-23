<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SENT = 'sent';
    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'letter_no',
        'date',
        'subject',
        'sender',
        'recipient',
        'category',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function statuses(): array
    {
        return [self::STATUS_DRAFT, self::STATUS_SENT, self::STATUS_ARCHIVED];
    }
}
