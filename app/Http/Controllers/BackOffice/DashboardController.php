<?php

namespace App\Http\Controllers\BackOffice;

use App\Http\Controllers\Controller;
use App\Models\MpTrip;
use App\Models\MpRoute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Backward compatibility: some old routes/logs call dashIndex and expect a view named dash_index.
    // Delegate to the current dashboardPage implementation to avoid "View [backoffice.dash_index] not found".
    public function dashIndex(Request $request)
    {
        return $this->dashboardPage($request);
    }

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

        // --- Bookings statistics (per month) from mp_reservations ---
        $months = [
            '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.',
            '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
        ];

        $year = Carbon::now()->year;

        $resvStats = DB::table('MP_RESERVATIONS')
            ->selectRaw("TO_CHAR(CREATED_AT,'MM') AS month,
                COUNT(*) AS total_bookings,
                SUM(SEATS_RESERVED) AS total_seats,
                SUM(CASE WHEN STATUS = 'cancelled' THEN 1 ELSE 0 END) AS cancels,
                SUM(CASE WHEN STATUS = 'completed' THEN 1 ELSE 0 END) AS checkin_success")
            ->whereRaw("EXTRACT(YEAR FROM CREATED_AT) = ?", [$year])
            ->groupByRaw("TO_CHAR(CREATED_AT,'MM')")
            ->orderByRaw("TO_CHAR(CREATED_AT,'MM')")
            ->get()
            ->keyBy('month');

        $bookings = collect();
        foreach ($months as $num => $name) {
            $r = $resvStats->get($num);
            $total_bookings = $r->total_bookings ?? 0;
            $total_seats = $r->total_seats ?? 0;
            $cancels = $r->cancels ?? 0;
            $checkin_success = $r->checkin_success ?? 0;
            $no_show = max(0, $total_bookings - $cancels - $checkin_success);

            $bookings->push([
                'month' => $name,
                'total_bookings' => (int)$total_bookings,
                'total_seats' => (int)$total_seats,
                'cancels' => (int)$cancels,
                'checkin_success' => (int)$checkin_success,
                'no_show' => (int)$no_show,
            ]);
        }

        $labels = $bookings->pluck('month');
        $checkin = $bookings->pluck('checkin_success');
        $cancels = $bookings->pluck('cancels');
        $noShow = $bookings->pluck('no_show');

        // Ensure datasets is defined for the view
        $datasets = collect();

        return view('backoffice.dashboard', compact('rows', 'types', 'grandTotal', 'start', 'end', 'bookings', 'labels', 'checkin', 'cancels', 'noShow', 'datasets'));
    }

    /**
     * User counts per month dashboard (separate method added, does not replace existing dashboardPage)
     */
    public function dashboardUsersPage()
    {
        $months = [
            '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.',
            '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
        ];

        // ดึงข้อมูลจาก MP_USERS
        $users = DB::table('MP_USERS')
            ->select('FIRST_NAME', 'LAST_NAME', 'CREATED_AT')
            ->get();

        // นับจำนวนผู้ใช้ตามเดือน จาก MP_EMPLOYEES
        $userCountsByMonth = DB::table('MP_EMPLOYEES')
            ->selectRaw("TO_CHAR(CREATED_AT, 'MM') as month, COUNT(*) as count")
            ->whereRaw("EXTRACT(YEAR FROM CREATED_AT) = ?", [now()->year])
            ->groupByRaw("TO_CHAR(CREATED_AT, 'MM')")
            ->orderByRaw("TO_CHAR(CREATED_AT, 'MM')")
            ->pluck('count', 'month')
            ->toArray();

        // map เดือนที่ไม่มีข้อมูล = 0
        $userCountsByMonthFull = [];
        foreach ($months as $num => $name) {
            $userCountsByMonthFull[$name] = $userCountsByMonth[$num] ?? 0;
        }

        return view('backoffice.dashboard', compact('users', 'userCountsByMonthFull'));
    }

    /**
     * Dashboard for routes page.
     */
    public function dashboardRoutesPage(Request $request)
    {
        // Removed functionality for 'จำนวนผู้ใช้บริการแยกตามเส้นทาง'
    }

    /**
     * รายงานสรุปยอดผู้ใช้แต่ละเส้นทางแบบรายวัน (รวมตามวันในสัปดาห์)
     * - Input: start_date, end_date (Y-m-d)
     * - Query: ดึงข้อมูลจาก mp_reservations ร่วมกับ mp_trips เพื่อหาจำนวนผู้ใช้บริการต่อวันต่อเส้นทาง
     * - Aggregate: map วันในสัปดาห์ (จันทร์..อาทิตย์) และรวมยอดหากมีวันซ้ำในช่วงที่เลือก
     */
    public function routeUsageDaily(Request $request)
    {
        // 1) รับช่วงวันที่ (ค่าเริ่มต้น = 7 วันล่าสุด)
        $end = $request->input('end_date');
        $start = $request->input('start_date');

        if (!$end) {
            $end = Carbon::now()->toDateString();
        }
        if (!$start) {
            $start = Carbon::parse($end)->subDays(6)->toDateString();
        }

        // Day of week labels (ISO-8601: 1=Mon .. 7=Sun)
        $dowLabels = [
            1 => 'จันทร์',
            2 => 'อังคาร',
            3 => 'พุธ',
            4 => 'พฤหัสบดี',
            5 => 'ศุกร์',
            6 => 'เสาร์',
            7 => 'อาทิตย์',
        ];

        // 2) Query ดึงยอดที่นั่งจอง (ถือเป็นจำนวนผู้ใช้บริการ) ต่อ route ต่อวัน
        //    ใช้สถานะ active หรือ completed เท่านั้น
        $rows = DB::table('MP_RESERVATIONS as r')
            ->join('MP_TRIPS as t', 'r.TRIP_ID', '=', 't.TRIP_ID')
            ->selectRaw('t.ROUTE_ID as route_id, t.SERVICE_DATE as service_date, SUM(r.SEATS_RESERVED) as seats')
            ->whereBetween('t.SERVICE_DATE', [$start, $end])
            ->where('r.STATUS', '=', 'completed')
            ->groupBy('t.ROUTE_ID', 't.SERVICE_DATE')
            ->orderBy('t.SERVICE_DATE')
            ->get();

        // 3) รวบรวมเส้นทางที่มีข้อมูล เพื่อนำไปดึงชื่อเส้นทาง
        $routeIds = $rows->pluck('route_id')->unique()->values();
        $routes = MpRoute::whereIn('route_id', $routeIds)->get(['route_id', 'name'])
            ->sortBy('name')
            ->values();

        // 4) เตรียมโครงสร้างสรุป: summary[1..7][route_id] = seats รวม
        $summary = [];
        for ($i = 1; $i <= 7; $i++) {
            $summary[$i] = [];
        }

        foreach ($rows as $r) {
            $dow = Carbon::parse($r->service_date)->dayOfWeekIso; // 1..7
            $summary[$dow][$r->route_id] = ($summary[$dow][$r->route_id] ?? 0) + (int) $r->seats;
        }

        // 5) แปลง labels และ datasets สำหรับ Chart.js
        $labels = array_values($dowLabels); // ['จันทร์', ..., 'อาทิตย์']

        // datasets: [{ label: route_name, data: [Mon..Sun] }]
        $datasets = [];
        foreach ($routes as $idx => $route) {
            $data = [];
            for ($i = 1; $i <= 7; $i++) {
                $data[] = (int) ($summary[$i][$route->route_id] ?? 0);
            }
            $datasets[] = [
                'label' => $route->name,
                'data' => $data,
            ];
        }

        // 6) เตรียมข้อมูลตาราง: แถวละวันในสัปดาห์ + รวมแถวสุดท้าย
        $table = [];
        $totalPerRoute = array_fill_keys($routes->pluck('route_id')->all(), 0);
        for ($i = 1; $i <= 7; $i++) {
            $row = ['day' => $dowLabels[$i]];
            $sumRow = 0;
            foreach ($routes as $route) {
                $val = (int) ($summary[$i][$route->route_id] ?? 0);
                $row[$route->route_id] = $val;
                $sumRow += $val;
                $totalPerRoute[$route->route_id] += $val;
            }
            $row['total'] = $sumRow;
            $table[] = $row;
        }
        $grandTotal = array_sum($totalPerRoute);

        return view('backoffice.dashboard', [
            'start' => $start,
            'end' => $end,
            'labels' => $labels,
            'routes' => $routes,
            'summary' => $summary,
            'datasets' => $datasets,
            'table' => $table,
            'totalPerRoute' => $totalPerRoute,
            'grandTotal' => $grandTotal,
        ]);
    }
}
