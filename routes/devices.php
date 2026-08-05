<?php

namespace App\Http\Controllers\Devices;

use App\Http\Controllers\Controller;

class DeviceController extends Controller
{


    public function index()
    {

        return view('devices.index');

    }


}
