<section class="profile-section">
    <header class="profile-section__header">
        <h2 class="profile-section__title">
            Foto de Perfil
        </h2>

        <p class="profile-section__description">
            Actualiza tu foto de perfil. Se recomienda una imagen cuadrada.
        </p>
    </header>

    <div class="profile-photo-container">
        <div class="profile-photo-preview">
            @if($user->profile_photo)
                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                     alt="Foto de perfil de {{ $user->name }}" 
                     class="profile-photo-image">
            @else
                <div class="profile-photo-placeholder">
                    <svg class="profile-photo-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <p class="profile-photo-placeholder-text">Sin foto</p>
                </div>
            @endif
        </div>

        <form method="post" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="profile-form">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="profile_photo" class="form-label">Seleccionar nueva foto</label>
                <input id="profile_photo" 
                       name="profile_photo" 
                       type="file" 
                       class="form-input" 
                       accept="image/png,image/jpeg,image/jpg,image/gif"
                       onchange="previewPhoto(event)" />
                <small class="form-helper-text">JPG, PNG o GIF (máximo 2MB)</small>
                
                @if ($errors->has('profile_photo'))
                    <div class="form-error">
                        <ul class="form-error-list">
                            @foreach ($errors->get('profile_photo') as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary">Subir Foto</button>
                
                @if (session('status') === 'photo-updated')
                    <p class="form-status">Foto actualizada.</p>
                @endif
            </div>
        </form>

        @if($user->profile_photo)
            <form method="post" action="{{ route('profile.photo.destroy') }}" class="profile-form" style="margin-top: 1rem;">
                @csrf
                @method('delete')
                
                <button type="submit" class="btn btn--danger btn--sm" onclick="return confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')">
                    🗑️ Eliminar Foto
                </button>
                
                @if (session('status') === 'photo-deleted')
                    <p class="form-status">Foto eliminada.</p>
                @endif
            </form>
        @endif
    </div>
</section>

<style>
    .profile-photo-container {
        margin-top: 1.5rem;
    }

    .profile-photo-preview {
        display: flex;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .profile-photo-image {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #39A900;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-photo-placeholder {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: #f5f5f5;
        border: 3px dashed #ccc;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #999;
    }

    .profile-photo-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 0.5rem;
    }

    .profile-photo-placeholder-text {
        font-size: 0.875rem;
        margin: 0;
    }

    .form-helper-text {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.875rem;
        color: #666;
    }

    .btn--sm {
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
    }
</style>

<script>
    function previewPhoto(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.profile-photo-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="profile-photo-image">`;
            };
            reader.readAsDataURL(file);
        }
    }
</script>
