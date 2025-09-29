{{-- filepath: resources/views/auth/login.blade.php --}}
<!doctype html>
<html lang="th">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>เข้าสู่ระบบ</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- jQuery + jQuery Validate --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.5/jquery.validate.min.js"></script>

    <style>
      body {
        background: linear-gradient(135deg, #f8fafc 0%, #e2eafc 100%);
        min-height: 100vh;
      }
      .card {
        border: none;
        border-radius: 1.2rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
      }
      .brand-logo {
        width: 60px;
        margin-bottom: 1rem;
      }
    </style>
  </head>
  <body>
    <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-7 col-lg-5">
            <div class="card p-4 p-md-5">
              <div class="text-center mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="brand-logo" alt="logo">
                <h3 class="fw-bold mb-1">เข้าสู่ระบบ</h3>
                <div class="text-muted small">กรุณากรอกอีเมลและรหัสผ่านของคุณ</div>
              </div>

              <form method="POST" action="{{ route('login.post') }}" id="empLoginForm">
                @csrf

                @if ($errors->any())
                  <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                  </div>
                @endif

                @if (session('error'))
                  <div class="alert alert-danger py-2">
                    {{ session('error') }}
                  </div>
                @endif

                <div class="mb-3">
                  <label for="email" class="form-label">อีเมล</label>
                  <input type="email"
                         class="form-control @error('email') is-invalid @enderror"
                         id="email"
                         name="email"
                         value="{{ old('email') }}"
                         required
                         autofocus
                         oninvalid="this.setCustomValidity('กรุณากรอกอีเมลให้ถูกต้อง')"
                         oninput="this.setCustomValidity('')">
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="mb-4">
                  <label for="password" class="form-label">รหัสผ่าน</label>
                  <input type="password" class="form-control @error('password') is-invalid @enderror"
                         id="password" name="password" required>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="d-grid mb-2">
                  <button type="submit" class="btn btn-primary btn-lg" id="empLoginBtn">เข้าสู่ระบบ</button>
                </div>

                <div class="text-center mt-3">
                  <a href="{{ route('register') }}">ยังไม่มีบัญชี? สมัครสมาชิก</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Validate script --}}
    <script>
      $(function () {
       const $form = $('#empLoginForm');
        const $btn = $('#empLoginBtn');

        $form.validate({
            onkeyup: function(el) {
                $(el).valid();
            },
            onfocusout: function(el) {
                $(el).valid();
            },
            errorElement: 'div',
            rules: {
                email: {
                    required: true,
                    email: true,
                    normalizer: v => $.trim(v)
                },
                password: {
                    required: true
                }
            },
            messages: {
                email: {
                    required: 'กรุณากรอกอีเมล',
                    email: 'รูปแบบอีเมลไม่ถูกต้อง'
                },
                password: {
                    required: 'กรุณากรอกรหัสผ่าน'
                }
            },
            errorClass: 'is-invalid',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                error.insertAfter(element);
            },
            success: function(label) {
                label.remove();
            },
            highlight: function(el) {
                $(el).addClass('is-invalid');
            },
            unhighlight: function(el) {
                $(el).removeClass('is-invalid');
            }
        });

        $form.on('submit', function(e) {
            e.preventDefault();
            if (!$form.valid()) return;

            startBtnLoading($btn[0]);
            $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: $form.serialize(),
                    dataType: 'json'
                })
                .done(function(res) {
                    window.location.href = '/';
                })
                .fail(function(xhr) {
                    endBtnLoading($btn[0]);
                    alert(xhr.responseJSON.message);
                })

        });
      });
    </script>
  </body>
</html>
