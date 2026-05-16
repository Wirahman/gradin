<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Courier;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourierTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function data_kurir_bisa_disimpan_ke_database_lewat_api_store()
    {
        $myUUID = (string) Str::uuid();

        $payload = [
            'name' => 'Budi Agung',
            'nik' => '1234567890123456',
            'address' => 'Jl. Merdeka No. 10',
            'phone' => '081234567890',
            'dob' => '2000-01-01',
            'status' => 'Active',
            'level' => 3,
            'myUUID' => $myUUID
        ];

        $response = $this->postJson('/api/couriers', $payload);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('couriers', [
            'nik' => '1234567890123456',
            'created_by' => $myUUID
        ]);
    }

    /** @test */
    public function data_kurir_hilang_dari_database_setelah_di_delete()
    {
        $courier = Courier::create([
            'name' => 'Joko Susilo',
            'nik' => '6543210987654321',
            'address' => 'Jl. Sudirman',
            'phone' => '085711112222',
            'dob' => '1990-05-12',
            'status' => 'Active',
            'level' => 2,
            'created_by' => (string) Str::uuid()
        ]);

        $response = $this->deleteJson('/api/couriers/' . $courier->uuid);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('couriers', [
            'uuid' => $courier->uuid
        ]);
    }
}
