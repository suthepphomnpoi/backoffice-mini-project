<!doctype html>
<html lang="th">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
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
                <h3 class="fw-bold mb-1">สมัครสมาชิก</h3>
                <div class="text-muted small">กรุณากรอกข้อมูลเพื่อสมัครสมาชิก</div>
              </div>
              <form method="POST" action="{{ route('register.post') }}" id="empRegisterForm">
                @csrf

                @if ($errors->any())
                  <div class="alert alert-danger py-2">
                    {{ $errors->first() }}
                  </div>
                @endif

                <div class="row mb-3">
                  <div class="col">
                    <label for="first_name" class="form-label">ชื่อ</label>
                    <input type="text"
                           class="form-control @error('first_name') is-invalid @enderror"
                           id="first_name"
                           name="first_name"
                           value="{{ old('first_name') }}"
                           required autofocus>
                    @error('first_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col">
                    <label for="last_name" class="form-label">นามสกุล</label>
                    <input type="text"
                           class="form-control @error('last_name') is-invalid @enderror"
                           id="last_name"
                           name="last_name"
                           value="{{ old('last_name') }}"
                           required>
                    @error('last_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">อีเมล</label>
                  <input type="email"
                         class="form-control @error('email') is-invalid @enderror"
                         id="email"
                         name="email"
                         value="{{ old('email') }}"
                         required
                         oninvalid="this.setCustomValidity('กรุณากรอกอีเมลให้ถูกต้อง')"
                         oninput="this.setCustomValidity('')">
                  @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label class="form-label">เพศ</label>
                  <div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="gender" id="gender_male" value="M" {{ old('gender', 'M') == 'M' ? 'checked' : '' }} required>
                      <label class="form-check-label" for="gender_male">ชาย</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="gender" id="gender_female" value="F" {{ old('gender') == 'F' ? 'checked' : '' }} required>
                      <label class="form-check-label" for="gender_female">หญิง</label>
                    </div>
                  </div>
                  @error('gender')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="dept_id" class="form-label">แผนก</label>
                  <select class="form-select @error('dept_id') is-invalid @enderror" id="dept_id" name="dept_id" required>
                    <option value="">-- เลือกแผนก --</option>
                    @foreach($departments as $dept)
                      <option value="{{ $dept->dept_id }}" {{ old('dept_id') == $dept->dept_id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                  </select>
                  @error('dept_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="position_id" class="form-label">ตำแหน่ง</label>
                  <select class="form-select @error('position_id') is-invalid @enderror" id="position_id" name="position_id" required>
                    <option value="">-- เลือกตำแหน่ง --</option>
                    @foreach($positions as $pos)
                      <option value="{{ $pos->position_id }}" {{ old('position_id') == $pos->position_id ? 'selected' : '' }}>{{ $pos->name }}</option>
                    @endforeach
                  </select>
                  @error('position_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="password" class="form-label">รหัสผ่าน</label>
                  <input type="password"
                         class="form-control @error('password') is-invalid @enderror"
                         id="password"
                         name="password"
                         required>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="mb-4">
                  <label for="password_confirmation" class="form-label">ยืนยันรหัสผ่าน</label>
                  <input type="password"
                         class="form-control"
                         id="password_confirmation"
                         name="password_confirmation"
                         required>
                </div>
                <div class="d-grid mb-2">
                  <button type="submit" class="btn btn-primary btn-lg" id="empRegisterBtn">สมัครสมาชิก</button>
                </div>
                <div class="text-center mt-3">
                  <a href="{{ route('login') }}">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      $(function () {
        const $form = $('#empRegisterForm');
        const $btn = $('#empRegisterBtn');

        $form.validate({
          onkeyup: function(el) { $(el).valid(); },
          onfocusout: function(el) { $(el).valid(); },
          errorElement: 'div',
          rules: {
            first_name: { required: true, normalizer: v => $.trim(v) },
            last_name: { required: true, normalizer: v => $.trim(v) },
            gender: { required: true },
            dept_id: { required: true },
            position_id: { required: true },
            email: { required: true, email: true, normalizer: v => $.trim(v) },
            password: { required: true, minlength: 6 },
            password_confirmation: { required: true, equalTo: "#password" }
          },
          messages: {
            first_name: { required: 'กรุณากรอกชื่อ' },
            last_name: { required: 'กรุณากรอกนามสกุล' },
            gender: { required: 'กรุณาเลือกเพศ' },
            dept_id: { required: 'กรุณาเลือกแผนก' },
            position_id: { required: 'กรุณาเลือกตำแหน่ง' },
            email: { required: 'กรุณากรอกอีเมล', email: 'รูปแบบอีเมลไม่ถูกต้อง' },
            password: { required: 'กรุณากรอกรหัสผ่าน', minlength: 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร' },
            password_confirmation: { required: 'กรุณายืนยันรหัสผ่าน', equalTo: 'รหัสผ่านไม่ตรงกัน' }
          },
          errorClass: 'is-invalid',
          errorPlacement: function(error, element) {
            if (element.attr('name') === 'gender') {
              error.addClass('invalid-feedback d-block');
              error.insertAfter(element.closest('.mb-3').find('.form-check-inline').last());
            } else {
              error.addClass('invalid-feedback');
              error.insertAfter(element);
            }
          },
          success: function(label) { label.remove(); },
          highlight: function(el) { $(el).addClass('is-invalid'); },
          unhighlight: function(el) { $(el).removeClass('is-invalid'); }
        });

        $form.on('submit', function(e) {
          if (!$form.valid()) {
            e.preventDefault();
            return;
          }
        });
      });
    </script>
  </body>
</html>