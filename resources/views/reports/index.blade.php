@extends('layouts.app')


@section('title','Reportes')


@section('content')


<section class="dashboard">


<div class="page-header">


<div>

<h1 class="dashboard-title">

📄 Reportes

</h1>


<p class="dashboard-description">

Consulta tu consumo eléctrico por mes.

</p>

</div>



<a href="#" class="btn">

⬇ Descargar PDF

</a>


</div>




<div class="card">


<h3>

Seleccionar mes

</h3>



<form method="GET" action="/reports">


<input
type="month"
name="mes"
value="{{ $mes }}"
>



<button class="btn">

Ver reporte

</button>


</form>


</div>




<div class="cards">



<div class="card">


<h3>

⚡ Consumo del mes

</h3>


<p class="card-value">

350 kWh

</p>


</div>




<div class="card">


<h3>

💰 Costo estimado

</h3>


<p class="card-value">

$850

</p>


</div>




<div class="card">


<h3>

📈 Comparación

</h3>


<p class="card-value">

-15%

</p>


</div>



</div>





<div class="card">


<h3>

Mes seleccionado:

{{ $mes }}

</h3>


<br>


<table class="table">


<tr>

<th>
Dispositivo
</th>

<th>
Consumo
</th>

<th>
Costo
</th>


</tr>



<tr>

<td>
Refrigerador
</td>

<td>
80 kWh
</td>

<td>
$200
</td>


</tr>



<tr>

<td>
Lavadora
</td>

<td>
20 kWh
</td>

<td>
$50
</td>


</tr>



</table>



</div>



</section>


@endsection
