<section class="profile-section delete-section">
    <header class="profile-section__header">
        <h2 class="profile-section__title">
            {{ __('Delete Account') }}
        </h2>

        <p class="profile-section__description">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <div class="delete-alert">
        <h3 class="delete-alert__title">⚠ {{ __('Warning') }}</h3>
        <p class="delete-alert__text">
            {{ __('This action cannot be undone. Please proceed with caution.') }}
        </p>
    </div>

    <div>
        <button type="button" id="delete-account-btn" class="btn btn--danger">
            {{ __('Delete Account') }}
        </button>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="confirm-user-deletion-modal" class="modal-overlay modal-overlay--hidden">
        <div class="modal">
            <div class="modal__header">
                <h2 class="modal__title">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="modal__body">
                @csrf
                @method('delete')

                <p class="modal__description">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <div class="modal__form-group">
                    <label for="password" class="form-label sr-only">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" class="form-password" placeholder="{{ __('Password') }}" />
                    
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
                        {{ __('Cancel') }}
                    </button>

                    <button type="submit" class="btn btn--danger">
                        {{ __('Delete Account') }}
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
