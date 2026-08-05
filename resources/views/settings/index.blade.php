@extends('layouts.app')


@section('title','Configuración')


@section('content')


<section class="dashboard">

<div class="settings-shell">

<div class="settings-header">

<h1 class="dashboard-title">
⚙ Configuración
</h1>


<p class="dashboard-description">
Personaliza tu experiencia en ControlWatt.
</p>

</div>




<!-- APARIENCIA -->

<div class="card settings-card">


<h3>
🌙 Apariencia
</h3>


<p>
Cambiar tema
</p>



<button id="theme-toggle" class="btn">

🌙 Cambiar modo

</button>



</div>





<!-- TAMAÑO LETRA -->

<div class="card settings-card">


<h3>
🔠 Tamaño de letra
</h3>



<div class="font-buttons">


<button class="btn size-btn" data-size="small">

Pequeña

</button>



<button class="btn size-btn" data-size="normal">

Normal

</button>



<button class="btn size-btn" data-size="large">

Grande

</button>


</div>



</div>






<!-- CERRAR SESIÓN -->

<div class="card settings-card">


<h3>
🚪 Cuenta
</h3>



<form method="POST" action="{{ route('logout') }}">

@csrf


<button class="btn logout">

Cerrar sesión

</button>


</form>


</div>

</section>


@endsection
