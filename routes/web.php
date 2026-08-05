<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Auth\RegisterController;
use App\Models\User;

use App\Http\Controllers\Homes\HomeController;
use App\Http\Controllers\Devices\DeviceController;
use App\Http\Controllers\Dashboard\DashboardController;


/*
|--------------------------------------------------------------------------
| PÁGINA PRINCIPAL
|--------------------------------------------------------------------------
*/

Route::get('/', function(){

    return redirect()->route('dashboard');

});



/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get('/login', function(){

    return view('auth.login');

})->name('login');



Route::post('/login', function(){

    $credentials = request()->validate([

        'email'=>'required|email',
        'password'=>'required'

    ]);


    if(Auth::attempt($credentials)){


        request()->session()->regenerate();


        return redirect()->route('dashboard');

    }


    return back()->withErrors([

        'email'=>'Credenciales incorrectas.'

    ]);


});




/*
|--------------------------------------------------------------------------
| REGISTRO
|--------------------------------------------------------------------------
*/


Route::get('/register', function(){

    return view('auth.register');

})->name('register');



Route::post('/register', function(){


    $data = request()->validate([

        'name'=>'required',

        'email'=>'required|email|unique:users',

        'password'=>'required|min:8'

    ]);



    $user = User::create([

        'name'=>$data['name'],

        'email'=>$data['email'],

        'password'=>Hash::make($data['password'])

    ]);



    Auth::login($user);



    return redirect()->route('dashboard');


});




/*
|--------------------------------------------------------------------------
| RECUPERAR PASSWORD
|--------------------------------------------------------------------------
*/


Route::get('/forgot-password', function(){

    return view('auth.forgot-password');

})->name('password.request');



Route::post('/forgot-password', function(){

    return back()->with(

        'status',

        'Si el correo existe recibirás instrucciones.'

    );

})->name('password.email');







/*
|--------------------------------------------------------------------------
| SISTEMA CONTROLWATT
|--------------------------------------------------------------------------
*/


Route::middleware('auth')->group(function(){



    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */


    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/homes', [DashboardController::class, 'createHome'])->name('dashboard.homes.create');
    Route::post('/dashboard/join-home', [DashboardController::class, 'joinHome'])->name('dashboard.homes.join');





    /*
    |--------------------------------------------------------------------------
    | HOGARES
    |--------------------------------------------------------------------------
    */


    Route::resource(

        'homes',

        HomeController::class

    );

    Route::post('/homes/{home}/members', [HomeController::class, 'addMember'])->name('homes.add-member');
    Route::post('/homes/{home}/members/{user}/role', [HomeController::class, 'updateMemberRole'])->name('homes.members.role');
    Route::delete('/homes/{home}/members/{user}', [HomeController::class, 'removeMember'])->name('homes.members.remove');
    Route::post('/homes/{home}/leave', [HomeController::class, 'leaveHome'])->name('homes.leave');





    /*
    |--------------------------------------------------------------------------
    | DISPOSITIVOS
    |--------------------------------------------------------------------------
    */


    Route::resource(

        'devices',

        DeviceController::class

    );






    /*
    |--------------------------------------------------------------------------
    | SCANNER
    |--------------------------------------------------------------------------
    */


    Route::get('/scanner', function(){

        return view('scanner.index');

    })->name('scanner.index');







    /*
    |--------------------------------------------------------------------------
    | REPORTES
    |--------------------------------------------------------------------------
    */


Route::get('/reports', [App\Http\Controllers\Reports\ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/download', [App\Http\Controllers\Reports\ReportController::class, 'download'])->name('reports.download');

Route::post('/scanner/analyze', [App\Http\Controllers\Scanner\ScannerController::class, 'analyze'])->name('scanner.analyze');








    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */


    Route::get('/profile', function(){

        return view('profile.index');

    })->name('profile.index');








    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN
    |--------------------------------------------------------------------------
    */


    Route::get('/settings', function(){

        return view('settings.index');

    })->name('settings.index');








    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */


    Route::post('/logout', function(){


        Auth::logout();


        request()->session()->invalidate();


        request()->session()->regenerateToken();



        return redirect('/login');


    })->name('logout');



});
