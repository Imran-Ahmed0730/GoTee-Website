<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\SolutionCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SolutionCategoryModuleTest extends TestCase
{
    use RefreshDatabase;

    private function adminWithPermissions(array $permissions): User
    {
        $user = User::factory()->create(['role' => 1]);
        $user->givePermissionTo(array_map(fn (string $permission) => Permission::findOrCreate('Solution Category '.$permission, 'web'), $permissions));

        return $user;
    }

    public function test_guest_and_unprivileged_admin_cannot_manage_categories(): void
    {
        $this->get(route('admin.solution-category.index'))->assertRedirect(route('login'));
        $user = $this->adminWithPermissions([]);
        $category = SolutionCategory::create(['name' => 'Example', 'status' => 0]);
        $this->actingAs($user)->get(route('admin.solution-category.index'))->assertForbidden();
        $this->post(route('admin.solution-category.store'), [])->assertForbidden();
        $this->patch(route('admin.solution-category.update', $category), [])->assertForbidden();
        $this->patch(route('admin.solution-category.status', $category))->assertForbidden();
        $this->delete(route('admin.solution-category.destroy', $category))->assertForbidden();
    }

    public function test_categories_can_be_created_edited_toggled_and_deleted(): void
    {
        $this->actingAs($this->adminWithPermissions(['View', 'Add', 'Update', 'Status Change', 'Delete']));
        $this->get(route('admin.solution-category.create'))->assertOk();
        $this->post(route('admin.solution-category.store'), ['name' => 'First Category', 'description' => '<script>test</script>', 'status' => 1])
            ->assertRedirect(route('admin.solution-category.index'))->assertSessionHasNoErrors();
        $category = SolutionCategory::where('name', 'First Category')->firstOrFail();
        $this->assertSame('first-category', $category->slug);
        $this->get(route('admin.solution-category.index'))->assertOk()->assertSee('&lt;script&gt;test&lt;/script&gt;', false);
        $this->get(route('admin.solution-category.edit', $category))->assertOk()->assertSee('First Category');
        $this->patch(route('admin.solution-category.update', $category), ['name' => 'Renamed', 'status' => 1])
            ->assertRedirect(route('admin.solution-category.index'))->assertSessionHasNoErrors();
        $this->assertDatabaseHas('solution_categories', ['id' => $category->id, 'slug' => 'renamed', 'status' => 1]);
        $this->patch(route('admin.solution-category.status', $category))->assertRedirect();
        $this->assertDatabaseHas('solution_categories', ['id' => $category->id, 'status' => 0]);
        $this->delete(route('admin.solution-category.destroy', $category))->assertRedirect(route('admin.solution-category.index'));
        $this->assertDatabaseMissing('solution_categories', ['id' => $category->id]);
    }

    public function test_names_are_unique_but_an_existing_category_can_keep_its_name(): void
    {
        $this->actingAs($this->adminWithPermissions(['Add', 'Update']));
        $category = SolutionCategory::create(['name' => 'Existing', 'status' => 1]);
        $other = SolutionCategory::create(['name' => 'Other', 'status' => 1]);
        $this->from('/')->post(route('admin.solution-category.store'), ['name' => 'Existing', 'status' => 1])->assertSessionHasErrors('name');
        $this->from('/')->patch(route('admin.solution-category.update', $other), ['name' => 'Existing', 'status' => 1])->assertSessionHasErrors('name');
        $this->patch(route('admin.solution-category.update', $category), ['name' => 'Existing', 'status' => 0])->assertSessionHasNoErrors();
        $this->assertDatabaseCount('solution_categories', 2);
    }

    public function test_invalid_fields_and_misleading_image_extensions_are_rejected(): void
    {
        $this->actingAs($this->adminWithPermissions(['Add']));
        $this->from('/')->post(route('admin.solution-category.store'), [])->assertSessionHasErrors(['name', 'status']);
        $this->from('/')->post(route('admin.solution-category.store'), [
            'name' => 'Example', 'status' => 2,
            'image' => $this->image('photo.php'),
            'icon' => UploadedFile::fake()->create('icon.txt', 10, 'text/plain'),
        ])->assertSessionHasErrors(['status', 'image', 'icon']);
        $this->assertDatabaseCount('solution_categories', 0);
    }

    public function test_colliding_long_slugs_fit_the_database_column(): void
    {
        $this->actingAs($this->adminWithPermissions(['Add']));
        $name = str_repeat('a', 254);
        $this->post(route('admin.solution-category.store'), ['name' => $name, 'status' => 1])->assertSessionHasNoErrors();
        $this->post(route('admin.solution-category.store'), ['name' => $name.'!', 'status' => 1])->assertSessionHasNoErrors();
        $this->assertDatabaseHas('solution_categories', ['name' => $name.'!', 'slug' => str_repeat('a', 253).'-2']);
    }

    public function test_images_are_preserved_replaced_and_removed_with_the_category(): void
    {
        $originalPublicPath = public_path();
        $testPublicPath = storage_path('framework/testing/solution-category-'.Str::uuid());
        $this->app->usePublicPath($testPublicPath);

        try {
            $this->actingAs($this->adminWithPermissions(['Add', 'Update', 'Delete']));
            $this->post(route('admin.solution-category.store'), [
                'name' => 'Images', 'status' => 1,
                'image' => $this->image('image.png'), 'icon' => $this->image('icon.png'),
            ])->assertSessionHasNoErrors();
            $category = SolutionCategory::where('name', 'Images')->firstOrFail();
            $oldImage = $category->image;
            $icon = $category->icon;
            $this->assertFileExists(public_path($oldImage));
            $this->assertFileExists(public_path($icon));

            $this->patch(route('admin.solution-category.update', $category), [
                'name' => 'Images', 'status' => 1, 'image' => $this->image('new.png'),
            ])->assertSessionHasNoErrors();
            $category->refresh();
            $this->assertFileDoesNotExist(public_path($oldImage));
            $this->assertFileExists(public_path($category->image));
            $this->assertSame($icon, $category->icon);
            $this->assertFileExists(public_path($icon));

            $newImage = $category->image;
            $this->delete(route('admin.solution-category.destroy', $category))->assertRedirect(route('admin.solution-category.index'));
            $this->assertFileDoesNotExist(public_path($newImage));
            $this->assertFileDoesNotExist(public_path($icon));
        } finally {
            $this->app->usePublicPath($originalPublicPath);
            File::deleteDirectory($testPublicPath);
        }
    }

    private function image(string $name): UploadedFile
    {
        return UploadedFile::fake()->createWithContent($name, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/a9sAAAAASUVORK5CYII='));
    }
}
