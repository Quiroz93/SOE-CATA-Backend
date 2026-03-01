<section class="profile-section delete-section">
    <header class="profile-section__header">
        <h2 class="profile-section__title">
            Eliminar Cuenta
        </h2>

        <p class="profile-section__description">
            Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.
        </p>
    </header>

    <div class="delete-alert">
        <h3 class="delete-alert__title">⚠ Advertencia</h3>
        <p class="delete-alert__text">
            Esta acción no puede ser deshecha. Por favor procede con cuidado.
        </p>
    </div>

    <div>
        <button type="button" id="delete-account-btn" class="btn btn--danger">
            Eliminar Cuenta
        </button>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="confirm-user-deletion-modal" class="modal-overlay modal-overlay--hidden">
        <div class="modal">
            <div class="modal__header">
                <h2 class="modal__title">
                    ¿Estás seguro de que deseas eliminar tu cuenta?
                </h2>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="modal__body">
                @csrf
                @method('delete')

                <p class="modal__description">
                    Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente. Por favor ingresa tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
                </p>

                <div class="modal__form-group">
                    <label for="password" class="form-label sr-only">Contraseña</label>
                    <input id="password" name="password" type="password" class="form-password" placeholder="Contraseña" />
                    
                    @if ($errors->userDeletion->has('password'))
                        <div class="form-error">
                            <ul class="form-error-list">
                                @foreach ($errors->userDeletion->get('password') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div class="modal__footer">
                    <button type="button" id="cancel-delete-btn" class="btn btn--secondary">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn--danger">
                        Eliminar Cuenta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteBtn = document.getElementById('delete-account-btn');
            const modal = document.getElementById('confirm-user-deletion-modal');
            const cancelBtn = document.getElementById('cancel-delete-btn');

            if (deleteBtn && modal && cancelBtn) {
                deleteBtn.addEventListener('click', function() {
                    modal.classList.remove('modal-overlay--hidden');
                });

                cancelBtn.addEventListener('click', function() {
                    modal.classList.add('modal-overlay--hidden');
                });

                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('modal-overlay--hidden');
                    }
                });
            }
        });
    </script>
</section>
