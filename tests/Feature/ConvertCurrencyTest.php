<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConvertCurrencyTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_currency_convert(): void
    {

        $response = $this->getJson('/api/v1/currency/convert?source=USD&target=JPY&amount=$1,525');

        $response->assertStatus(200)
            ->assertJson([
                'msg' => 'success',
                'amount' => '$170,496.53'
            ]);
    }

    public function test_error()
    {
        $response = $this->get('/api/v1/currency/convert?source=USD&target=JPY&amount=$1525');

        $response->assertStatus(422);

        $response = $this->get('/api/v1/currency/convert?source=US&target=JPY&amount=1525');

        $response->assertStatus(422);
    }
}
