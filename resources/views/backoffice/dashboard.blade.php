@extends('layouts.backoffice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">รายงานผู้ใช้งาน</h5>
                </div>
                <div class="card-body">
                    {{-- ตารางผู้ใช้ --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>ชื่อ</th>
                                    <th>นามสกุล</th>
                                    <th>วันที่สมัคร</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $user)
                                    <tr>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>
                                            {{ $user->created_at 
                                                ? \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') 
                                                : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">
                                            ไม่มีข้อมูลผู้ใช้
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- กราฟจำนวนผู้ใช้แยกตามเดือน --}}
                    <h6 class="mt-4">จำนวนผู้ใช้แยกตามเดือน ({{ now()->year }})</h6>
                    <canvas id="userChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($userCountsByMonthFull)) !!},
            datasets: [{
                label: 'จำนวนผู้ใช้',
                data: {!! json_encode(array_values($userCountsByMonthFull)) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
