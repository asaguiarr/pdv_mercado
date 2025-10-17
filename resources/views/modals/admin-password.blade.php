<!-- Modal de Confirmação de Senha Administrativa -->
<div class="modal fade" id="adminPasswordModal" tabindex="-1" aria-labelledby="adminPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adminPasswordModalLabel">
          <i class="fas fa-lock me-2"></i>Confirmação de Administrador
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning d-flex align-items-center" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>
          Esta operação requer privilégios de administrador.
        </div>

        <div class="mb-3">
          <label for="admin-password" class="form-label">Digite sua senha de administrador:</label>
          <div class="input-group">
            <input type="password" id="admin-password" class="form-control" placeholder="Senha de administrador">
            <button class="btn btn-outline-secondary" type="button" id="toggle-password">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <div id="password-feedback" class="alert alert-danger d-none" role="alert">
          <i class="fas fa-times-circle me-2"></i>
          <span id="password-message"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          <i class="fas fa-times me-2"></i>Cancelar
        </button>
        <button type="button" class="btn btn-primary" id="confirm-admin-password">
          <i class="fas fa-check me-2"></i>Confirmar
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('adminPasswordModal');
    const bootstrapModal = new bootstrap.Modal(modalEl);
    const passwordInput = document.getElementById('admin-password');
    const toggleBtn = document.getElementById('toggle-password');
    const confirmBtn = document.getElementById('confirm-admin-password');
    const feedback = document.getElementById('password-feedback');
    const messageEl = document.getElementById('password-message');

    // Toggle password visibility
    toggleBtn.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.querySelector('i').classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleBtn.querySelector('i').classList.replace('fa-eye-slash', 'fa-eye');
        }
    });

    // Confirm password
    confirmBtn.addEventListener('click', () => {
        const password = passwordInput.value.trim();
        if (!password) {
            showFeedback('Por favor, digite sua senha');
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verificando...';

        fetch('{{ route("pdv.verify-admin-password") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.valid) {
                feedback.classList.add('d-none');
                bootstrapModal.hide();
                passwordInput.value = '';
                if (window.adminPasswordCallback) window.adminPasswordCallback();
            } else {
                showFeedback(data.message || 'Senha incorreta');
            }
        })
        .catch(() => {
            showFeedback('Erro ao verificar senha');
        })
        .finally(() => {
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fas fa-check me-2"></i>Confirmar';
        });
    });

    function showFeedback(msg) {
        messageEl.textContent = msg;
        feedback.classList.remove('d-none');
    }

    // Global function to show modal with callback
    window.showAdminPasswordModal = function(callback) {
        window.adminPasswordCallback = callback;
        bootstrapModal.show();
    };
});
</script>
