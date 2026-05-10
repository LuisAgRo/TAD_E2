<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $pintura     = Category::where('slug', 'pintura')->first();
        $ceramica    = Category::where('slug', 'ceramica')->first();
        $ilustracion = Category::where('slug', 'ilustracion')->first();
        $escultura   = Category::where('slug', 'escultura')->first();
        $fotografia  = Category::where('slug', 'fotografia')->first();

        $products = [
            [
                'name'        => 'Cuadro Bermellón Nº1',
                'description' => 'Pintura al óleo sobre lienzo, tonos bermellón y ocre.',
                'price'       => 120.00,
                'stock'       => 5,
                'image' => 'img/Bermellon.png',
                'sku'         => 'PINT-001',
                'active'      => true,
                'category_id' => $pintura?->id,
            ],
            [
                'name'        => 'Jarrón Artesanal Rojo',
                'description' => 'Jarrón de cerámica hecho a mano con esmalte rojo.',
                'price'       => 45.00,
                'stock'       => 10,
                'image' => 'img/Jarron.png', 
                'sku'         => 'CER-001',
                'active'      => true,
                'category_id' => $ceramica?->id,
            ],
            [
                'name'        => 'Print Abstracto Fuego',
                'description' => 'Ilustración digital en tonos cálidos, impresión en papel.',
                'price'       => 25.00,
                'stock'       => 20,
                'image' => 'img/Fuego.png', 
                'sku'         => 'ILU-001',
                'active'      => true,
                'category_id' => $ilustracion?->id,
            ],
            [
                'name'        => 'Figura Llama Cerámica',
                'description' => 'Escultura decorativa de cerámica esmaltada.',
                'price'       => 60.00,
                'stock'       => 8,
                'image' => 'img/Llama.png', 
                'sku'         => 'ESC-001',
                'active'      => true,
                'category_id' => $escultura?->id,
            ],
            [
                'name'        => 'Fotografía Atardecer Sevilla',
                'description' => 'Fotografía artística enmarcada, edición limitada.',
                'price'       => 85.00,
                'stock'       => 3,
                'image' => 'img/Atardecer.png', 
                'sku'         => 'FOT-001',
                'active'      => true,
                'category_id' => $fotografia?->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}