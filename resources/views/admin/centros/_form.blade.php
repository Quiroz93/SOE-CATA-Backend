@csrf

<x-admin.form-input 
    name="nombre" 
    label="Nombre del Centro"
    :value="$centro->nombre ?? null"
/>

<x-admin.form-input 
    name="codigo" 
    label="Código"
    :value="$centro->codigo ?? null"
/>

<x-admin.form-input 
    name="direccion" 
    label="Dirección"
    :value="$centro->direccion ?? null"
/>

<x-admin.form-input 
    name="telefono" 
    label="Teléfono"
    :value="$centro->telefono ?? null"
/>

<x-admin.form-input 
    name="email" 
    label="Email"
    type="email"
    :value="$centro->email ?? null"
/>

<x-admin.form-switch
    name="estado"
    label="Centro activo"
    :checked="$centro->estado ?? true"
/>
