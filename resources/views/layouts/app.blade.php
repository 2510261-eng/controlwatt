<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>
@yield('title','ControlWatt')
</title>


@vite([
'resources/css/app.css',
'resources/js/app.js'
])


</head>


<body style="
min-height:100vh;
background-image:url('/images/smart-home.jpg');
background-size:cover;
background-position:center;
background-repeat:no-repeat;
">


<div class="app">


@include('components.sidebar')


<div class="main">


@include('components.navbar')


<main class="content">

@if (session('success'))
<div class="card" style="margin-bottom: 1rem; border-left: 4px solid #28a745;">
    <strong>{{ session('success') }}</strong>
</div>
@endif

@yield('content')

</main>


</div>


</div>


</body>

</html>
