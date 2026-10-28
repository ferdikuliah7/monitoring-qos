<?php

namespace App\Http\Controllers;

use App\Models\QosMeasurement;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = QosMeasurement::with('school')
            ->orderByDesc('measured_at');

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        $measurements = $query->paginate(15)->withQueryString();

        $schools = \App\Models\School::orderBy('nama_sekolah')->get();

        $notifications = $measurements->getCollection()
            ->map(function ($measurement) {
                $values = [
                    $measurement->throughput,
                    $measurement->delay,
                    $measurement->jitter,
                    $measurement->packet_loss,
                ];

                if (!$measurement->success) {
                    $level = 'danger';
                    $title = 'Pengukuran QoS gagal';
                    $message = 'Pengukuran jaringan tidak berhasil. Periksa koneksi dan konfigurasi target.';
                } elseif (in_array(null, $values, true)) {
                    $level = 'warning';
                    $title = 'Data QoS belum lengkap';
                    $message = 'Sebagian parameter QoS belum tersedia pada hasil pengukuran ini.';
                } else {
                    $level = 'info';
                    $title = 'Pengukuran QoS tersimpan';
                    $message = 'Hasil pengukuran QoS berhasil tersimpan.';
                }

                return [
                    'school' => $measurement->school?->nama_sekolah ?? 'Sekolah tidak diketahui',
                    'level' => $level,
                    'title' => $title,
                    'message' => $message,
                    'measured_at' => $measurement->measured_at,
                ];
            });

     return view('notification.index', compact(
    'measurements',
    'notifications',
    'schools'
));
    }
}

