<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PageModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_title_is_rejected(): void
    {
        $user = $this->userWithPermissions(['Page Add']);
        Page::create(['title' => 'About us', 'status' => 1]);

        $this->actingAs($user)->post(route('admin.page.store'), ['title' => 'About us', 'status' => 1])
            ->assertSessionHasErrors('title');

        $this->assertSame(1, Page::count());
    }

    public function test_status_defaults_to_active_but_preserves_inactive_values(): void
    {
        $user = $this->userWithPermissions(['Page Add', 'Page Update']);
        $page = Page::create(['title' => 'Inactive page', 'status' => 0]);

        $this->actingAs($user)->get(route('admin.page.create'))
            ->assertSee('id="status" value="1" checked', false);
        $this->actingAs($user)->get(route('admin.page.edit', $page))
            ->assertDontSee('id="status" value="1" checked', false);
        $this->actingAs($user)->withSession(['_old_input' => ['status' => '0']])
            ->get(route('admin.page.create'))
            ->assertDontSee('id="status" value="1" checked', false);
    }

    public function test_long_colliding_slugs_fit_the_database_column(): void
    {
        $first = Page::create(['title' => str_repeat('a', 254).'?', 'status' => 1]);
        $second = Page::create(['title' => str_repeat('a', 254).'!', 'status' => 1]);

        $this->assertLessThanOrEqual(255, strlen($first->slug));
        $this->assertLessThanOrEqual(255, strlen($second->slug));
        $this->assertNotSame($first->slug, $second->slug);
    }

    public function test_page_can_be_created_updated_toggled_and_deleted(): void
    {
        $user = $this->userWithPermissions(['Page View', 'Page Add', 'Page Update', 'Page Delete', 'Page Status Change']);
        $this->actingAs($user)->post(route('admin.page.store'), ['title' => 'About', 'description' => 'Details', 'status' => 1])->assertRedirect(route('admin.page.index'));
        $page = Page::where('title', 'About')->firstOrFail();
        $this->assertSame('about', $page->slug);
        $this->actingAs($user)->get(route('admin.page.index'))->assertOk()->assertSee('About');
        $this->actingAs($user)->get(route('admin.page.edit', $page))->assertOk();
        $this->actingAs($user)->patch(route('admin.page.update', $page), ['title' => 'About', 'description' => 'Updated', 'status' => 1])->assertRedirect(route('admin.page.index'));
        $this->assertDatabaseHas('pages', ['id' => $page->id, 'description' => 'Updated']);
        $this->actingAs($user)->patch(route('admin.page.status', $page))->assertRedirect();
        $this->assertDatabaseHas('pages', ['id' => $page->id, 'status' => 0]);
        $this->actingAs($user)->delete(route('admin.page.destroy', $page))->assertRedirect(route('admin.page.index'));
        $this->assertModelMissing($page);
    }

    public function test_page_access_requires_permission(): void
    {
        $user = $this->userWithPermissions([]);
        $this->actingAs($user)->get(route('admin.page.index'))->assertForbidden();
        $this->actingAs($user)->post(route('admin.page.store'), ['title' => 'About', 'status' => 1])->assertForbidden();
    }

    private function userWithPermissions(array $permissions): User
    {
        $user = User::factory()->create(['role' => 1]);
        foreach ($permissions as $permission) {
            $user->givePermissionTo(Permission::findOrCreate($permission, 'web'));
        }

        return $user;
    }
}
