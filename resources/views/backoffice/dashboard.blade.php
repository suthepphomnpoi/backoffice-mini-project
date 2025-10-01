@extends('layouts.backoffice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">รายงานจำนวนเที่ยว ({{ \Carbon\Carbon::parse($start)->translatedFormat('M Y') }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
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

                    <div class="mt-3">
                        <h6>สรุป</h6>
                        <table class="table table-sm table-borderless w-50">
                            <tbody>
                                @foreach($types as $type => $data)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td class="text-end">{{ $data['total'] }}</td>
                                    </tr>
                                @endforeach
                                <tr class="fw-bold">
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
</div>
@endsection
