<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
    ['name' => 'Pintura',     'name_en' => 'Painting',      'slug' => 'pintura',     'description' => 'Pinturas y acrílicos',          'description_en' => 'Paintings and acrylics'],
    ['name' => 'Cerámica',    'name_en' => 'Ceramics',      'slug' => 'ceramica',    'description' => 'Piezas de cerámica artesanal',   'description_en' => 'Handmade ceramic pieces'],
    ['name' => 'Ilustración', 'name_en' => 'Illustration',  'slug' => 'ilustracion', 'description' => 'Ilustraciones y prints',         'description_en' => 'Illustrations and prints'],
    ['name' => 'Escultura',   'name_en' => 'Sculpture',     'slug' => 'escultura',   'description' => 'Esculturas y figuras',           'description_en' => 'Sculptures and figures'],
    ['name' => 'Fotografía',  'name_en' => 'Photography',   'slug' => 'fotografia',  'description' => 'Fotografía artística',           'description_en' => 'Artistic photography'],
];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}