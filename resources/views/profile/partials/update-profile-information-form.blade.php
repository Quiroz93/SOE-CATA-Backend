<section class="profile-section">
    <header class="profile-section__header">
        <h2 class="profile-section__title">
            Información de Perfil
        </h2>

        <p class="profile-section__description">
            Actualiza la información de tu perfil y dirección de correo. }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="profile-form">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Nombre</label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            @if ($errors->has('name'))
                <div class="form-error">
                    <ul class="form-error-list">
                        @foreach ($errors->get('name') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @if ($errors->has('email'))
                <div class="form-error">
                    <ul class="form-error-list">
                        @foreach ($errors->get('email') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="verification-alert">
                    <p class="verification-alert__text">
                        Tu correo electrónico no ha sido verificado.

                        <button form="send-verification" class="verification-link">
                            Haz clic aquí para reenviar el correo de verificación. }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="verification-success">
                            Un nuevo enlace de verificación ha sido enviado a tu correo electrónico. }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Guardar</button>

            @if (session('status') === 'profile-updated')
                <p class="form-status">Guardado.</p>
            @endif
        </div>
    </form>
</section>
