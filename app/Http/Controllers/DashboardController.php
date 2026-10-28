<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\QosMeasurement;
use App\Services\QosClassificationService;
use App\Services\QosRcaService;
use App\Services\QosDssService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(
        Request $request,
        QosClassificationService $classification,
        QosRcaService $rcaService,
        QosDssService $dssService
    ) {
        /*
        |--------------------------------------------------------------------------
        | Ambil seluruh sekolah
        |--------------------------------------------------------------------------
        */
        $schools = School::orderBy('nama_sekolah')->get();

        /*
        |--------------------------------------------------------------------------
        | Tentukan sekolah yang dipilih
        |--------------------------------------------------------------------------
        */
        $selectedSchoolId = $request->get('school_id');

        if ($selectedSchoolId) {
            $selectedSchool = School::find($selectedSchoolId);
        } else {
            $selectedSchool = $schools->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Variabel hasil
        |--------------------------------------------------------------------------
        */
        $latestMeasurement = null;
        $latestClassification = null;
        $latestRca = null;
        $latestDss = null;

        /*
        |--------------------------------------------------------------------------
        | Ambil pengukuran terbaru dari database
        |--------------------------------------------------------------------------
        | Mengambil pengukuran terbaru untuk sekolah yang dipilih,
        | baik hasil Tes QoS manual maupun scheduler otomatis.
        |--------------------------------------------------------------------------
        */
        if ($selectedSchool) {
            $latestMeasurement = QosMeasurement::where(
                'school_id',
                $selectedSchool->id
            )
                ->latest('measured_at')
                ->latest('id')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | TIPHON, RCA, dan DSS
        |--------------------------------------------------------------------------
        */
        if ($latestMeasurement) {

            /*
            |--------------------------------------------------------------------------
            | TIPHON
            |--------------------------------------------------------------------------
            */
            $latestClassification = $classification->analyze(
                $latestMeasurement->throughput,
                $selectedSchool->bandwidth,
                $latestMeasurement->delay,
                $latestMeasurement->jitter,
                $latestMeasurement->packet_loss
            );

            /*
            |--------------------------------------------------------------------------
            | RCA dan DSS
            |--------------------------------------------------------------------------
            | RCA dijalankan untuk kondisi SEDANG atau BURUK.
            |--------------------------------------------------------------------------
            */
            if (
                isset($latestClassification['condition']) &&
                in_array(
                    $latestClassification['condition'],
                    ['SEDANG', 'BURUK']
                )
            ) {
                /*
                |--------------------------------------------------------------------------
                | Deteksi ping timeout
                |--------------------------------------------------------------------------
                */
                $pingTimeout = false;

                if ($latestMeasurement->raw_output) {
                    $rawOutput = json_decode(
                        $latestMeasurement->raw_output,
                        true
                    );

                    if (
                        is_array($rawOutput) &&
                        isset($rawOutput['target_ping']['success'])
                    ) {
                        $pingTimeout =
                            $rawOutput['target_ping']['success'] === false;
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | RCA
                |--------------------------------------------------------------------------
                */
                $latestRca = $rcaService->analyze(
                    $latestClassification,
                    $latestMeasurement->packet_loss,
                    $pingTimeout
                );

                /*
                |--------------------------------------------------------------------------
                | DSS
                |--------------------------------------------------------------------------
                */
                $latestDss = $dssService->recommend(
                    $latestRca
                );

            } else {

                /*
                |--------------------------------------------------------------------------
                | Jika kondisi BAIK
                |--------------------------------------------------------------------------
                */
                $latestRca = [
                    'required' => false,
                    'condition' => 'BAIK',
                    'summary' =>
                        'Kualitas jaringan berada pada kondisi baik.',
                    'causes' => [],
                ];

                $latestDss = [
                    'required' => false,
                    'summary' =>
                        'Tidak diperlukan tindakan khusus karena kualitas jaringan berada pada kondisi baik.',
                    'recommendations' => [],
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Data grafik 
         /*
        |--------------------------------------------------------------------------
        | Grafik: hanya satu pengukuran terbaru
        |--------------------------------------------------------------------------
        */
$chartMeasurements = collect();

if ($selectedSchool) {
    $chartMeasurement = QosMeasurement::where(
        'school_id',
        $selectedSchool->id
    )
        ->where('success', true)
        ->whereNotNull('throughput')
        ->whereNotNull('delay')
        ->whereNotNull('jitter')
        ->whereNotNull('packet_loss')
        ->whereNotNull('ping')
        ->latest('measured_at')
        ->latest('id')
        ->first();

    if ($chartMeasurement) {
        $chartMeasurements = collect([$chartMeasurement]);
    }
}

        /*
        |--------------------------------------------------------------------------
        | Status seluruh sekolah
        |--------------------------------------------------------------------------
        | Setiap sekolah menggunakan pengukuran terbarunya dari database.
        |--------------------------------------------------------------------------
        */
        $schoolStatuses = $schools->map(
            function ($school) use (
                $classification,
                $rcaService,
                $dssService
            ) {
                /*
                |--------------------------------------------------------------------------
                | Ambil pengukuran terbaru untuk sekolah ini
                |--------------------------------------------------------------------------
                */
                $measurement = QosMeasurement::where(
                    'school_id',
                    $school->id
                )
                    ->latest('measured_at')
                    ->latest('id')
                    ->first();

                $qosClassification = null;
                $rca = null;
                $dss = null;

                /*
                |--------------------------------------------------------------------------
                | Analisis jika tersedia pengukuran
                |--------------------------------------------------------------------------
                */
                if ($measurement) {

                    /*
                    |--------------------------------------------------------------------------
                    | TIPHON
                    |--------------------------------------------------------------------------
                    */
                    $qosClassification = $classification->analyze(
                        $measurement->throughput,
                        $school->bandwidth,
                        $measurement->delay,
                        $measurement->jitter,
                        $measurement->packet_loss
                    );

                    /*
                    |--------------------------------------------------------------------------
                    | RCA dan DSS untuk kondisi SEDANG atau BURUK
                    |--------------------------------------------------------------------------
                    */
                    if (
                        isset($qosClassification['condition']) &&
                        in_array(
                            $qosClassification['condition'],
                            ['SEDANG', 'BURUK']
                        )
                    ) {
                        /*
                        |--------------------------------------------------------------------------
                        | Deteksi ping timeout
                        |--------------------------------------------------------------------------
                        */
                        $pingTimeout = false;

                        if ($measurement->raw_output) {
                            $rawOutput = json_decode(
                                $measurement->raw_output,
                                true
                            );

                            if (
                                is_array($rawOutput) &&
                                isset($rawOutput['target_ping']['success'])
                            ) {
                                $pingTimeout =
                                    $rawOutput['target_ping']['success'] === false;
                            }
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | RCA
                        |--------------------------------------------------------------------------
                        */
                        $rca = $rcaService->analyze(
                            $qosClassification,
                            $measurement->packet_loss,
                            $pingTimeout
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | DSS
                        |--------------------------------------------------------------------------
                        */
                        $dss = $dssService->recommend($rca);

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | Jika kondisi BAIK
                        |--------------------------------------------------------------------------
                        */
                        $rca = [
                            'required' => false,
                            'condition' => 'BAIK',
                            'summary' =>
                                'Kualitas jaringan berada pada kondisi baik.',
                            'causes' => [],
                        ];

                        $dss = [
                            'required' => false,
                            'summary' =>
                                'Tidak diperlukan tindakan khusus karena kualitas jaringan berada pada kondisi baik.',
                            'recommendations' => [],
                        ];
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Data status sekolah
                |--------------------------------------------------------------------------
                */
                return [
                    'school' => $school,
                    'measurement' => $measurement,
                    'classification' => $qosClassification,
                    'rca' => $rca,
                    'dss' => $dss,
                ];
            }
        );

        /*
        |--------------------------------------------------------------------------
        | Kirim data ke dashboard
        |--------------------------------------------------------------------------
        */
        return view('dashboard', [
            'schools' => $schools,
            'selectedSchool' => $selectedSchool,
            'latestMeasurement' => $latestMeasurement,
            'latestClassification' => $latestClassification,
            'latestRca' => $latestRca,
            'latestDss' => $latestDss,
            'chartMeasurements' => $chartMeasurements,
            'schoolStatuses' => $schoolStatuses,
        ]);
    }
}
