<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->input('mes', date('Y-m'));

        $user = auth()->user();

        $homes = Home::where('user_id', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with(['devices' => function ($query) use ($mes) {
                $query->whereHas('consumptions', function ($consumptionQuery) use ($mes) {
                    $consumptionQuery->where('month', $mes);
                });
            }])
            ->get();

        $reportRows = [];
        $totalKwh = 0;
        $totalCost = 0;

        foreach ($homes as $home) {
            foreach ($home->devices as $device) {
                $consumption = $device->consumptions()->where('month', $mes)->first();
                $usage = $consumption?->kwh ?? ($device->hours_per_day ?? 0) * 0.5;
                $cost = $usage * 2.5;

                $reportRows[] = [
                    'home' => $home->name,
                    'device' => $device->name,
                    'consumption' => round($usage, 2),
                    'cost' => round($cost, 2),
                ];

                $totalKwh += $usage;
                $totalCost += $cost;
            }
        }

        return view('reports.index', compact('mes', 'reportRows', 'totalKwh', 'totalCost'));
    }

    public function download(Request $request)
    {
        $mes = $request->input('mes', date('Y-m'));
        $user = auth()->user();

        $homes = Home::where('user_id', $user->id)
            ->orWhereHas('members', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })
            ->with('devices')
            ->get();

        $rows = [];

        foreach ($homes as $home) {
            foreach ($home->devices as $device) {
                $consumption = $device->consumptions()->where('month', $mes)->first();
                $usage = $consumption?->kwh ?? ($device->hours_per_day ?? 0) * 0.5;
                $rows[] = [
                    'home' => $home->name,
                    'device' => $device->name,
                    'consumption' => round($usage, 2),
                    'cost' => round($usage * 2.5, 2),
                ];
            }
        }

        $content = "Reporte mensual\nMes: {$mes}\n\n";
        $content .= "Hogar,Dispositivo,Consumo (kWh),Costo (USD)\n";

        foreach ($rows as $row) {
            $content .= $row['home'] . ',' . $row['device'] . ',' . $row['consumption'] . ',' . $row['cost'] . "\n";
        }

        $filename = 'reporte-' . $mes . '.csv';
        $path = 'reports/' . $filename;
        Storage::disk('local')->put($path, $content);

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
        ]);
    }
}
