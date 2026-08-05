<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
ControlWatt - Login
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

Monitorea tu consumo eléctrico

</div>



@if($errors->any())

<p>

{{ $errors->first() }}

</p>

@endif



<form method="POST" action="/login">

@csrf


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



<button class="auth-btn">

Iniciar sesión

</button>


</form>



<div class="auth-links">


<a href="/forgot-password">

¿Olvidaste tu contraseña?

</a>


<br><br>


¿No tienes cuenta?

<a href="/register">

Crear cuenta

</a>


</div>



</div>


</body>

</html>
