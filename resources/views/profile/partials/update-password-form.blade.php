<section class="profile-section">
    <header class="profile-section__header">
        <h2 class="profile-section__title">
            {{ __('Update Password') }}
        </h2>

        <p class="profile-section__description">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="profile-form">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-password" autocomplete="current-password" />
            @if ($errors->updatePassword->has('current_password'))
                <div class="form-error">
                    <ul class="form-error-list">
                        @foreach ($errors->updatePassword->get('current_password') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-password" autocomplete="new-password" />
            @if ($errors->updatePassword->has('password'))
                <div class="form-error">
                    <ul class="form-error-list">
                        @foreach ($errors->updatePassword->get('password') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-password" autocomplete="new-password" />
            @if ($errors->updatePassword->has('password_confirmation'))
                <div class="form-error">
                    <ul class="form-error-list">
                        @foreach ($errors->updatePassword->get('password_confirmation') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p class="form-status">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
