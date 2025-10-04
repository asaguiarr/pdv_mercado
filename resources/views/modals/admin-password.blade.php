<!-- Modal de Confirmação de Senha Administrativa -->
<div class="modal fade" id="adminPasswordModal" tabindex="-1" aria-labelledby="adminPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adminPasswordModalLabel">
                    <i class="fas fa-lock me-2"></i>Confirmação de Administrador
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta operação requer privilégios de administrador.
                </div>

                <div class="mb-3">
                    <label for="admin-password" class="form-label">Digite sua senha de administrador:</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="admin-password" placeholder="Senha de administrador">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div id="password-feedback" class="mt-2" style="display: none;">
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        <span id="password-message"></span>
                    </div>
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
$(document).ready(function() {
    // Toggle password visibility
    $('#toggle-password').click(function() {
        const passwordInput = $('#admin-password');
        const icon = $(this).find('i');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Handle admin password confirmation
    $('#confirm-admin-password').click(function() {
        const password = $('#admin-password').val();
        const button = $(this);
        const feedback = $('#password-feedback');
        const message = $('#password-message');

        if (!password) {
            showPasswordFeedback('Por favor, digite sua senha', 'danger');
            return;
        }

        button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Verificando...');

        $.ajax({
            url: '{{ route("pdv.verify-admin-password") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                password: password
            },
            success: function(response) {
                if (response.valid) {
                    feedback.hide();
                    $('#adminPasswordModal').modal('hide');
                    $('#admin-password').val('');

                    // Execute the callback function if provided
                    if (window.adminPasswordCallback) {
                        window.adminPasswordCallback();
                    }
                } else {
                    showPasswordFeedback(response.message || 'Senha incorreta', 'danger');
                }
            },
            error: function(xhr) {
                const errorMessage = xhr.responseJSON?.message || 'Erro ao verificar senha';
                showPasswordFeedback(errorMessage, 'danger');
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-check me-2"></i>Confirmar');
            }
        });
    });

    function showPasswordFeedback(message, type) {
        const feedback = $('#password-feedback');
        const messageEl = $('#password-message');

        messageEl.text(message);
        feedback.removeClass('alert-danger alert-success').addClass(`alert-${type}`);
        feedback.show();
    }

    // Reset modal when closed
    $('#adminPasswordModal').on('hidden.bs.modal', function() {
        $('#admin-password').val('');
        $('#password-feedback').hide();
    });
});

// Global function to show admin password modal
function showAdminPasswordModal(callback) {
    window.adminPasswordCallback = callback;
    $('#adminPasswordModal').modal('show');
}
</script>
