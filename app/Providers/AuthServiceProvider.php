<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Programa::class => \App\Policies\ProgramaPolicy::class,
        \App\Models\Oferta::class => \App\Policies\OfertaPolicy::class,
        \App\Models\Noticia::class => \App\Policies\NoticiaPolicy::class,
        \App\Models\Instructor::class => \App\Policies\InstructorPolicy::class,
        \App\Models\Competencia::class => \App\Policies\CompetenciaPolicy::class,
        \App\Models\Centro::class => \App\Policies\CentroPolicy::class,
        \App\Models\User::class => \App\Policies\UserPolicy::class,
        \App\Models\Preinscrito::class => \App\Policies\PreinscritoPolicy::class,
        \App\Models\Inscrito::class => \App\Policies\InscritoPolicy::class,
        \App\Models\Novedad::class => \App\Policies\NovedadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Gate::before para Super Admin (bypass total)
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });
        // Aquí puedes registrar gates o integraciones futuras con Spatie
    }
}
