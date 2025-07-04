<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_type_id',
        'institution_id',
        'name',
        'description',
        'color',
        'initial_balance',
        'include_in_dashboard',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        // CORREÇÃO: Restaurar o conteúdo desta função
        return [
            'initial_balance' => 'decimal:2',
            'include_in_dashboard' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
