<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Sistema de Gestão</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-vh-100 bg-primary d-flex align-items-center justify-content-center p-4">
    <div class="w-100" style="max-width: 28rem;">
        <!-- Login Container -->
        <div class="bg-white bg-opacity-95 rounded-3 shadow-lg overflow-hidden">

            <!-- Header -->
            <div class="bg-primary text-white py-5 px-4 text-center">
                <i class="fas fa-store fs-1 mb-4 opacity-75"></i>
                <h1 class="h4 fw-bold mb-2">Sistema de Gestão</h1>
                <p class="text-primary-emphasis small">PDV Mercado - Faça seu login</p>
            </div>

            <!-- Body -->
            <div class="p-4">
                @if(session('error'))
                    <div class="alert alert-danger d-flex align-items-center mb-4">
                        <i class="fas fa-exclamation-triangle me-3"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4">
                        <i class="fas fa-check-circle me-3"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-exclamation-triangle me-3"></i>
                            <span class="fw-medium">Erros encontrados:</span>
                        </div>
                        <ul class="ms-4 mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email"
                                   class="form-control"
                                   id="email"
                                   name="email"
                                   placeholder="seu@email.com"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Sua senha"
                                   required>
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword()">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox"
                               class="form-check-input"
                               id="remember"
                               name="remember">
                        <label for="remember" class="form-check-label">
                            Lembrar-me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="btn btn-primary w-100 py-3 fs-5 fw-semibold">
                        <i class="fas fa-sign-in-alt me-3"></i>
                        Entrar
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="bg-light px-4 py-3 text-center border-top">
                <a href="{{ route('password.request') }}"
                   class="text-primary fw-medium d-inline-flex align-items-center">
                    <i class="fas fa-key me-2"></i>
                    Esqueceu sua senha?
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Auto-focus no campo de email
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.focus();
            }
        });
    </script>
</body>
</html>
