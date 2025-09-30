@extends('layouts.backoffice')

@section('title', 'แดชบอร์ด')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">สวัสดี</h3>
                </div>
                <div class="card-body">
                    ยินดีต้อนรับสู่ระบบจัดการหลังบ้าน
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ชื่อ</th>
                                    <th>อีเมล</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>สมชาย ใจดี</td>
                                    <td>somchai@example.com</td>
                                    <td><span class="badge bg-success">ใช้งาน</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>สมหญิง สายใจ</td>
                                    <td>somying@example.com</td>
                                    <td><span class="badge bg-secondary">ไม่ใช้งาน</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
