<?php

namespace App\Models;

// Importa las clases de relaciones de Eloquent para definir cómo se conectan los modelos.
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Home extends Model
{
    // Define qué campos pueden llenarse al crear o actualizar un hogar.
    protected $fillable = ['name', 'address', 'user_id', 'code'];

    // Relación: un hogar pertenece a un propietario.
    public function owner(): BelongsTo
    {
        // Indica que el hogar está asociado con un usuario mediante el campo user_id.
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación: un hogar puede tener varios miembros.
    public function members(): BelongsToMany
    {
        // Relaciona el hogar con muchos usuarios a través de la tabla intermedia home_user.
        return $this->belongsToMany(User::class, 'home_user')
            ->withPivot(['role'])
            ->withTimestamps();
    }

    public function isAdmin(User $user): bool
    {
        if ($this->user_id === $user->id) {
            return true;
        }

        if (! \Illuminate\Support\Facades\Schema::hasColumn('home_user', 'role')) {
            return false;
        }

        return $this->members()->where('users.id', $user->id)
            ->wherePivot('role', 'admin')
            ->exists();
    }

    // Relación: un hogar puede tener muchos dispositivos.
    public function devices(): HasMany
    {
        // Indica que cada hogar puede tener múltiples dispositivos.
        return $this->hasMany(Device::class);
    }
}
