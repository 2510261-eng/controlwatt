<?php

namespace App\Http\Controllers\Dashboard;

// Importa la clase base de todos los controladores de Laravel.
use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    // Muestra la vista principal del panel de control con datos reales del usuario.
    public function index()
    {
        $user = auth()->user();
        $userId = $user?->id;

        $homes = Home::where('user_id', $userId)
            ->orWhereHas('members', function ($query) use ($userId) {
                $query->where('users.id', $userId);
            })
            ->with(['devices', 'members', 'owner'])
            ->get();

        $homesCount = $homes->count();
        $devicesCount = $homes->sum(fn ($home) => $home->devices->count());
        $estimatedPower = $homes->sum(fn ($home) => $home->devices->sum('power'));
        $usersCount = $homes->sum(fn ($home) => 1 + $home->members->count());

        return view('dashboard.index', compact('homes', 'homesCount', 'devicesCount', 'estimatedPower', 'usersCount', 'user'));
    }

    public function createHome(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $homeCode = strtoupper(substr(md5(uniqid()), 0, 4));

        $home = Home::create([
            'code' => $homeCode,
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Hogar creado correctamente. Código: ' . $home->code);
    }

    public function joinHome(Request $request): RedirectResponse
    {
        $homeId = trim($request->input('home_id'));

        if ($homeId === '') {
            return back()->withErrors(['home_id' => 'Debes ingresar un ID de hogar.']);
        }

        $home = Home::where('code', $homeId)->first();

        if (! $home) {
            return back()->withErrors(['home_id' => 'No se encontró un hogar con ese ID.']);
        }

        $user = auth()->user();

        if ($home->members()->where('users.id', $user->id)->exists() || $home->owner->is($user)) {
            return back()->withErrors(['home_id' => 'Ya perteneces a este hogar.']);
        }

        $home->members()->attach($user->id);

        return back()->with('success', 'Te uniste al hogar correctamente.');
    }
}
