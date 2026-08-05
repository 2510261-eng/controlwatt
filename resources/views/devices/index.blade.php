@extends('layouts.app')


@section('title','Dispositivos')


@section('content')


<section class="dashboard">


<div class="page-header">

<h1 class="dashboard-title">

⚡ Dispositivos

</h1>


<a href="{{ route('devices.create') }}" class="btn">

+ Agregar dispositivo

</a>


</div>


<div class="cards">

@if($devices->isEmpty())

<div class="card">

<h3>
No hay dispositivos registrados
</h3>

<p>
Agrega uno desde el botón de arriba.
</p>

</div>

@else

@foreach($devices as $device)

<div class="card">

<h3>
{{ $device->name }}
</h3>

<p>
<strong>Hogar:</strong> {{ $device->home?->name ?? 'Sin hogar' }}
</p>

<p>
{{ number_format($device->power, 2, ',', '.') }} W
</p>

<span>
Consumo estimado: {{ number_format($device->power * 24 / 1000, 2, ',', '.') }} kWh/día
</span>

<div style="margin-top: 1rem; display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;">
<a href="{{ route('devices.show', $device) }}" class="btn" style="display: inline-block;">Ver</a>
@can('update', $device)
<a href="{{ route('devices.edit', $device) }}" class="btn" style="display: inline-block;">Editar</a>
@endcan
@can('delete', $device)
<form action="{{ route('devices.destroy', $device) }}" method="POST" onsubmit="return confirm('¿Eliminar este dispositivo?')" style="margin: 0;">
@csrf
@method('DELETE')
<button class="btn" type="submit" style="display: inline-block;">Eliminar</button>
</form>
@endcan
</div>

</div>

@endforeach

@endif

</div>


</section>


@endsection
