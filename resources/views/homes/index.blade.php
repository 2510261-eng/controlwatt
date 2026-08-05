@extends('layouts.app')


@section('title','Hogares')


@section('content')


<section class="dashboard">


<div class="page-header">


<h1 class="dashboard-title">

🏡 Hogares

</h1>

<a href="{{ route('homes.create') }}" class="btn" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25);">
Crear hogar
</a>

</div>




<p class="dashboard-description" style="margin: 0 0 1.25rem; line-height: 1.6;">
Administra las viviendas donde se monitorea el consumo eléctrico.
</p>




<div class="cards">


@forelse($homes as $home)


<div class="card" style="display: flex; flex-direction: column; gap: 0.8rem; padding: 1.1rem 1.1rem 1rem;">

<h3 style="margin: 0;">
🏠 {{ $home->name }}
</h3>

<div style="display: flex; flex-direction: column; gap: 0.35rem;">
<p style="margin: 0; line-height: 1.4;">
<strong>Código:</strong> #{{ $home->code ?? $home->id }}
</p>

<p style="margin: 0; line-height: 1.4;">
<strong>Dirección:</strong> {{ $home->address ?? 'Sin dirección' }}
</p>

<p style="margin: 0; line-height: 1.4;">
<strong>Propietario:</strong> {{ $home->owner?->name ?? 'Sin propietario' }}
</p>

<p style="margin: 0; line-height: 1.4;">
<strong>Usuarios:</strong> {{ $home->members->count() + ($home->owner ? 1 : 0) }}
</p>

<p style="margin: 0; line-height: 1.4;">
<strong>Dispositivos:</strong> {{ $home->devices->count() }}
</p>
</div>

<a href="{{ route('homes.show',$home) }}" class="btn" style="margin-top: 0.3rem; align-self: flex-start;">
Ver hogar
</a>

</div>


@empty


<div class="card">


<h3>
No hay hogares registrados
</h3>


<p>
Aún no perteneces a ningún hogar.
</p>


</div>


@endforelse



</div>


</section>


@endsection
