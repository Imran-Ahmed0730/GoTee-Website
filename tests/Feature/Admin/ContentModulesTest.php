<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\Blog;
use App\Models\Admin\BlogCategory;
use App\Models\Admin\Faq;
use App\Models\Admin\FaqCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ContentModulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_blog_categories_can_be_created_updated_status_changed_and_deleted(): void
    {
        $user = $this->userWithPermissions([
            'Blog Category Add',
            'Blog Category View',
            'Blog Category Update',
            'Blog Category Delete',
            'Blog Category Status Change',
        ]);

        $this->actingAs($user)
            ->post(route('admin.blog.category.store'), [
                'name' => 'Summer Wear',
                'description' => 'Seasonal styles',
                'icon' => $this->fakePng('icon.png'),
                'cover_photo' => $this->fakePng('summer.png'),
                'status' => 1,
            ])
            ->assertRedirect(route('admin.blog.category.index'));

        $category = BlogCategory::query()->where('name', 'Summer Wear')->firstOrFail();
        $storedIcon = $category->icon;
        $storedCoverPhoto = $category->cover_photo;
        $this->assertSame('summer-wear', $category->slug);
        $this->assertDatabaseHas('blog_categories', [
            'id' => $category->id,
            'name' => 'Summer Wear',
            'slug' => 'summer-wear',
        ]);
        $this->assertFileExists(public_path($storedCoverPhoto));
        $this->assertFileExists(public_path($storedIcon));
        $this->assertSame('summer-wear-2', BlogCategory::create([
            'name' => 'Summer-Wear',
            'status' => 1,
        ])->slug);

        $this->actingAs($user)
            ->get(route('admin.blog.category.index'))
            ->assertOk()
            ->assertSee('Summer Wear')
            ->assertSee('src="'.asset($storedIcon).'"', false)
            ->assertSee('src="'.asset('images/blog-category-placeholder.svg').'"', false);

        $this->actingAs($user)
            ->get(route('admin.blog.category.create'))
            ->assertOk()
            ->assertSee('src="'.asset('images/blog-category-placeholder.svg').'"', false);

        $this->actingAs($user)
            ->get(route('admin.blog.category.edit', $category))
            ->assertOk()
            ->assertSee('Summer Wear')
            ->assertSee('src="'.asset($storedIcon).'"', false);

        $this->actingAs($user)
            ->patch(route('admin.blog.category.update', $category), [
                'name' => 'Warm Weather',
                'description' => 'Updated seasonal styles',
                'icon' => $this->fakePng('updated-icon.png'),
                'cover_photo' => $this->fakePng('updated-cover.png'),
                'status' => 1,
            ])
            ->assertRedirect(route('admin.blog.category.index'));

        $category->refresh();
        $updatedIcon = $category->icon;
        $updatedCoverPhoto = $category->cover_photo;
        $this->assertSame('warm-weather', $category->slug);
        $this->assertNotSame($storedIcon, $updatedIcon);
        $this->assertFileDoesNotExist(public_path($storedIcon));
        $this->assertFileExists(public_path($updatedIcon));
        $this->assertNotSame($storedCoverPhoto, $updatedCoverPhoto);
        $this->assertFileDoesNotExist(public_path($storedCoverPhoto));
        $this->assertFileExists(public_path($updatedCoverPhoto));

        $this->actingAs($user)
            ->patch(route('admin.blog.category.status', $category))
            ->assertRedirect();
        $this->assertDatabaseHas('blog_categories', ['id' => $category->id, 'status' => 0]);

        $this->actingAs($user)
            ->delete(route('admin.blog.category.destroy', $category))
            ->assertRedirect(route('admin.blog.category.index'));

        $this->assertDatabaseMissing('blog_categories', ['id' => $category->id]);
        $this->assertFileDoesNotExist(public_path($storedCoverPhoto));
        $this->assertFileDoesNotExist(public_path($updatedCoverPhoto));
        $this->assertFileDoesNotExist(public_path($updatedIcon));
    }

    public function test_blogs_can_be_created_updated_filtered_status_changed_and_deleted(): void
    {
        $user = $this->userWithPermissions([
            'Blog Add',
            'Blog View',
            'Blog Update',
            'Blog Delete',
            'Blog Status Change',
            'Blog Category Delete',
        ]);
        $category = BlogCategory::create(['name' => 'News', 'status' => 1]);

        $this->actingAs($user)
            ->post(route('admin.blog.store'), [
                'category_id' => $category->id,
                'title' => 'New Summer Collection',
                'description' => "A look at this season's collection.",
                'thumbnail' => $this->fakePng('thumbnail.png'),
                'cover_photo' => $this->fakePng('cover.png'),
                'status' => 1,
            ])
            ->assertRedirect(route('admin.blog.index'));

        $blog = Blog::query()->where('title', 'New Summer Collection')->firstOrFail();
        $storedThumbnail = $blog->thumbnail;
        $storedCoverPhoto = $blog->cover_photo;
        $this->assertSame('new-summer-collection', $blog->slug);
        $this->assertFileExists(public_path($storedThumbnail));
        $this->assertFileExists(public_path($storedCoverPhoto));
        $placeholderDirectory = public_path('uploads/blogs/thumbnails');
        if (! is_dir($placeholderDirectory)) {
            mkdir($placeholderDirectory, 0775, true);
        }
        file_put_contents($placeholderDirectory.'/placeholder.png', 'placeholder image');
        $collisionBlog = Blog::create([
            'category_id' => $category->id,
            'title' => 'New-Summer-Collection',
            'thumbnail' => 'uploads/blogs/thumbnails/placeholder.png',
            'status' => 1,
        ]);
        $this->assertSame('new-summer-collection-2', $collisionBlog->slug);

        $this->actingAs($user)
            ->get(route('admin.blog.index', ['category_id' => $category->id, 'status' => 1]))
            ->assertOk()
            ->assertSee('New Summer Collection');

        $this->actingAs($user)
            ->get(route('admin.blog.edit', $blog))
            ->assertOk()
            ->assertSee('New Summer Collection');

        $this->actingAs($user)
            ->patch(route('admin.blog.update', $blog), [
                'category_id' => $category->id,
                'title' => 'Summer Collection Guide',
                'description' => 'Updated details.',
                'thumbnail' => $this->fakePng('updated-thumbnail.png'),
                'cover_photo' => $this->fakePng('updated-cover.png'),
                'status' => 1,
            ])
            ->assertRedirect(route('admin.blog.index'));

        $blog->refresh();
        $updatedThumbnail = $blog->thumbnail;
        $updatedCoverPhoto = $blog->cover_photo;
        $this->assertSame('summer-collection-guide', $blog->slug);
        $this->assertNotSame($storedThumbnail, $updatedThumbnail);
        $this->assertNotSame($storedCoverPhoto, $updatedCoverPhoto);
        $this->assertFileDoesNotExist(public_path($storedThumbnail));
        $this->assertFileDoesNotExist(public_path($storedCoverPhoto));
        $this->assertFileExists(public_path($updatedThumbnail));
        $this->assertFileExists(public_path($updatedCoverPhoto));

        $this->actingAs($user)
            ->patch(route('admin.blog.status', $blog))
            ->assertRedirect();
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'status' => 0]);

        $this->actingAs($user)
            ->delete(route('admin.blog.destroy', $blog))
            ->assertRedirect(route('admin.blog.index'));

        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);
        $this->assertFileDoesNotExist(public_path($updatedThumbnail));
        $this->assertFileDoesNotExist(public_path($updatedCoverPhoto));

        $this->actingAs($user)
            ->delete(route('admin.blog.category.destroy', $category))
            ->assertRedirect(route('admin.blog.category.index'));
        $this->assertDatabaseMissing('blogs', ['id' => $collisionBlog->id]);
        $this->assertFileDoesNotExist(public_path('uploads/blogs/thumbnails/placeholder.png'));
    }

    public function test_blog_thumbnail_is_required_when_creating_without_a_blog_id(): void
    {
        $user = $this->userWithPermissions(['Blog Add']);
        $category = BlogCategory::create(['name' => 'News', 'status' => 1]);

        $this->actingAs($user)
            ->from(route('admin.blog.create'))
            ->post(route('admin.blog.store'), [
                'category_id' => $category->id,
                'title' => 'Thumbnail Required',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.blog.create'))
            ->assertSessionHasErrors(['thumbnail']);
    }

    public function test_faq_categories_and_faqs_support_full_crud_and_status_changes(): void
    {
        $user = $this->userWithPermissions([
            'FAQ Category Add',
            'FAQ Category View',
            'FAQ Category Update',
            'FAQ Category Delete',
            'FAQ Category Status Change',
            'FAQ Add',
            'FAQ View',
            'FAQ Update',
            'FAQ Delete',
            'FAQ Status Change',
        ]);

        $this->actingAs($user)
            ->post(route('admin.faq.category.store'), ['name' => 'Orders', 'status' => 1])
            ->assertRedirect(route('admin.faq.category.index'));

        $category = FaqCategory::query()->where('name', 'Orders')->firstOrFail();
        $this->assertDatabaseHas('faq_categories', ['id' => $category->id, 'name' => 'Orders']);
        $this->actingAs($user)
            ->get(route('admin.faq.category.index'))
            ->assertOk()
            ->assertSee('Orders');

        $this->actingAs($user)
            ->get(route('admin.faq.category.edit', $category))
            ->assertOk()
            ->assertSee('Orders');

        $this->actingAs($user)
            ->patch(route('admin.faq.category.update', $category), ['name' => 'Orders and Delivery', 'status' => 1])
            ->assertRedirect(route('admin.faq.category.index'));

        $this->actingAs($user)
            ->patch(route('admin.faq.category.status', $category))
            ->assertRedirect();
        $this->assertDatabaseHas('faq_categories', ['id' => $category->id, 'status' => 0]);

        $this->actingAs($user)
            ->post(route('admin.faq.store'), [
                'category_id' => $category->id,
                'question' => 'How do I track an order?',
                'answer' => 'Use the tracking link in your confirmation email.',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.faq.index'));

        $faq = Faq::query()->where('question', 'How do I track an order?')->firstOrFail();

        $this->actingAs($user)
            ->get(route('admin.faq.index', ['category_id' => $category->id, 'status' => 1]))
            ->assertOk()
            ->assertSee('How do I track an order?');

        $this->actingAs($user)
            ->get(route('admin.faq.edit', $faq))
            ->assertOk()
            ->assertSee('How do I track an order?');

        $this->actingAs($user)
            ->patch(route('admin.faq.update', $faq), [
                'category_id' => $category->id,
                'question' => 'Where can I track my order?',
                'answer' => 'Open the tracking link in your confirmation email.',
                'status' => 1,
            ])
            ->assertRedirect(route('admin.faq.index'));

        $this->actingAs($user)
            ->patch(route('admin.faq.status', $faq))
            ->assertRedirect();
        $this->assertDatabaseHas('faqs', ['id' => $faq->id, 'status' => 0]);

        $this->actingAs($user)
            ->delete(route('admin.faq.destroy', $faq))
            ->assertRedirect(route('admin.faq.index'));
        $this->assertDatabaseMissing('faqs', ['id' => $faq->id]);

        $anotherFaq = Faq::create([
            'category_id' => $category->id,
            'question' => 'Do you ship internationally?',
            'answer' => 'Yes, international shipping is available.',
            'status' => 1,
        ]);
        $this->actingAs($user)
            ->delete(route('admin.faq.category.destroy', $category))
            ->assertRedirect(route('admin.faq.category.index'));
        $this->assertDatabaseMissing('faq_categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('faqs', ['id' => $anotherFaq->id]);
    }

    public function test_faq_creation_requires_a_category_question_answer_and_status(): void
    {
        $user = $this->userWithPermissions(['FAQ Add']);

        $this->actingAs($user)
            ->from(route('admin.faq.create'))
            ->post(route('admin.faq.store'), [])
            ->assertRedirect(route('admin.faq.create'))
            ->assertSessionHasErrors(['category_id', 'question', 'answer', 'status']);
    }

    public function test_content_module_routes_require_the_matching_permission(): void
    {
        $user = User::factory()->create(['role' => 1]);

        $this->actingAs($user)
            ->get(route('admin.faq.index'))
            ->assertForbidden();
    }

    /**
     * @param  array<int, string>  $permissionNames
     */
    private function userWithPermissions(array $permissionNames): User
    {
        $user = User::factory()->create(['role' => 1]);

        foreach ($permissionNames as $permissionName) {
            $user->givePermissionTo(Permission::findOrCreate($permissionName, 'web'));
        }

        return $user;
    }

    private function fakePng(string $filename): UploadedFile
    {
        $image = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aXk8AAAAASUVORK5CYII=',
            true,
        );

        $this->assertIsString($image);

        return UploadedFile::fake()->createWithContent($filename, $image);
    }
}
