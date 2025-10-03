@extends('layouts.backoffice')

@php
// Remove mock data: expect controller to pass real booking variables.
// Provide empty collections as safe defaults to avoid undefined variable errors.
$routes = $routes ?? collect();
$labels = $labels ?? collect();
$summary = $summary ?? collect();
// Safe defaults for other sections if this page is rendered from another action
$rows = $rows ?? [];
$types = $types ?? [];
$grandTotal = $grandTotal ?? 0;
$bookings = $bookings ?? collect();
$checkin = $checkin ?? collect();
$cancels = $cancels ?? collect();
$noShow = $noShow ?? collect();
$datasets = $datasets ?? collect();
$start = $start ?? \Carbon\Carbon::now()->startOfMonth()->toDateString();
$end = $end ?? \Carbon\Carbon::now()->endOfMonth()->toDateString();

$activeTab = $activeTab ?? request('tab', 'trips');
$period = request('period', 'week');
$periodLabel = match($period) {
    'day' => 'วัน',
    'week' => 'สัปดาห์',
    'month' => 'เดือน',
    'year' => 'ปี',
    default => 'ช่วงเวลา',
};
@endphp

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex gap-2 mb-3">
        <a href="{{ route('dashboard', ['tab' => 'trips']) }}" class="btn {{ $activeTab==='trips' ? 'btn-primary' : 'btn-outline-primary' }}">รายงานเที่ยว</a>
        <a href="{{ route('dashboard', ['tab' => 'bookings']) }}" class="btn {{ $activeTab==='bookings' ? 'btn-primary' : 'btn-outline-primary' }}">รายงานการจอง</a>
        <a href="{{ url('backoffice/reports/route-usage-daily') }}?tab=route-usage&start_date={{ $start }}&end_date={{ $end }}" class="btn {{ $activeTab==='route-usage' ? 'btn-primary' : 'btn-outline-primary' }}">สรุปผู้ใช้ตามเส้นทาง</a>
    </div>
    @if($activeTab==='trips')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm report-card-top">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">รายงานจำนวนเที่ยว ({{ \Carbon\Carbon::parse($start)->translatedFormat('M Y') }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle table-report">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>ประเภทรถ</th>
                                    <th>ทะเบียน</th>
                                    <th class="text-end">จำนวนเที่ยว</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $r)
                                    <tr>
                                        <td>{{ $r['type'] }}</td>
                                        <td>{{ $r['plate'] }}</td>
                                        <td class="text-end">{{ $r['count'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">ไม่มีข้อมูลในช่วงนี้</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 report-summary">
                        <h6>สรุป</h6>
                        <table class="table table-sm table-borderless w-50">
                            <tbody>
                                @foreach($types as $type => $data)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td class="text-end">{{ $data['total'] }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold summary-row total">
                                    <td>รวมทั้งหมด</td>
                                    <td class="text-end">{{ $grandTotal }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if($activeTab==='bookings')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm report-card-bottom">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">รายงานการจอง</h5>
                </div>
                <div class="card-body">

                    {{-- ตารางข้อมูล --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle table-report">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>เดือน</th>
                                    <th>จำนวนการจอง</th>
                                    <th>จำนวนที่นั่งถูกจอง</th>
                                    <th>การยกเลิก</th>
                                    <th>Check-in สำเร็จ</th>
                                    <th>No Show</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $row)
                                    <tr>
                                        <td class="text-center">{{ $row['month'] }}</td>
                                        <td class="text-end">{{ $row['total_bookings'] }}</td>
                                        <td class="text-end">{{ $row['total_seats'] }}</td>
                                        <td class="text-end">{{ $row['cancels'] }}</td>
                                        <td class="text-end">{{ $row['checkin_success'] }}</td>
                                        <td class="text-end">{{ $row['no_show'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">ไม่มีข้อมูล</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- กราฟ --}}
                    <h6 class="mt-4">สถิติรายเดือน</h6>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card p-2 chart-card">
                                <canvas id="chartCheckin" height="120"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card p-2 chart-card">
                                <canvas id="chartCancels" height="120"></canvas>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card p-2 chart-card">
                                <canvas id="chartNoShow" height="120"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endif
{{-- ====== รายงานสรุปยอดผู้ใช้แต่ละเส้นทางรายวัน (ย้าย/รวมจาก report_route_usage.blade) ====== --}}
@if($activeTab==='route-usage')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">รายงานสรุปยอดผู้ใช้แต่ละเส้นทางรายวัน</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3 mb-3" method="get" action="{{ url('backoffice/reports/route-usage-daily') }}">
                        <input type="hidden" name="tab" value="route-usage">
                        <div class="col-md-3">
                            <label class="form-label">วันที่เริ่ม</label>
                            <input type="date" class="form-control" name="start_date" value="{{ $start }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">วันที่สิ้นสุด</label>
                            <input type="date" class="form-control" name="end_date" value="{{ $end }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary" type="submit">แสดงรายงาน</button>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="text-muted">ช่วงที่เลือก: {{ \Carbon\Carbon::parse($start)->translatedFormat('j M Y') }} - {{ \Carbon\Carbon::parse($end)->translatedFormat('j M Y') }}</div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th style="min-width:120px">วัน</th>
                                    @foreach($routes as $route)
                                        <th>{{ $route->name }}</th>
                                    @endforeach
                                    <th>รวม/วัน</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($table)
                                @foreach($table as $row)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $row['day'] }}</td>
                                        @foreach($routes as $route)
                                            <td class="text-end">{{ number_format($row[$route->route_id] ?? 0) }}</td>
                                        @endforeach
                                        <td class="text-end fw-semibold">{{ number_format($row['total']) }}</td>
                                    </tr>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="{{ max(2, ($routes->count() ?? 0) + 2) }}" class="text-center text-muted">ไม่มีข้อมูลรายงานในช่วงนี้</td>
                                    </tr>
                                @endisset
                            </tbody>
                            @isset($totalPerRoute)
                            <tfoot>
                                <tr class="fw-bold">
                                    <td class="text-center">รวมทั้งหมด</td>
                                    @foreach($routes as $route)
                                        <td class="text-end">{{ number_format($totalPerRoute[$route->route_id] ?? 0) }}</td>
                                    @endforeach
                                    <td class="text-end">{{ number_format($grandTotal ?? 0) }}</td>
                                </tr>
                            </tfoot>
                            @endisset
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6>กราฟจำนวนผู้ใช้บริการแยกตามเส้นทาง (ตามวันในสัปดาห์)</h6>
                        <div class="card p-3">
                            <canvas id="routeUsageChart" height="150"></canvas>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function changePeriod(period) {
        const url = new URL(window.location.href);
        url.searchParams.set('period', period);
        window.location.href = url.toString();
    }

    const chartLabels = @json($labels);

    const datasets = [
        @foreach($routes as $route)
        {
            label: @json($route->name),
            data: [
                @foreach($labels as $i => $label)
                    {{ $summary[$i][$route->route_id] ?? 0 }},
                @endforeach
            ],
            backgroundColor: '{{ ['#b5e0ff','#e0b5ff','#ffd6b5','#b5ffd6','#ffb5b5'][$loop->index % 5] }}'
        },
        @endforeach
    ];

    const linewayCanvas = document.getElementById('linewayChart');
    if (linewayCanvas) {
        const ctx = linewayCanvas.getContext('2d');
        window.linewayChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: datasets
            },  
            options: {
                responsive: true,
                plugins: { legend: { position: 'top' } },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 500,
                        ticks: {
                            stepSize: 50
                        }
                    }
                }
            }
        });
    }
</script>

@if($activeTab==='bookings')
<script>
    const labels = {!! json_encode($labels) !!};
    const elCheckin = document.getElementById('chartCheckin');
    const elCancels = document.getElementById('chartCancels');
    const elNoShow = document.getElementById('chartNoShow');
    if (elCheckin) {
        new Chart(elCheckin, {
            type: 'bar',
            data: { labels: labels, datasets: [{ label: 'Check-in สำเร็จ', data: {!! json_encode($checkin) !!}, backgroundColor: 'rgba(54, 162, 235, 0.6)', borderColor: 'rgba(54, 162, 235, 1)', borderWidth: 1 }] },
            options: { scales: { y: { beginAtZero: true } } }
        });
    }
    if (elCancels) {
        new Chart(elCancels, {
            type: 'bar',
            data: { labels: labels, datasets: [{ label: 'การยกเลิก', data: {!! json_encode($cancels) !!}, backgroundColor: 'rgba(255, 99, 132, 0.6)', borderColor: 'rgba(255, 99, 132, 1)', borderWidth: 1 }] },
            options: { scales: { y: { beginAtZero: true } } }
        });
    }
    if (elNoShow) {
        new Chart(elNoShow, {
            type: 'bar',
            data: { labels: labels, datasets: [{ label: 'No Show', data: {!! json_encode($noShow) !!}, backgroundColor: 'rgba(255, 206, 86, 0.6)', borderColor: 'rgba(255, 206, 86, 1)', borderWidth: 1 }] },
            options: { scales: { y: { beginAtZero: true } } }
        });
    }
</script>
@endif

<script>
    // Guard unused chart placeholder (if not present, do nothing)
    const elCheckinChart = document.getElementById('checkinChart');
    if (elCheckinChart) {
        const checkinLabels = @json($labels);
        const checkinDatasets = @json($datasets);
        new Chart(elCheckinChart, {
            type: 'bar',
            data: { labels: checkinLabels, datasets: checkinDatasets },
            options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 10 } } } }
        });
    }
    </script>

{{-- Scripts for route usage report (merged) --}}
@if($activeTab==='route-usage')
<script>
    (function(){
        const el = document.getElementById('routeUsageChart');
        if (!el) return;
        const labels2 = @json($labels);
        const datasets2 = @json($datasets);
        const palette = ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc949','#af7aa1','#ff9da7','#9c755f','#bab0ab'];
        (datasets2||[]).forEach((d,i)=>{
            d.backgroundColor = palette[i % palette.length];
            d.borderColor = d.backgroundColor;
            d.borderWidth = 1;
        });
        new Chart(el, { type: 'bar', data: { labels: labels2, datasets: datasets2 }, options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } } });
    })();
</script>
@endif
@endpush
