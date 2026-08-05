<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
ControlWatt - Recuperar contraseña
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

Recupera el acceso a tu cuenta

</div>




@if(session('status'))

<p class="success">

{{ session('status') }}

</p>

@endif




@if($errors->any())

<p class="error">

{{ $errors->first() }}

</p>

@endif





<form method="POST" action="/forgot-password">

@csrf



<label>

Correo electrónico

</label>



<input

type="email"

name="email"

placeholder="correo@ejemplo.com"

required>




<button class="auth-btn">

Enviar enlace de recuperación

</button>



</form>





<div class="auth-links">


<a href="/login">

← Volver al inicio de sesión

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
