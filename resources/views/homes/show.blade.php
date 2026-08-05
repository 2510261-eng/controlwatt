@extends('layouts.app')

@section('title', $home->name)

@section('content')
<section class="dashboard">
    <div class="page-header">
        <h1 class="dashboard-title">🏡 {{ $home->name }}</h1>
        <a href="{{ route('homes.index') }}" class="btn">← Volver</a>
    </div>

    <p class="dashboard-description">{{ $home->address ?? 'Sin dirección registrada' }}</p>

    @php
        $homeDisplayId = str_pad((string) $home->id, 4, '0', STR_PAD_LEFT);
    @endphp

    <div style="display: flex; flex-direction: column; gap: 0.35rem; margin: 0.5rem 0 1rem;">
        <p style="margin: 0; line-height: 1.4;"><strong>Código del hogar:</strong> #{{ $home->code ?? $homeDisplayId }}</p>
        <p style="margin: 0; line-height: 1.4;"><strong>ID:</strong> #{{ $homeDisplayId }}</p>
    </div>

    @if($home->isAdmin(auth()->user()))
        <form action="{{ route('homes.destroy', $home) }}" method="POST" style="margin: 0.75rem 0 1rem;" onsubmit="return confirm('¿Deseas disolver este hogar y eliminar todos sus datos?')">
            @csrf
            @method('DELETE')
            <button class="btn" type="submit">🗑️ Disolver hogar</button>
        </form>
    @endif

    <div class="cards">
        <div class="card">
            <h3>👤 Propietario</h3>
            <p>{{ $home->owner?->name ?? 'Sin propietario' }}</p>
        </div>

        <div class="card">
            <h3>👥 Usuarios del hogar</h3>
            @php $homeUsers = collect([$home->owner])->merge($home->members); @endphp
            <div style="display: grid; gap: 0.75rem; margin-top: 0.5rem;">
                @forelse ($homeUsers as $member)
                    @if($member)
                        <div style="padding: 0.75rem; border: 1px solid rgba(255,255,255,0.16); border-radius: 0.75rem; background: rgba(255,255,255,0.04); display: flex; flex-direction: column; gap: 0.35rem;">
                            <p style="margin: 0; font-weight: 600;">{{ $member->name }}</p>
                            <p style="margin: 0; opacity: 0.8;">ID: #{{ str_pad((string) $member->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p style="margin: 0; opacity: 0.8;">Código: {{ $member->code ?? str_pad((string) $member->id, 4, '0', STR_PAD_LEFT) }}</p>
                            @php $memberRole = $home->members()->where('users.id', $member->id)->first()?->pivot?->role ?? 'owner'; @endphp
                            <p style="margin: 0.25rem 0 0; font-size: 0.95rem;">Rol: {{ $memberRole === 'admin' ? 'Administrador' : ($memberRole === 'owner' ? 'Propietario' : 'Miembro') }}</p>
                            @php $memberDevices = $home->devices->filter(fn($device) => $device->user_id == $member->id); @endphp
                            @if($memberDevices->isNotEmpty())
                                <p style="margin: 0.25rem 0 0; font-size: 0.95rem;">Dispositivos: {{ $memberDevices->pluck('name')->join(', ') }}</p>
                            @else
                                <p style="margin: 0.25rem 0 0; font-size: 0.95rem;">Dispositivos: sin dispositivos</p>
                            @endif

                            @if($home->isAdmin(auth()->user()) && ! $home->owner->is($member))
                                <form action="{{ route('homes.members.role', [$home, $member]) }}" method="POST" class="form" style="margin-top: 0.5rem;">
                                    @csrf
                                    <select name="role" style="color: #111; background-color: #fff;">
                                        <option value="member" {{ $memberRole === 'member' ? 'selected' : '' }}>Miembro</option>
                                        <option value="admin" {{ $memberRole === 'admin' ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                    <button class="btn" type="submit" style="margin-top: 0.25rem;">Guardar rol</button>
                                </form>

                                <form action="{{ route('homes.members.remove', [$home, $member]) }}" method="POST" class="form" style="margin-top: 0.5rem;" onsubmit="return confirm('¿Deseas eliminar a este miembro del hogar?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn" type="submit" style="margin-top: 0.25rem;">Eliminar miembro</button>
                                </form>
                            @endif
                        </div>
                    @endif
                @empty
                    <p>No hay usuarios adicionales.</p>
                @endforelse
            </div>

            @if($home->isAdmin(auth()->user()))
                <form action="{{ route('homes.add-member', $home) }}" method="POST" class="form" style="margin-top: 1rem;">
                    @csrf
                    <label>Agregar miembro por ID de usuario</label>
                    <input type="text" name="member_id" placeholder="Ej. usr-001" required style="color: #111; background-color: #fff;">
                    <button class="btn" type="submit">Agregar miembro</button>
                </form>
            @endif

            @if($home->user_id !== auth()->id())
                <form action="{{ route('homes.leave', $home) }}" method="POST" class="form" style="margin-top: 1rem;" onsubmit="return confirm('¿Deseas desvincularte de este hogar?')">
                    @csrf
                    <button class="btn" type="submit">Desvincularme del hogar</button>
                </form>
            @endif
        </div>

        <div class="card">
            <h3>🔌 Dispositivos del hogar</h3>
            <div style="display: grid; gap: 0.75rem; margin-top: 0.5rem;">
                @forelse ($home->devices as $device)
                    @php
                        $deviceDisplayId = str_pad((string) $device->id, 4, '0', STR_PAD_LEFT);
                    @endphp
                    <div style="padding: 0.75rem; border: 1px solid rgba(255,255,255,0.16); border-radius: 0.75rem; background: rgba(255,255,255,0.92);">
                        <h4 style="margin: 0 0 0.35rem; color: #0f172a;">{{ $device->name }}</h4>
                        <p style="margin: 0.2rem 0; color: #334155;">ID: #{{ $deviceDisplayId }}</p>
                        <p style="margin: 0.2rem 0; color: #334155;">⚡ {{ $device->power }} W</p>
                        <p style="margin: 0.2rem 0; color: #334155;">Hogar: {{ $device->home?->name ?? 'Sin hogar' }}</p>
                        @can('update', $device)
                            <div style="margin-top: 0.75rem;">
                                <a href="{{ route('devices.edit', $device) }}" class="btn">Editar</a>
                            </div>
                        @endcan
                        @can('delete', $device)
                            <form action="{{ route('devices.destroy', $device) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Deseas eliminar este dispositivo?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn" type="submit">Eliminar</button>
                            </form>
                        @endcan
                    </div>
                @empty
                    <p>No hay dispositivos en este hogar todavía.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
