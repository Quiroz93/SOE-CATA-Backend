// Ejemplo de consulta optimizada
$programas = Programa::with(['redesFormacionRelaciones.redFormacion'])
    ->whereHas('redesFormacionRelaciones', function($q) {
        $q->where('estado', 'activo');
    })
    ->get();

// Acceso a redes activas
foreach ($programas as $programa) {
    foreach ($programa->redesFormacionActivas as $relacion) {
        $red = $relacion->redFormacion;
        // ...
    }
}
