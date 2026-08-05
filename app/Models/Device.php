<?php

namespace App\Models;

// Importa la clase base de Eloquent y la relación de pertenencia.
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    // Define los campos que pueden asignarse de forma masiva al crear o actualizar un dispositivo.
    protected $fillable = [
        'home_id',
        'user_id',
        'name',
        'type',
        'power',
        'hours_per_day',
    ];

    // Relación: un dispositivo pertenece a un hogar.
    public function home(): BelongsTo
    {
        // Indica que cada dispositivo está asociado a un hogar específico.
        return $this->belongsTo(Home::class);
    }

    // Relación: un dispositivo pertenece a un usuario.
    public function user(): BelongsTo
    {
        // Indica que cada dispositivo está asociado a un usuario específico.
        return $this->belongsTo(User::class);
    }

    public function consumptions()
    {
        return $this->hasMany(Consumption::class);
    }
}
