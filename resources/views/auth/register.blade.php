<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
ControlWatt - Crear cuenta
</title>


@vite(['resources/css/app.css','resources/js/app.js'])


</head>


<body class="auth-page">


<div class="auth-card">


<div class="auth-header">

    <img src="{{ asset('images/logo.png') }}" class="auth-logo" alt="ControlWatt">

    <h1>
        ControlWatt
    </h1>

</div>


<div class="auth-subtitle">

Crea tu cuenta para administrar tu hogar inteligente

</div>



@if($errors->any())

<div class="error">

{{ $errors->first() }}

</div>

@endif




<form method="POST" action="/register">

@csrf



<label>
Nombre completo
</label>


<input

type="text"

name="name"

placeholder="Juan Pérez"

required>



<label>
Correo electrónico
</label>


<input

type="email"

name="email"

placeholder="correo@ejemplo.com"

required>



<label>
Contraseña
</label>


<input

type="password"

name="password"

placeholder="********"

required>



<label>
Confirmar contraseña
</label>


<input

type="password"

name="password_confirmation"

placeholder="********"

required>




<button class="auth-btn">

Crear cuenta

</button>



</form>




<div class="auth-links">


¿Ya tienes cuenta?


<a href="/login">

Iniciar sesión

</a>


</div>



</div>



</body>

</html>
