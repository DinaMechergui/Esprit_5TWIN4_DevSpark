<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Vérifie que toutes les pages principales se rendent sans erreur (200/403/404).
 */
class PagesRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_render(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/forgot-password')->assertOk();
        $this->get('/reset-password/unused-token')->assertOk();
    }

    public function test_profile_page_renders_for_authenticated_user(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/profile')->assertOk();
        $this->actingAs($user)->get('/confirm-password')->assertOk();
    }

    public function test_admin_pages_render(): void
    {
        $author = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/admin/users')->assertOk();
        $this->actingAs($admin)->get('/admin/users/create')->assertOk();
        $this->actingAs($admin)->get('/admin/users/'.$author->id)->assertOk();
        $this->actingAs($admin)->get('/admin/users/'.$author->id.'/edit')->assertOk();
    }

    public function test_error_pages_render(): void
    {
        $this->get('/page-inexistante')->assertNotFound();
        $this->get('/admin')->assertRedirect('/login');
    }
}
