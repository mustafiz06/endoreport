<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Clinical Login | EndoCapture Pro</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <!-- AdminLTE Theme -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">
    <!-- Medical Theme -->
    <link rel="stylesheet" href="{{ asset('css/medical-theme.css') }}">

    <style>
        body.login-page {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0369a1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .login-box {
            width: 440px;
            max-width: 95%;
        }
        .login-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }
        .login-card-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 24px;
            text-align: center;
        }
        .login-icon-badge {
            width: 56px;
            height: 56px;
            border-radius: 12px;
            background: #0284c7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(2, 132, 199, 0.3);
            margin-bottom: 12px;
        }
        .demo-account-chip {
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.78rem;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px solid #cbd5e1;
            background: #f1f5f9;
            margin: 2px;
            display: inline-block;
        }
        .demo-account-chip:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
        }
    </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-card">
        <div class="login-card-header">
            <div class="login-icon-badge">
                <i class="fas fa-stethoscope"></i>
            </div>
            <h4 class="font-weight-bold text-dark mb-1">EndoCapture <span class="text-primary">Pro</span></h4>
            <p class="text-muted small mb-0">Diagnostic Video Capture & Reporting Workstation</p>
            <div class="badge badge-light border text-secondary mt-2 px-2 py-1">
                <i class="fas fa-hospital-symbol mr-1"></i> {{ $currentOrg?->name ?? 'Apex Endoscopy Center' }}
            </div>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small mb-3">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success py-2 px-3 small mb-3">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="small text-muted font-weight-bold mb-1">CLINICAL EMAIL / USERNAME</label>
                    <div class="input-group">
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', 'admin@hospital.com') }}" required autofocus placeholder="doctor@hospital.com">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white">
                                <span class="fas fa-envelope text-muted"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="small text-muted font-weight-bold mb-1">PASSWORD</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" value="password" required placeholder="••••••••">
                        <div class="input-group-append">
                            <div class="input-group-text bg-white">
                                <span class="fas fa-lock text-muted"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center mb-4">
                    <div class="col-8">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="remember" class="custom-control-input" id="remember" checked>
                            <label class="custom-control-label small text-muted" for="remember">
                                Keep workstation session active
                            </label>
                        </div>
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold shadow-sm">
                            <i class="fas fa-sign-in-alt mr-1"></i> Log In
                        </button>
                    </div>
                </div>
            </form>

            <div class="border-top pt-3 mt-3">
                <div class="text-muted small font-weight-bold mb-2 text-center">
                    <i class="fas fa-user-shield text-info mr-1"></i> Quick Demo Staff Profiles (Click to fill):
                </div>
                <div class="d-flex flex-wrap justify-content-center">
                    <span class="demo-account-chip" onclick="fillLogin('admin@hospital.com')">
                        <i class="fas fa-shield-alt text-danger"></i> <strong>Admin</strong>
                    </span>
                    <span class="demo-account-chip" onclick="fillLogin('doctor@hospital.com')">
                        <i class="fas fa-user-md text-primary"></i> <strong>Doctor</strong>
                    </span>
                    <span class="demo-account-chip" onclick="fillLogin('technician@hospital.com')">
                        <i class="fas fa-microchip text-success"></i> <strong>Technician</strong>
                    </span>
                    <span class="demo-account-chip" onclick="fillLogin('reception@hospital.com')">
                        <i class="fas fa-desktop text-warning"></i> <strong>Reception</strong>
                    </span>
                    <span class="demo-account-chip" onclick="fillLogin('reporter@hospital.com')">
                        <i class="fas fa-file-alt text-info"></i> <strong>Reporter</strong>
                    </span>
                </div>
                <p class="text-center text-muted small mt-2 mb-0">Default Password: <code>password</code></p>
            </div>
        </div>

        <div class="card-footer bg-light text-center py-2 small text-muted">
            <i class="fas fa-lock mr-1"></i> Authorized Medical Personnel Only &bull; HIPAA & Audit Compliant
        </div>
    </div>
</div>

<script>
    function fillLogin(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
    }
</script>
</body>
</html>
