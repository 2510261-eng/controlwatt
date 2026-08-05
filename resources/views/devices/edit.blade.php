@extends('layouts.app')

@section('title','Editar dispositivo')

@section('content')
<section class="dashboard">
    <div class="page-header">
        <h1 class="dashboard-title">✏️ Editar dispositivo</h1>
        <a href="{{ route('devices.index') }}" class="btn">← Volver</a>
    </div>

    <form action="{{ route('devices.update', $device) }}" method="POST" class="form" style="display: flex; flex-direction: column; gap: 0.9rem;">
        @csrf
        @method('PUT')

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Nombre del dispositivo</label>
            <input type="text" name="name" value="{{ old('name', $device->name) }}" required>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Hogar</label>
            <select name="home_id" style="color: #111; background-color: #fff;">
                @foreach($homes as $home)
                    <option value="{{ $home->id }}" {{ $device->home_id == $home->id ? 'selected' : '' }} style="color: #111;">{{ $home->name }} (#{{ $home->id }})</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Potencia (Watts)</label>
            <input type="number" name="power" step="0.01" min="0" value="{{ old('power', $device->power) }}" required>
        </div>

        <button class="btn" type="submit">Guardar cambios</button>
    </form>
</section>
@endsection
