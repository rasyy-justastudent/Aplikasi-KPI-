<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Portal — Sistem KPI Guru MI Al-Husna</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons & FontAwesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --noor-emerald-dark: #043e2e;
            --noor-emerald: #064e3b;
            --noor-emerald-light: #0d5c46;
            --noor-bg: #ffffff;
            --noor-text: #111827;
            --noor-muted: #6b7280;
            --noor-border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #ffffff;
            color: var(--noor-text);
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: row;
        }

        /* 1. Left Form Panel (Satu dengan latar putih, tanpa kartu) */
        .login-form-side {
            flex: 1;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 3.5rem 3rem;
            position: relative;
        }

        .form-container-box {
            width: 100%;
            max-width: 420px;
            margin: auto;
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
        }

        .form-portal-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--noor-emerald-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .form-portal-subtitle {
            font-size: 0.9rem;
            color: var(--noor-muted);
            margin-bottom: 2.25rem;
            line-height: 1.55;
        }

        .form-label-custom {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .forgot-link {
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-transform: none;
            font-weight: 600;
            color: var(--noor-emerald-dark);
            text-decoration: none;
            font-size: 0.82rem;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-custom .input-icon-left {
            position: absolute;
            left: 16px;
            color: #9ca3af;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .input-group-custom .form-control {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 0.92rem;
            color: var(--noor-text);
            background: #f8fafc;
            transition: all 0.2s ease;
        }

        .input-group-custom .form-control:focus {
            border-color: var(--noor-emerald);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(6, 78, 59, 0.12);
            outline: none;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 4px;
            font-size: 1.1rem;
        }

        .password-toggle-btn:hover {
            color: var(--noor-emerald-dark);
        }

        .btn-portal-submit {
            width: 100%;
            background-color: var(--noor-emerald-dark);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(4, 62, 46, 0.2);
            cursor: pointer;
            margin-top: 1.75rem;
        }

        .btn-portal-submit:hover {
            background-color: #02261c;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(4, 62, 46, 0.3);
        }

        .login-footer-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #9ca3af;
            text-align: center;
            margin-top: auto;
            padding-top: 2rem;
        }

        /* 2. Right Hero Panel (Gambar & Branding Sisi Kanan) */
        .login-hero-side {
            flex: 1;
            background: linear-gradient(135deg, #033225 0%, #043e2e 40%, #0d5c46 100%);
            color: #ffffff;
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .login-hero-side::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(circle at 20% 30%, rgba(16, 185, 129, 0.18) 0%, transparent 50%),
                              radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.05) 0%, transparent 40%);
            pointer-events: none;
        }

        .hero-logo-box {
            width: 58px;
            height: 58px;
            background: #ffffff;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .hero-logo-box i {
            font-size: 1.8rem;
            color: var(--noor-emerald-dark);
        }

        .hero-main-content {
            max-width: 480px;
            margin: auto 0;
            z-index: 2;
        }

        .hero-welcome-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1.25rem;
            line-height: 1.3;
        }

        .hero-description {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.82);
            line-height: 1.75;
            margin-bottom: 0;
        }

        .hero-footer-pill {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            background: rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 30px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.9);
            width: fit-content;
            z-index: 2;
        }

        .hero-icons-group {
            display: inline-flex;
            gap: 6px;
            background: rgba(255, 255, 255, 0.12);
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .login-wrapper {
                flex-direction: column-reverse;
                min-height: 100vh;
            }
            .login-hero-side {
                padding: 2.5rem 1.5rem;
                min-height: 200px;
            }
            .login-form-side {
                padding: 2.5rem 1.25rem;
            }
        }
        @media (max-width: 576px) {
            .login-hero-side {
                display: none;
            }
            .login-form-side {
                padding: 2rem 1.25rem;
                justify-content: center;
            }
            .form-portal-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">
        <!-- 1. LEFT PANEL: Login Form Side (Form di Sisi Kiri, Menyatu Langsung dengan Latar Putih) -->
        <div class="login-form-side">
            <div class="mb-4">
                <img src="<?= base_url('/img/logo.png') ?>" alt="Logo MI Al-Husna" style="height: 60px; width: auto; object-fit: contain;">
            </div>

            <div class="form-container-box">
                <h3 class="form-portal-title">Masuk ke Portal</h3>
                <p class="form-portal-subtitle">
                    Silakan masukkan kredensial Anda untuk melanjutkan ke dashboard KPI.
                </p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger rounded-3 py-2.5 px-3 mb-4 text-center border-0" style="font-size: 0.85rem; background: #fef2f2; color: #991b1b;">
                        <i class="bi bi-exclamation-circle me-1.5"></i><?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success rounded-3 py-2.5 px-3 mb-4 text-center border-0" style="font-size: 0.85rem; background: #ecfdf5; color: #065f46;">
                        <i class="bi bi-check-circle me-1.5"></i><?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/attempt-login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <!-- Field 1: Email / Username / NIP -->
                    <div class="mb-4">
                        <label class="form-label-custom">Email Guru / NIP / Username</label>
                        <div class="input-group-custom">
                            <i class="bi bi-person input-icon-left"></i>
                            <input type="text" name="username" class="form-control" placeholder="Contoh: ahmad_padil / NIP / Email" required value="<?= old('username') ?>" autofocus>
                        </div>
                    </div>

                    <!-- Field 2: Password -->
                    <div class="mb-3">
                        <div class="form-label-custom">
                            <span>Kata Sandi</span>
                            <a href="#" class="forgot-link" onclick="alert('Silakan hubungi Administrator TU MI Al-Husna untuk me-reset kata sandi Anda.'); return false;">Lupa Kata Sandi?</a>
                        </div>
                        <div class="input-group-custom">
                            <i class="bi bi-lock input-icon-left"></i>
                            <input type="password" id="passwordInput" name="password" class="form-control" placeholder="••••••••" required>
                            <button type="button" class="password-toggle-btn" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-portal-submit">
                        Masuk Portal <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>

            <!-- Footer Callout -->
            <div class="login-footer-text">
                <i class="bi bi-shield-check me-1"></i> AMANAH & EXCELLENCE IN SERVICE
            </div>
        </div>

        <!-- 2. RIGHT PANEL: Graphic Hero Side (Gambar & Pesan di Sisi Kanan) -->
        <div class="login-hero-side">
            <div class="hero-logo-box p-1">
                <img src="<?= base_url('/img/logo.png') ?>" alt="Logo MI Al-Husna" style="max-height: 100%; max-width: 100%; object-fit: contain;">
            </div>

            <div class="hero-main-content">
                <h2 class="hero-welcome-title">Ahlan wa Sahlan di MI Al-Husna</h2>
                <p class="hero-description">
                    Sistem Penilaian Kinerja Guru berlandaskan nilai-nilai Ihsan, mendampingi dedikasi Anda dalam mencetak generasi Rabbani yang beradab dan berilmu.
                </p>
            </div>

            <div class="hero-footer-pill">
                <div class="hero-icons-group">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-book-fill"></i>
                    <i class="bi bi-award-fill"></i>
                </div>
                <span>Mendidik dengan Hati & Ilmu</span>
            </div>
        </div>
    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passInput = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>
