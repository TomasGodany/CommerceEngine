<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductCatalogSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            'Tričká' => 'Pohodlné tričká pre každodenné nosenie.',
            'Mikiny' => 'Teplé mikiny a svetre na chladnejšie dni.',
            'Nohavice' => 'Džínsy, chinos a športové nohavice.',
            'Bundy' => 'Zimné a jarné bundy pre každé počasie.',
            'Obuv' => 'Tenisky, topánky a sandále.',
        ])->map(fn (string $description, string $name) => Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name, 'description' => $description, 'is_active' => true]
        ));

        $brands = collect(['Citro Wear', 'Urban Style', 'Nordic Line', 'Sport Pro'])
            ->map(fn (string $name) => Brand::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'description' => $name.' - obľúbená módna značka.', 'is_active' => true]
            ));

        $products = [
            ['name' => 'Pánske bavlnené tričko', 'category' => 'Tričká', 'price' => 14.99, 'sale_price' => 9.99, 'color' => [45, 45, 45]],
            ['name' => 'Dámske tričko s potlačou', 'category' => 'Tričká', 'price' => 17.99, 'sale_price' => null, 'color' => [215, 230, 0]],
            ['name' => 'Pánska mikina s kapucňou', 'category' => 'Mikiny', 'price' => 34.99, 'sale_price' => 27.99, 'color' => [60, 60, 60]],
            ['name' => 'Dámsky sveter s golierom', 'category' => 'Mikiny', 'price' => 29.99, 'sale_price' => null, 'color' => [90, 90, 90]],
            ['name' => 'Pánske džínsy Slim Fit', 'category' => 'Nohavice', 'price' => 44.99, 'sale_price' => 34.99, 'color' => [30, 40, 70]],
            ['name' => 'Dámske chino nohavice', 'category' => 'Nohavice', 'price' => 39.99, 'sale_price' => null, 'color' => [70, 60, 50]],
            ['name' => 'Pánska zimná bunda', 'category' => 'Bundy', 'price' => 89.99, 'sale_price' => 69.99, 'color' => [20, 20, 20]],
            ['name' => 'Dámska jarná bunda', 'category' => 'Bundy', 'price' => 64.99, 'sale_price' => null, 'color' => [150, 30, 30]],
            ['name' => 'Pánske tenisky Street', 'category' => 'Obuv', 'price' => 54.99, 'sale_price' => 44.99, 'color' => [230, 230, 230]],
            ['name' => 'Dámske športové tenisky', 'category' => 'Obuv', 'price' => 59.99, 'sale_price' => null, 'color' => [215, 230, 0]],
        ];

        foreach ($products as $item) {
            $slug = Str::slug($item['name']);

            if (Product::where('slug', $slug)->exists()) {
                continue;
            }

            $imagePath = $this->generateProductImage($item['name'], $item['color']);

            Product::create([
                'category_id' => $categories[$item['category']]->id,
                'brand_id' => $brands->random()->id,
                'name' => $item['name'],
                'slug' => $slug,
                'sku' => 'CE-'.strtoupper(Str::random(8)),
                'description' => $item['name'].' - kvalitný kúsok oblečenia z pohodlných materiálov, vhodný na denné nosenie.',
                'short_description' => $item['name'],
                'price' => $item['price'],
                'sale_price' => $item['sale_price'],
                'image_path' => $imagePath,
                'seo_title' => $item['name'],
                'seo_description' => 'Kúpte si '.$item['name'].' v e-shope Commerce Engine.',
                'is_active' => true,
            ]);
        }
    }

    /**
     * Generate a simple placeholder product image and store it on the public disk.
     *
     * @param  array{0: int, 1: int, 2: int}  $color
     */
    private function generateProductImage(string $name, array $color): string
    {
        $width = 600;
        $height = 600;

        $image = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($image, ...$color);
        $white = imagecolorallocate($image, 255, 255, 255);
        $accent = imagecolorallocate($image, 0xD7, 0xE6, 0x00);

        imagefilledrectangle($image, 0, 0, $width - 1, $height - 1, $background);

        // Simple clothing-hanger silhouette to indicate a clothing item.
        imagesetthickness($image, 6);
        imageline($image, (int) ($width / 2), 120, (int) ($width / 2), 160, $accent);
        imagearc($image, (int) ($width / 2), 110, 30, 30, 0, 360, $accent);
        imageline($image, (int) ($width / 2), 160, (int) ($width / 2 - 160), 300, $white);
        imageline($image, (int) ($width / 2), 160, (int) ($width / 2 + 160), 300, $white);
        imageline($image, (int) ($width / 2 - 160), 300, (int) ($width / 2 + 160), 300, $white);

        $label = wordwrap($name, 22, "\n", true);
        $font = 5;
        $lines = explode("\n", $label);
        $lineHeight = imagefontheight($font) + 6;
        $startY = $height - 90;

        foreach ($lines as $index => $line) {
            $textWidth = imagefontwidth($font) * strlen($line);
            $x = (int) (($width - $textWidth) / 2);
            imagestring($image, $font, $x, $startY + ($index * $lineHeight), $line, $white);
        }

        ob_start();
        imagepng($image);
        $contents = ob_get_clean();

        $path = 'products/'.Str::uuid().'.png';
        Storage::disk('public')->put($path, $contents);

        return $path;
    }
}
