@extends('layouts.app')

@section('title', 'Editar hogar')

@section('content')
<section class="dashboard">
    <div class="page-header">
        <h1 class="dashboard-title">✏️ Editar hogar</h1>
        <a href="{{ route('homes.index') }}" class="btn">← Volver</a>
    </div>

    <form action="{{ route('homes.update', $home) }}" method="POST" class="form" style="display: flex; flex-direction: column; gap: 0.9rem;">
        @csrf
        @method('PUT')

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Nombre del hogar</label>
            <input type="text" name="name" value="{{ old('name', $home->name) }}" required>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.35rem;">
            <label style="margin: 0;">Dirección</label>
            <input type="text" name="address" value="{{ old('address', $home->address) }}">
        </div>

        <button class="btn" type="submit" style="align-self: flex-start; margin-top: 0.25rem;">Actualizar hogar</button>
    </form>
</section>
@endsection
