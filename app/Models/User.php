<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// Importa las clases necesarias para que el modelo funcione como usuario autenticable.
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\Auth\RegisterController;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    // Habilita la fábrica de modelos para generar usuarios de prueba.
    use HasFactory, Notifiable;

    /**
     * Los campos que pueden asignarse de forma masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
    ];

    /**
     * Los campos que deben ocultarse al serializar el modelo.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Define cómo deben convertirse ciertos atributos al trabajar con ellos.
     *
     * @return array<string, string>
     */
    protected static function booted(): void
    {
        static::creating(function (self $user): void {
            if (empty($user->code)) {
                $user->code = strtoupper(substr(md5(uniqid()), 0, 4));
            }
        });
    }

    protected function casts(): array
    {
        // Convierte la fecha de verificación de correo a un objeto DateTime.
        // Y aplica el hash automáticamente a la contraseña.
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
