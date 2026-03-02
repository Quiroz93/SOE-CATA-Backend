@extends('admin.layouts.app')

@section('title', 'Perfil')

@section('styles')
    @vite(['resources/css/profile.css'])
@endsection

@section('content')
    <div class="profile-wrapper">
        <div class="profile-container">
            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-photo-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="profile-card">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
