<?php

namespace App\Http\Controllers;

use App\Models\DriverAmt;
use App\Models\AttendanceAmt;
use Illuminate\Http\Request;

class RFIDController extends Controller
{
    public function form()
    {
        $today = AttendanceAmt::with('driverAmt')
            ->whereDate('scanned_at', now()->toDateString())
            ->latest()
            ->get();

        return view('rfid.scan', compact('today'));
    }

    public function scan(Request $request)
    {
        $request->validate(['rfid' => 'required|string']);

        $rfid = trim($request->input('rfid'));
        $driver = DriverAmt::where('rfid_code', $rfid)->first();

        if (!$driver) {
            // Untuk AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'RFID tidak terdaftar.'
                ]);
            }
            return back()->with('error', 'RFID tidak terdaftar.');
        }

        // Cegah input ganda dalam 1 menit
        $recent = AttendanceAmt::where('driver_amt_id', $driver->id)
            ->where('scanned_at', '>=', now()->subMinute())
            ->exists();

        if ($recent) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $driver->name . ' sudah absen dalam 1 menit terakhir.'
                ]);
            }
            return back()->with('error', $driver->name . ' sudah absen.');
        }

        // Simpan absensi
        AttendanceAmt::create([
            'driver_amt_id' => $driver->id,
            'scanned_at' => now(),
            'status' => 'hadir',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil untuk ' . $driver->name,
                'data' => [
                    'driver_name' => $driver->name,
                    'driver_position' => $driver->position,
                    'scanned_at' => now()->format('H:i:s')
                ]
            ]);
        }

        return back()->with('success', 'Absensi berhasil untuk ' . $driver->name);
    }

    // Method untuk mendapatkan data terbaru via AJAX
    public function getData()
    {
        $today = AttendanceAmt::with('driverAmt')
            ->whereDate('scanned_at', now()->toDateString())
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $today->map(function($item, $index) {
                return [
                    'no' => $index + 1,
                    'name' => $item->driverAmt->name,
                    'position' => $item->driverAmt->position,
                    'time' => \Carbon\Carbon::parse($item->scanned_at)->format('H:i:s')
                ];
            }),
            'total' => $today->count()
        ]);
    }
}