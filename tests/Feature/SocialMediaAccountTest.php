<?php

namespace Tests\Feature;

use App\Models\SocialMediaAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SocialMediaAccountTest extends TestCase
{
    private function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_admin_can_access_profile_and_social_media_settings_page(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('admin.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Profil Akun', false);
        $response->assertSee('Autentikasi', false);
        $response->assertSee('Sosial Media', false);
    }

    public function test_admin_can_update_profile_information(): void
    {
        $admin = $this->createAdmin();

        $newEmail = 'admin.baru.' . uniqid() . '@ponorogo.go.id';
        $response = $this->actingAs($admin)->put(route('admin.profile.update'), [
            'name' => 'Nama Baru Admin',
            'email' => $newEmail,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'name' => 'Nama Baru Admin',
            'email' => $newEmail,
        ]);
    }

    public function test_admin_can_add_social_media_account(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.profile.social-accounts.store'), [
            'platform' => 'telegram',
            'account_name' => 'Kanal Resmi Kecamatan Mlarak',
            'account_id' => '@kecamatan_mlarak',
            'access_token' => '123456789:ABCdefGhIJKlmNoPQRstuVWXyz',
            'is_active' => '1',
            'auto_post' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('social_media_accounts', [
            'platform' => 'telegram',
            'account_name' => 'Kanal Resmi Kecamatan Mlarak',
            'account_id' => '@kecamatan_mlarak',
            'is_active' => 1,
            'auto_post' => 1,
        ]);
    }

    public function test_admin_can_add_instagram_account(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->post(route('admin.profile.social-accounts.store'), [
            'platform' => 'instagram',
            'account_name' => '@kecamatan_mlarak',
            'account_id' => '17841400000000',
            'access_token' => 'IG_ACCESS_TOKEN_XYZ123',
            'is_active' => '1',
            'auto_post' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('social_media_accounts', [
            'platform' => 'instagram',
            'account_name' => '@kecamatan_mlarak',
            'account_id' => '17841400000000',
            'is_active' => 1,
            'auto_post' => 1,
        ]);
    }

    public function test_admin_can_update_social_media_account(): void
    {
        $admin = $this->createAdmin();
        $account = SocialMediaAccount::create([
            'user_id' => $admin->id,
            'platform' => 'facebook',
            'account_name' => 'Kecamatan Mlarak FP',
            'account_id' => '1029384756',
            'access_token' => 'EAAX...',
            'is_active' => true,
            'auto_post' => false,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.profile.social-accounts.update', $account->id), [
            'platform' => 'facebook',
            'account_name' => 'Kecamatan Mlarak Official Fanpage',
            'account_id' => '1029384756',
            'is_active' => '1',
            'auto_post' => '1',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('social_media_accounts', [
            'id' => $account->id,
            'account_name' => 'Kecamatan Mlarak Official Fanpage',
            'auto_post' => 1,
        ]);
    }

    public function test_admin_can_toggle_social_account_status(): void
    {
        $admin = $this->createAdmin();
        $account = SocialMediaAccount::create([
            'user_id' => $admin->id,
            'platform' => 'telegram',
            'account_name' => 'Test Channel',
            'is_active' => true,
            'auto_post' => true,
        ]);

        $response = $this->actingAs($admin)->postJson(route('admin.profile.social-accounts.toggle', $account->id), [
            'field' => 'auto_post',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'status' => false]);
        $this->assertEquals(0, $account->fresh()->auto_post);
    }

    public function test_admin_can_delete_social_media_account(): void
    {
        $admin = $this->createAdmin();
        $account = SocialMediaAccount::create([
            'user_id' => $admin->id,
            'platform' => 'twitter',
            'account_name' => '@mlarak_ponorogo',
            'access_token' => 'test-token',
        ]);

        $response = $this->actingAs($admin)->delete(route('admin.profile.social-accounts.destroy', $account->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('social_media_accounts', ['id' => $account->id]);
    }
}
