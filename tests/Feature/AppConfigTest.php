<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Services\AppConfigService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The admin config panel exists so settings can be changed without server
 * access. Most of these tests are about what it must REFUSE to do — the
 * convenience is only acceptable while credentials stay out of reach.
 */
class AppConfigTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superadmin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'super@test.com',
        ]);
    }

    public function test_superadmin_can_open_the_panel(): void
    {
        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.settings.app-config.index'));

        $response->assertOk();
        $response->assertSee('Nama Aplikasi');
    }

    public function test_other_admin_roles_are_refused(): void
    {
        foreach (['admin_tour', 'admin_umum'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->actingAs($user)
                ->get(route('admin.settings.app-config.index'))
                ->assertForbidden();

            $this->actingAs($user)
                ->post(route('admin.settings.app-config.update'), ['app_name' => 'Diretas'])
                ->assertForbidden();
        }
    }

    public function test_guests_are_refused(): void
    {
        $this->get(route('admin.settings.app-config.index'))->assertRedirect();
    }

    public function test_saved_value_overrides_config(): void
    {
        $this->actingAs($this->superadmin)->post(route('admin.settings.app-config.update'), [
            'app_name' => 'Sujai Laketoba Travel',
            'app_url' => 'https://sujailaketoba.com',
            'log_level' => 'error',
            'mail_from_address' => 'info@sujailaketoba.com',
            'mail_from_name' => 'Sujai Laketoba',
            'session_lifetime' => 120,
        ])->assertRedirect();

        AppConfigService::apply();

        $this->assertSame('Sujai Laketoba Travel', config('app.name'));
        $this->assertSame(120, config('session.lifetime'));
    }

    public function test_credentials_are_never_rendered(): void
    {
        $dbPassword = (string) config('database.connections.mysql.password');
        $appKey = (string) config('app.key');

        // Without this guard the assertions below would pass against an empty
        // string and prove nothing — a security test that cannot fail is worse
        // than no test, because it reads as coverage.
        $this->assertNotSame('', $dbPassword, 'DB password kosong di lingkungan test; assertDontSee di bawah jadi tidak bermakna.');
        $this->assertNotSame('', $appKey, 'APP_KEY kosong di lingkungan test; assertDontSee di bawah jadi tidak bermakna.');

        $response = $this->actingAs($this->superadmin)
            ->get(route('admin.settings.app-config.index'));

        // The real values must not leak, whatever the page happens to list.
        $response->assertDontSee($dbPassword);
        $response->assertDontSee($appKey);

        // No input field may exist for a denied key.
        $response->assertDontSee('name="DB_PASSWORD"', false);
        $response->assertDontSee('name="APP_KEY"', false);
        $response->assertDontSee('name="db_password"', false);
    }

    public function test_crafted_post_cannot_introduce_a_denied_key(): void
    {
        $this->actingAs($this->superadmin)->post(route('admin.settings.app-config.update'), [
            'app_name' => 'Sujai Laketoba',
            'app_url' => 'https://sujailaketoba.com',
            'log_level' => 'error',
            'mail_from_address' => 'info@sujailaketoba.com',
            'mail_from_name' => 'Sujai Laketoba',
            'session_lifetime' => 120,
            // Not offered by the form. Must be discarded, not stored.
            'DB_PASSWORD' => 'dibajak',
            'APP_KEY' => 'base64:dibajak',
            'db_password' => 'dibajak',
        ]);

        $stored = AppConfigService::stored();

        $this->assertArrayNotHasKey('DB_PASSWORD', $stored);
        $this->assertArrayNotHasKey('APP_KEY', $stored);
        $this->assertArrayNotHasKey('db_password', $stored);
        $this->assertSame('Sujai Laketoba', $stored['app_name']);
    }

    public function test_denylist_wins_even_if_a_credential_is_added_to_the_field_list(): void
    {
        // Simulates someone later pasting a credential into config/editable.php.
        // The service must still refuse to surface it.
        config([
            'editable.fields.db_password' => [
                'config' => 'database.connections.mysql.password',
                'label' => 'DB Password',
                'help' => 'seharusnya tidak pernah muncul',
                'type' => 'text',
                'rules' => 'nullable|string',
                'group' => 'Umum',
            ],
            'editable.denied' => array_merge(config('editable.denied'), ['DB_PASSWORD']),
        ]);

        $this->assertArrayNotHasKey('db_password', AppConfigService::fields());

        AppConfigService::save(['db_password' => 'dibajak']);

        $this->assertArrayNotHasKey('db_password', AppConfigService::stored());
    }

    public function test_env_file_is_never_written(): void
    {
        $before = file_get_contents(base_path('.env'));

        $this->actingAs($this->superadmin)->post(route('admin.settings.app-config.update'), [
            'app_name' => 'Nama Baru',
            'app_url' => 'https://sujailaketoba.com',
            'log_level' => 'debug',
            'mail_from_address' => 'info@sujailaketoba.com',
            'mail_from_name' => 'Sujai Laketoba',
            'session_lifetime' => 60,
        ]);

        $this->assertSame($before, file_get_contents(base_path('.env')));
        $this->assertDatabaseHas('settings', ['key' => AppConfigService::STORAGE_KEY]);
    }

    public function test_invalid_input_is_rejected(): void
    {
        $this->actingAs($this->superadmin)->post(route('admin.settings.app-config.update'), [
            'app_name' => '',
            'app_url' => 'bukan-url',
            'log_level' => 'chatty',
            'mail_from_address' => 'bukan-email',
            'mail_from_name' => 'Sujai',
            'session_lifetime' => 1,
        ])->assertSessionHasErrors(['app_name', 'app_url', 'log_level', 'mail_from_address', 'session_lifetime']);

        $this->assertNull(Setting::where('key', AppConfigService::STORAGE_KEY)->first());
    }
}
