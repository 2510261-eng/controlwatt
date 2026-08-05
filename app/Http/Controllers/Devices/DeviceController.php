<?php

namespace App\Http\Controllers\Devices;

// Importa la clase base del controlador, el modelo de dispositivo y la solicitud HTTP.
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeviceController extends Controller
{
    /**
     * Muestra la lista de dispositivos disponibles.
     */
    public function index(): View
    {
        // Obtiene solo los dispositivos relacionados con los hogares del usuario autenticado.
        $devices = Device::with('home')
            ->whereHas('home', function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhereHas('members', function ($memberQuery) {
                        $memberQuery->where('users.id', auth()->id());
                    });
            })
            ->latest()
            ->get();

        return view('devices.index', compact('devices'));
    }

    /**
     * Muestra el formulario para crear un nuevo dispositivo.
     */
    public function create(): View
    {
        $homes = Home::where('user_id', auth()->id())
            ->orWhereHas('members', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();

        return view('devices.create', compact('homes'));
    }

    /**
     * Guarda un dispositivo nuevo en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        // Valida los datos recibidos desde el formulario.
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'power' => ['required', 'numeric', 'min:0'],
            'hours_per_day' => ['nullable', 'numeric', 'min:0'],
            'home_id' => ['nullable', 'exists:homes,id'],
        ]);

        // Busca o crea un hogar para asociar el dispositivo.
        $home = Home::find($data['home_id'] ?? null);

        if (! $home) {
            $home = Home::where('user_id', auth()->id())->first();
        }

        if (! $home) {
            $home = Home::create([
                'code' => strtoupper(substr(md5(uniqid()), 0, 4)),
                'name' => 'Hogar principal',
                'address' => null,
                'user_id' => auth()->id(),
            ]);
        }

        // Guarda el dispositivo con los datos validados.
        Device::create([
            'home_id' => $home->id,
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'type' => 'general',
            'power' => $data['power'],
            'hours_per_day' => $data['hours_per_day'] ?? 0,
        ]);

        return redirect()
            ->route('devices.index')
            ->with('success', 'Dispositivo agregado correctamente.');
    }

    /**
     * Muestra los detalles de un dispositivo específico.
     */
    public function show(string $id): View
    {
        $device = Device::with('home')->findOrFail($id);

        return view('devices.show', compact('device'));
    }

    /**
     * Muestra el formulario para editar un dispositivo existente.
     */
    public function edit(string $id): View
    {
        $device = Device::findOrFail($id);

        if (! auth()->user()->can('update', $device)) {
            abort(403);
        }

        $homes = Home::where('user_id', auth()->id())
            ->orWhereHas('members', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();

        return view('devices.edit', compact('device', 'homes'));
    }

    /**
     * Actualiza los datos de un dispositivo existente.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $device = Device::findOrFail($id);

        if (! auth()->user()->can('update', $device)) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'power' => ['required', 'numeric', 'min:0'],
            'hours_per_day' => ['nullable', 'numeric', 'min:0'],
            'home_id' => ['nullable', 'exists:homes,id'],
        ]);

        $device->update([
            'home_id' => $data['home_id'] ?? $device->home_id,
            'name' => $data['name'],
            'power' => $data['power'],
            'hours_per_day' => $data['hours_per_day'] ?? $device->hours_per_day,
        ]);

        return redirect()
            ->route('devices.index')
            ->with('success', 'Dispositivo actualizado correctamente.');
    }

    /**
     * Elimina un dispositivo de la base de datos.
     */
    public function destroy(string $id): RedirectResponse
    {
        $device = Device::findOrFail($id);

        if (! auth()->user()->can('delete', $device)) {
            abort(403);
        }

        $device->delete();

        return redirect()
            ->route('devices.index')
            ->with('success', 'Dispositivo eliminado correctamente.');
    }
}
