<?php

namespace App\Http\Requests;

use App\Domain\Programa\Enums\EstadoPrograma;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Str;

class ProgramaStoreRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $ficha = $this->input('ficha', $this->input('codigo'));
        $slug = $this->input('slug');

        if (!$slug && $this->filled('nombre')) {
            $slug = Str::slug((string) $this->input('nombre'));
        }

        $this->merge([
            'ficha' => $ficha,
            'slug' => $slug,
        ]);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nombre' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:programas,slug'],
            'ficha' => ['required', 'string', 'max:50', 'unique:programas,ficha'],
            'nivel' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'estado' => ['required', new Enum(EstadoPrograma::class)],
        ];
    }
}
