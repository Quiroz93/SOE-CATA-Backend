<section class="profile-section">
    <header class="profile-section__header">
        <h2 class="profile-section__title">
            Actualizar Contraseña
        </h2>

        <p class="profile-section__description">
            Asegúrate de usar una contraseña larga y aleatoria para mantener tu cuenta segura.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="profile-form">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">Contraseña Actual</label>
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
            <label for="update_password_password" class="form-label">Nueva Contraseña</label>
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
            <label for="update_password_password_confirmation" class="form-label">Confirmar Contraseña</label>
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
            <button type="submit" class="btn">Guardar</button>

            @if (session('status') === 'password-updated')
                <p class="form-status">Guardado.</p>
            @endif
        </div>
    </form>
</section>
