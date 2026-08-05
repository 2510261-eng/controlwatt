@extends('layouts.app')


@section('title','Agregar dispositivo')


@section('content')


<section class="dashboard">


<h1 class="dashboard-title">

Nuevo dispositivo

</h1>



<form action="{{ route('devices.store') }}" method="POST" class="form" style="display: flex; flex-direction: column; gap: 0.9rem;" data-device-form>

    @csrf

    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
        <label style="margin: 0;">Nombre del dispositivo</label>
        <input type="text" name="name" required>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
        <label style="margin: 0;">Hogar</label>
        <select name="home_id" style="color: #111; background-color: #fff;">
            @foreach($homes as $home)
                <option value="{{ $home->id }}" style="color: #111;">{{ $home->name }} (#{{ $home->id }})</option>
            @endforeach
        </select>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
        <label style="margin: 0;">Potencia (Watts)</label>
        <input type="number" name="power" step="0.01" min="0" required>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.35rem;">
        <label style="margin: 0;">Horas de uso diario</label>
        <input type="number" name="hours_per_day" step="0.1" min="0">
    </div>

    <button class="btn" type="submit" style="align-self: flex-start; margin-top: 0.25rem;">Guardar</button>

</form>


</section>


@endsection
