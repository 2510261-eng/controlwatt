@extends('layouts.app')

@section('title', 'Crear hogar')

@section('content')
<section class="dashboard">
    <div class="page-header">
        <h1 class="dashboard-title">🏡 Crear hogar</h1>
        <a href="{{ route('homes.index') }}" class="btn">← Volver</a>
    </div>

    <form action="{{ route('homes.store') }}" method="POST" class="form" style="display: flex; flex-direction: column; gap: 0.9rem;">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Nombre del hogar</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Dirección</label>
            <input type="text" name="address" value="{{ old('address') }}">
        </div>

        <button class="btn" type="submit" style="align-self: flex-start; margin-top: 0.25rem;">Guardar hogar</button>
    </form>
</section>
@endsection
