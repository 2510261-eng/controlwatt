@extends('layouts.app')


@section('title','Dashboard')


@section('content')


<section class="dashboard">


<h1 class="dashboard-title">

Bienvenido a ControlWatt

</h1>


<p class="dashboard-description">

Monitorea y administra el consumo eléctrico de tu hogar.

</p>



<div class="cards">

<div class="card">

<div class="card-title">
⚡ Consumo actual
</div>

<div class="card-value">
{{ number_format($estimatedPower, 0) }} W
</div>

</div>

<div class="card">

<div class="card-title">
🏠 Hogares
</div>

<div class="card-value">
{{ $homesCount }}
</div>

</div>

<div class="card">

<div class="card-title">
🔌 Dispositivos
</div>

<div class="card-value">
{{ $devicesCount }}
</div>

</div>

</div>

<p class="dashboard-description">
Actualmente tienes {{ $usersCount }} usuarios vinculados a tus hogares y {{ $devicesCount }} dispositivos registrados.
</p>

<div class="cards" style="margin-top: 1rem;">
    <div class="card" style="display: flex; flex-direction: column; gap: 0.6rem;">
        <div class="card-title">👤 Tu código de usuario</div>
        <div class="card-value">{{ $user->code ?? str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</div>
        <p style="margin: 0; line-height: 1.4;">ID numérico: #{{ str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="card">
        <h3>🤝 Unirse a un hogar</h3>
        <form action="{{ route('dashboard.homes.join') }}" method="POST" class="form">
            @csrf
            <label>ID del hogar</label>
            <input type="text" name="home_id" placeholder="Ej. A2C4" required style="color: #111; background-color: #fff;">
            <button class="btn" type="submit">Unirse</button>
        </form>
    </div>
</div>

@if($homes->isNotEmpty())
<div style="margin-top: 1.5rem;">
    <h2 class="dashboard-title" style="font-size: 1.4rem; margin-bottom: 1rem;">🏠 Mis hogares</h2>
    <div class="cards">
        @foreach($homes as $home)
        <div class="card" style="display: flex; flex-direction: column; gap: 0.7rem;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 0.75rem; flex-wrap: wrap;">
                <h3 style="margin: 0;">🏠 {{ $home->name }}</h3>
                <a href="{{ route('homes.show', $home) }}" class="btn">Ver hogar</a>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                <p style="margin: 0; line-height: 1.4;"><strong>Código:</strong> {{ $home->code ?? str_pad((string) $home->id, 4, '0', STR_PAD_LEFT) }}</p>
                <p style="margin: 0; line-height: 1.4;"><strong>Propietario:</strong> {{ $home->owner?->name ?? 'Sin propietario' }}</p>
                <p style="margin: 0; line-height: 1.4;"><strong>Miembros:</strong> {{ $home->members->count() + 1 }}</p>
                <p style="margin: 0; line-height: 1.4;"><strong>Dispositivos:</strong> {{ $home->devices->count() }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

</section>


@endsection
