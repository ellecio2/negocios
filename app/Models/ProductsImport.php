<?php

namespace App\Models;

use Auth;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

//class ProductsImport implements ToModel, WithHeadingRow, WithValidation
class ProductsImport implements ToCollection, WithHeadingRow, WithValidation, ToModel {
    private $rows = 0;

    public function collection(Collection $rows) {
        $user = Auth::user();
        $canImport = true;
        $importLimit = 0;

        if ($user->user_type == 'seller' && addon_is_activated('seller_subscription')) {
            $currentProductCount = $user->products()->count();
            $totalAllowed = $user->shop->seller_package->product_upload_limit;
            $importLimit = $totalAllowed - $currentProductCount;

            if ($importLimit <= 0 || $user->shop->package_invalid_at == null || Carbon::now()->diffInDays(Carbon::parse($user->shop->package_invalid_at), false) < 0) {
                $canImport = false;
                flash(translate('Has alcanzado el limite de productos, actualiza tu paquete.'))->warning();
            }
        }

        if ($canImport) {
            $importedCount = 0;


            foreach ($rows as $row) {
                if ($importedCount >= $importLimit) {
                    flash(translate("Se han importado $importedCount has alcanzado tu limite de {$importLimit} productos, actualiza tu paquete"))->warning();
                    break;
                }

                $approved = 1;
                if ($user->user_type == 'seller' && get_setting('product_approve_by_admin') == 1) {
                    $approved = 1;
                }
                if (empty(array_filter($row->toArray()))) {
                    break;
                }
                $requiredFields = ['nombre', 'descripcion', 'opciones', 'subcategorias', 'marca', 'existencia', 'codigo_barras', 'etiquetas', 'precio', 'unidad', 'peso', 'alto', 'ancho', 'largo', 'imagen'];
                $missingFields = [];
            
                foreach ($requiredFields as $field) {
                    if (!isset($row[$field])) {
                        $missingFields[] = $field;
                    }
                }
            
                if (!empty($missingFields)) {
                    $missingFieldsString = implode(', ', $missingFields);
                    flash(translate("Faltan datos en algunas filas del archivo Excel: $missingFieldsString"))->warning();
                    break;
                }
                
                $product = Product::create([
                    'name' => $row['nombre'],
                    'description' => $row['descripcion'],
                    'added_by' => $user->user_type == 'seller' ? 'seller' : 'admin',
                    'user_id' => $user->user_type == 'seller' ? $user->id : User::where('user_type', 'admin')->first()->id,
                    'approved' => $approved,
                    'category_id' => $row['opciones'],
                    'subcategory_id' => $row['subcategorias'],
                    'brand_id' => $row['marca'],
                    'current_stock' => $row['existencia'],
                    'barcode' => $row['codigo_barras'],
                    'video_provider' => 'YouTube',
                    'video_link' => '',
                    'tags' => $row['etiquetas'],
                    'external_link_btn' => 'Comprar Ahora',
                    'earn_point' => 1,
                    'unit_price' => $row['precio'],
                    'unit' => $row['unidad'],
                    'weight' => $row['peso'],
                    'meta_title' => $row['nombre'],
                    'meta_description' => $row['descripcion'],
                    'est_shipping_days' => '',
                    'colors' => json_encode(array()),
                    'attributes' => '["1"]',
                    'choice_options' => '[{"attribute_id":"1","values":["'.$row['alto'].'x'.$row['ancho'].'x'.$row['largo'].'"]}]',
                    'variations' => json_encode(array()),
                    'slug' => preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', strtolower($row['nombre']))) . '-' . Str::random(5),
                    'thumbnail_img' => $this->downloadThumbnail($row['imagen']),
                    'photos' => $this->downloadGalleryImages($row['imagen']),
                    
                ]);
                ProductStock::create([
                    'product_id' => $product->id,
                    'qty' => $row['existencia'],
                    'price' => $row['precio'],
                    'sku' => $row['oem'],
                    'variant' => '',
                ]);
                ProductTax::create([
                    'product_id' => $product->id,
                    'tax_id' => 3,
                    'tax' => 0,
                ]);

                $importedCount++;
            }

            if ($importedCount >= 0) {
                flash(translate('Productos importados exitosamente'))->success();
            }
        }
    }

    public function downloadThumbnail($url) {
        try {
            $contents = file_get_contents($url);
            $name = 'uploads/all/' . Str::random(15) . '.png';
            $path = public_path($name);
            file_put_contents($path, $contents);

            $upload = new Upload;
            $upload->external_link = $url;
            $upload->file_name = $name;
            $upload->type = 'image';
            $upload->save();

            return $upload->id;
        } catch (\Exception $e) {
            \Log::error('Error al descargar la imagen: ' . $e->getMessage());

        }
        return null;
    }

    public function downloadGalleryImages($urls) {
        $data = array();
        foreach (explode(',', str_replace(' ', '', $urls)) as $url) {
            $data[] = $this->downloadThumbnail($url);
        }
        return implode(',', $data);
    }

    public function model(array $row) {
        ++$this->rows;
    }

    public function getRowCount(): int {
        return $this->rows;
    }

    public function rules(): array {
        return [
            // Can also use callback validation rules
            'unit_price' => function ($attribute, $value, $onFailure) {
                if (!is_numeric($value)) {
                    $onFailure('El Precio Unitario no es Numerico');
                }
            }
        ];
    }
}
