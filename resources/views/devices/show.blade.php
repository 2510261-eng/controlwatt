@extends('layouts.app')

@section('title', $device->name)

@section('content')
<section class="dashboard">
    <div class="page-header">
        <h1 class="dashboard-title">🔌 {{ $device->name }}</h1>
        <a href="{{ route('devices.index') }}" class="btn">← Volver</a>
    </div>

    <div class="card">
        <p><strong>Hogar:</strong> {{ $device->home?->name ?? 'Sin hogar' }}</p>
        <p><strong>Potencia:</strong> {{ $device->power }} W</p>
        <p><strong>Creado:</strong> {{ $device->created_at->format('d/m/Y H:i') }}</p>
    </div>
</section>
@endsection
