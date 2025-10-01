<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\MpTrip;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show dashboard report using mp_trips data.
     * We aggregate trips by vehicle type and license plate for the current month.
     */
    public function dashboardPage(Request $request)
    {
        $start = Carbon::now()->startOfMonth()->toDateString();
        $end = Carbon::now()->endOfMonth()->toDateString();

        // load trips within current month with related vehicle and type
        $trips = MpTrip::with(['vehicle.type'])
            ->whereBetween('service_date', [$start, $end])
            ->get();

        // aggregate: [ typeName => [ 'vehicles' => [plate => count], 'total' => int ] ]
        $types = [];
        foreach ($trips as $trip) {
            $vehicle = $trip->vehicle;
            $typeName = 'ไม่ระบุประเภท';
            $plate = 'ไม่ระบุ';

            if ($vehicle) {
                $plate = $vehicle->license_plate ?? $plate;
                if ($vehicle->type && $vehicle->type->name) {
                    $typeName = $vehicle->type->name;
                }
            }

            if (!isset($types[$typeName])) {
                $types[$typeName] = ['vehicles' => [], 'total' => 0];
            }

            if (!isset($types[$typeName]['vehicles'][$plate])) {
                $types[$typeName]['vehicles'][$plate] = 0;
            }

            $types[$typeName]['vehicles'][$plate]++;
            $types[$typeName]['total']++;
        }

        // prepare flat rows for the table
        $rows = [];
        foreach ($types as $typeName => $data) {
            foreach ($data['vehicles'] as $plate => $count) {
                $rows[] = [
                    'type' => $typeName,
                    'plate' => $plate,
                    'count' => $count,
                ];
            }
        }

        $grandTotal = array_sum(array_column($types, 'total'));

        return view('backoffice.dashboard', compact('rows', 'types', 'grandTotal', 'start', 'end'));
    }
}
