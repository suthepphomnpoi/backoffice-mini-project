@extends('layouts.backoffice')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">รายงานสรุปยอดผู้ใช้แต่ละเส้นทางรายวัน</h5>
                </div>
                <div class="card-body">

                    {{-- Form เลือกช่วงวันที่ --}}
                    <form class="row g-3 mb-3" method="get">
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

                    {{-- ตารางสรุปแบบวันในสัปดาห์ x เส้นทาง --}}
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
                                @foreach($table as $row)
                                    <tr>
                                        <td class="text-center fw-semibold">{{ $row['day'] }}</td>
                                        @foreach($routes as $route)
                                            <td class="text-end">{{ number_format($row[$route->route_id] ?? 0) }}</td>
                                        @endforeach
                                        <td class="text-end fw-semibold">{{ number_format($row['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td class="text-center">รวมทั้งหมด</td>
                                    @foreach($routes as $route)
                                        <td class="text-end">{{ number_format($totalPerRoute[$route->route_id] ?? 0) }}</td>
                                    @endforeach
                                    <td class="text-end">{{ number_format($grandTotal) }}</td>
                                </tr>
                            </tfoot>
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const datasets = @json(array_map(function($ds, $idx) {
        // Add color in PHP side is cumbersome here; we will assign in JS below
        return $ds; 
    }, $datasets, array_keys($datasets)));

    // Add colors
    const palette = ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f','#edc949','#af7aa1','#ff9da7','#9c755f','#bab0ab'];
    datasets.forEach((d, i) => {
        d.backgroundColor = palette[i % palette.length];
        d.borderColor = d.backgroundColor;
        d.borderWidth = 1;
    });

    new Chart(document.getElementById('routeUsageChart'), {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
@endpush
