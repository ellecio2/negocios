<?php

namespace Tests\Feature\Products;

use Tests\TestCase;

class ProductTest extends TestCase {
    public function testCreateProduct() {
        $response = $this->get('/seller/products/create');
        $response->assertStatus(200);
    }

    /*
     * Hago test
     * El test falla
     * Genero el codigo para solucionar el test
     * El test es procesado con exito
     * */

    public function testEditProduct() {
        $response = $this->get('/seller/products/create');
        $response->assertStatus(200);
    }
}
