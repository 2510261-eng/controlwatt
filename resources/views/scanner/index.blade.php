@extends('layouts.app')

@section('title','Escanear')

@section('content')
<section class="dashboard">
    <div class="page-header">
        <h1 class="dashboard-title">📷 Escanear dispositivo</h1>
        <a href="{{ route('devices.create') }}" class="btn">➕ Agregar dispositivo</a>
    </div>

    <p class="dashboard-description">Toma una foto de la etiqueta energética del electrodoméstico para obtener una sugerencia de voltaje, potencia y nombre.</p>

    <div class="card">
        <form method="POST" action="{{ route('scanner.analyze') }}" enctype="multipart/form-data" data-ocr-form>
            @csrf
            <div style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label>Seleccionar imagen</label>
                <input type="file" name="image" accept="image/*" capture="camera" required>
            </div>
            <button class="btn" type="submit" style="margin-top: 0.75rem;">Analizar imagen</button>
        </form>

        <div data-ocr-result style="margin-top: 1rem;"></div>
    </div>
</section>
@endsection
