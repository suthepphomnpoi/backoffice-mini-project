<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Shuttle Bus</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabler-icons/3.35.0/tabler-icons.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />

  <!-- OverlayScrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />

  <!-- DataTables v1 + Bootstrap 5 (CSS) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

  <!-- AdminLTE (CSS) -->
  <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body {
      font-family: 'Noto Sans Thai', 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
      background: #f6f8fa;
    }
    .app-header.navbar {
      background: linear-gradient(90deg, #2563eb 0%, #1e293b 100%);
      color: #fff;
      box-shadow: 0 2px 8px rgba(30,41,59,0.08);
    }
    .app-header .nav-link, .app-header .nav-link i {
      color: #fff !important;
    }
    .app-sidebar {
      background: #1e293b !important;
      color: #fff;
      border-right: 1px solid #e5e7eb;
    }
    .sidebar-menu .nav-link.active, .sidebar-menu .nav-link:hover {
      background: #2563eb !important;
      color: #fff !important;
      border-radius: 0.5rem;
      font-weight: 600;
    }
    .sidebar-menu .nav-link, .sidebar-menu .nav-link i {
      color: #cbd5e1 !important;
      transition: background 0.2s, color 0.2s;
    }
    .brand-link {
      font-size: 1.3rem;
      font-weight: 700;
      color: #2563eb !important;
      letter-spacing: 1px;
    }
    .app-content-header {
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
      margin-bottom: 1.5rem;
      padding: 1.2rem 0 0.5rem 0;
      box-shadow: 0 2px 8px rgba(30,41,59,0.03);
    }
    .app-content {
      min-height: 80vh;
    }
    .btn-primary, .btn-primary:focus, .btn-primary:active {
      background: linear-gradient(90deg, #2563eb 0%, #1e40af 100%);
      border: none;
      box-shadow: 0 2px 8px rgba(37,99,235,0.08);
    }
    .btn {
      border-radius: 0.5rem;
      font-weight: 600;
      letter-spacing: 0.5px;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 2px 8px rgba(30,41,59,0.06);
      border: none;
    }
    table.dataTable {
      background: #fff;
      border-radius: 0.75rem;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(30,41,59,0.04);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
      border-radius: 0.5rem !important;
      margin: 0 2px;
      background: #f1f5f9 !important;
      color: #2563eb !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
      background: #2563eb !important;
      color: #fff !important;
    }
    .form-control, .form-select {
      border-radius: 0.5rem;
      border: 1px solid #e5e7eb;
      background: #f8fafc;
      font-size: 1rem;
    }
    .swal2-popup {
      border-radius: 1rem !important;
    }
  </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary" style="background: #f6f8fa;">
  <div class="app-wrapper">
    @include('partials.backoffice-navbar')
    @include('partials.backoffice-sidebar')

    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">@yield('title')</h3></div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </main>
  </div>

  <!-- ===== JS (วางท้าย body) ===== -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>

  <!-- DataTables v1 + Bootstrap 5 (JS) -->
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

  <!-- Bootstrap bundle + AdminLTE -->
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/adminlte.min.js') }}"></script>

  <!-- OverlayScrollbars -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- CSRF for AJAX -->
  <script>
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    });
  </script>

  <!-- OverlayScrollbars init -->
  <script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = { scrollbarTheme: 'os-theme-light', scrollbarAutoHide: 'leave', scrollbarClickScroll: true };
    document.addEventListener('DOMContentLoaded', function () {
      const el = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
      if (el && OverlayScrollbarsGlobal?.OverlayScrollbars) {
        OverlayScrollbarsGlobal.OverlayScrollbars(el, { scrollbars: Default });
      }
    });
  </script>

  <!-- Helpers (global) -->
  <script>
    window.startBtnLoading = function(btn, text){
      const $btn = $(btn);
      $btn.data('orig-html', $btn.html());
      $btn.prop('disabled', true).html(
        `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>${text || 'Processing...'}`
      );
    };
    window.endBtnLoading = function(btn){
      const $btn = $(btn);
      $btn.prop('disabled', false).html($btn.data('orig-html') || 'บันทึก');
    };
    window.confirmSwal = function(messageOrOpts){
      const opts = typeof messageOrOpts === 'string' ? { text: messageOrOpts } : (messageOrOpts || {});
      return Swal.fire({
        title: opts.title || 'ยืนยันการทำรายการ?',
        text:  opts.text ?? opts.text,
        icon:  opts.icon || 'question',
        showCancelButton: true,
        confirmButtonText: opts.confirmText || 'ตกลง',
        cancelButtonText:  opts.cancelText  || 'ยกเลิก'
      });
    };
    window.showSwalSuccess = (msg) => Swal.fire({ icon:'success', title:'สำเร็จ', text: msg || 'ดำเนินการเรียบร้อย' });
    window.showSwalError   = (msg) => Swal.fire({ icon:'error',   title:'ไม่สำเร็จ', text: msg || 'เกิดข้อผิดพลาด' });
  </script>

  @stack('scripts')
</body>
</html>
