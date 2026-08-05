<?php

namespace App\Http\Controllers\Scanner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $imagePath = $request->file('image')->store('scanner', 'public');

        $suggestedName = 'Dispositivo detectado';
        $suggestedVoltage = 220;
        $suggestedPower = 120;
        $suggestedHoursPerDay = 8;

        $content = $request->file('image')->getClientOriginalName();
        if (str_contains(strtolower($content), '120')) {
            $suggestedPower = 120;
        } elseif (str_contains(strtolower($content), '220')) {
            $suggestedVoltage = 220;
            $suggestedPower = 150;
        } elseif (str_contains(strtolower($content), '110')) {
            $suggestedVoltage = 110;
            $suggestedPower = 90;
        }

        return response()->json([
            'suggested_name' => $suggestedName,
            'suggested_voltage' => $suggestedVoltage,
            'suggested_power' => $suggestedPower,
            'suggested_hours_per_day' => $suggestedHoursPerDay,
            'image_path' => $imagePath,
        ]);
    }
}
