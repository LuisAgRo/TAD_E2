<?php

return [
    'required' => 'El campo :attribute es obligatorio.',
    'numeric'  => 'El campo :attribute debe ser un número.',
    'integer'  => 'El campo :attribute debe ser un número entero.',
    'image'    => 'El archivo debe ser una imagen.',
    'max'      => [
        'numeric' => 'El campo :attribute no debe ser mayor a :max.',
        'file'    => 'La :attribute no debe pesar más de :max kilobytes.',
        'string'  => 'El campo :attribute no debe tener más de :max caracteres.',
    ],
    'unique'   => 'El :attribute ya está en uso.',
    'exists'   => 'El :attribute seleccionado no es válido.',
    'min'      => [
        'numeric' => 'El campo :attribute debe ser al menos :min.',
        'string'  => 'El campo :attribute debe tener al menos :min caracteres.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Personalización de Atributos
    |--------------------------------------------------------------------------
    | Aquí es donde ocurre la magia. Cambiamos los nombres técnicos de la BD
    | por nombres legibles en español.
    */
    'attributes' => [
        'name'        => 'nombre',
        'description' => 'descripción',
        'price'       => 'precio',
        'stock'       => 'stock',
        'sku'         => 'SKU',
        'category_id' => 'categoría',
        'image'       => 'imagen',
    ],
];