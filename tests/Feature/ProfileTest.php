<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_public_profil_page_is_displayed(): void
    {
        $response = $this->get('/profil');

        $response->assertOk();
        $response->assertSee('Profil Pemerintah Kecamatan Mlarak');
        $response->assertSee('Bagan Struktur Organisasi');
    }

    public function test_admin_can_access_users_management(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        if ($superAdmin) {
            $response = $this->actingAs($superAdmin)->get('/admin/users');
            $response->assertOk();
        }
    }
}
