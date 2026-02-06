<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Property extends Model
{
    public const ID_COLUMN = 'id';
    public const PROPERTY_TYPE_COLUMN = 'property_type';
    public const FEATURES_COLUMN = 'features';
    public const PRICE_COLUMN = 'price';
    public const TAXES_COLUMN = 'taxes';
    public const INCOME_COLUMN = 'income';
    public const EXPENDITURE_COLUMN = 'expenditure';
    public const USER_ID_COLUMN = 'user_id';
    public const HASH_COLUMN = 'hash';
    public const CREATED_AT_COLUMN = 'created_at';
    public const UPDATED_AT_COLUMN = 'updated_at';

    protected $fillable = [
        self::PROPERTY_TYPE_COLUMN,
        self::FEATURES_COLUMN,
        self::PRICE_COLUMN,
        self::TAXES_COLUMN,
        self::INCOME_COLUMN,
        self::EXPENDITURE_COLUMN,
        self::USER_ID_COLUMN,
        self::HASH_COLUMN,
    ];

    protected function casts(): array
    {
        return [
            self::FEATURES_COLUMN => 'array',
            self::PRICE_COLUMN => 'decimal:2',
            self::TAXES_COLUMN => 'decimal:2',
            self::INCOME_COLUMN => 'decimal:2',
            self::EXPENDITURE_COLUMN => 'decimal:2',
        ];
    }

    public function getId(): int
    {
        return $this->getAttribute(self::ID_COLUMN);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
