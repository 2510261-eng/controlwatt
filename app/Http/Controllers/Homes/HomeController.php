<?php

namespace App\Http\Controllers\Homes;

// Importa la clase base del controlador y los tipos necesarios para trabajar con hogares.
use App\Http\Controllers\Controller;
use App\Models\Home;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    // Muestra la lista de hogares registrados.
    public function index(): View
    {
        // Obtiene los hogares del usuario autenticado, incluyendo los que fue invitado a unirse.
        $homes = Home::with(['owner', 'members', 'devices'])
            ->where('user_id', auth()->id())
            ->orWhereHas('members', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();

        // Carga la vista de listado y pasa los hogares a la plantilla.
        return view('homes.index', compact('homes'));
    }

    // Muestra el formulario para crear un nuevo hogar.
    public function create(): View
    {
        // Devuelve la vista del formulario de creación.
        return view('homes.create');
    }

    // Guarda un nuevo hogar en la base de datos.
    public function store(Request $request): RedirectResponse
    {
        // Valida que los datos enviados cumplan con las reglas definidas.
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'address' => ['nullable','string','max:255'],
        ]);

        $homeCode = strtoupper(substr(md5(uniqid()), 0, 4));

        // Crea un nuevo hogar con los datos validados y asigna el usuario autenticado.
        Home::create([
            'code' => $homeCode,
            'name' => $data['name'],
            'address' => $data['address'] ?? null,
            'user_id' => auth()->id(),
        ]);

        // Redirige a la lista de hogares con un mensaje de éxito.
        return redirect()
            ->route('homes.index')
            ->with('success','Hogar agregado correctamente.');
    }

    // Muestra los detalles de un hogar específico.
    public function show(Home $home): View
    {
        // Carga la vista de detalle del hogar.
        return view('homes.show', compact('home'));
    }

    // Muestra el formulario para editar un hogar existente.
    public function edit(Home $home): View
    {
        if (! $home->isAdmin(auth()->user())) {
            abort(403);
        }

        // Devuelve la vista de edición del hogar seleccionado.
        return view('homes.edit', compact('home'));
    }

    // Actualiza los datos de un hogar ya existente.
    public function update(Request $request, Home $home): RedirectResponse
    {
        if (! $home->isAdmin(auth()->user())) {
            abort(403);
        }

        // Valida los nuevos datos enviados por el formulario.
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'address' => ['nullable','string','max:255'],
        ]);

        // Actualiza el hogar con los valores validados.
        $home->update($data);

        // Redirige de nuevo a la lista con un mensaje de éxito.
        return redirect()
            ->route('homes.index')
            ->with('success','Hogar actualizado correctamente.');
    }

    public function addMember(Request $request, Home $home): RedirectResponse
    {
        if (! $home->isAdmin(auth()->user())) {
            abort(403);
        }

        $memberId = trim($request->input('member_id'));

        if ($memberId === '') {
            return back()->withErrors(['member_id' => 'Debes ingresar un ID de usuario.']);
        }

        $user = \App\Models\User::where('code', $memberId)
            ->orWhere('id', $memberId)
            ->orWhere('email', $memberId)
            ->first();

        if (! $user) {
            return back()->withErrors(['member_id' => 'No se encontró un usuario con ese ID o correo.']);
        }

        if ($home->members()->where('users.id', $user->id)->exists() || $home->owner->is($user)) {
            return back()->withErrors(['member_id' => 'Ese usuario ya pertenece al hogar.']);
        }

        $home->members()->attach($user->id, ['role' => 'member']);

        return back()->with('success', 'Miembro agregado correctamente.');
    }

    public function updateMemberRole(Request $request, Home $home, User $user): RedirectResponse
    {
        if (! $home->isAdmin(auth()->user()) || $home->owner->is($user)) {
            abort(403);
        }

        $role = $request->input('role', 'member');

        if (! in_array($role, ['member', 'admin'], true)) {
            return back()->withErrors(['role' => 'Rol inválido.']);
        }

        $home->members()->syncWithoutDetaching([$user->id => ['role' => $role]]);

        return back()->with('success', 'Rol actualizado correctamente.');
    }

    public function removeMember(Home $home, User $user): RedirectResponse
    {
        if (! $home->isAdmin(auth()->user()) || $home->owner->is($user)) {
            abort(403);
        }

        $home->members()->detach($user->id);

        return back()->with('success', 'Miembro eliminado correctamente.');
    }

    public function leaveHome(Home $home): RedirectResponse
    {
        $user = auth()->user();

        if ($home->owner->is($user)) {
            return back()->withErrors(['home' => 'El propietario no puede desvincularse del hogar.']);
        }

        if (! $home->members()->where('users.id', $user->id)->exists()) {
            return back()->withErrors(['home' => 'No perteneces a este hogar.']);
        }

        $home->members()->detach($user->id);

        return redirect()->route('homes.index')->with('success', 'Te desvinculaste del hogar correctamente.');
    }

    // Elimina un hogar de la base de datos.
    public function destroy(Home $home): RedirectResponse
    {
        if (! $home->isAdmin(auth()->user())) {
            abort(403);
        }

        $home->devices()->delete();
        $home->members()->detach();
        $home->delete();

        // Redirige a la lista de hogares con un mensaje de éxito.
        return redirect()
            ->route('homes.index')
            ->with('success','Hogar eliminado correctamente.');
    }
}
