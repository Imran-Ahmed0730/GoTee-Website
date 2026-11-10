<?php

namespace Tests\Feature\Admin;

use App\Models\Admin\CaseStudy;
use App\Models\Admin\Client;
use App\Models\Admin\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PortfolioModulesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var array<int, string>
     */
    private array $createdPublicImages = [];

    protected function tearDown(): void
    {
        foreach ($this->createdPublicImages as $imagePath) {
            if (is_file($imagePath)) {
                unlink($imagePath);
            }
        }

        parent::tearDown();
    }

    public function test_testimonials_support_full_crud_filtering_visibility_and_optional_images(): void
    {
        $user = $this->userWithPermissions([
            'Testimonial Add',
            'Testimonial View',
            'Testimonial Update',
            'Testimonial Delete',
            'Testimonial Status Change',
        ]);

        $this->actingAs($user)
            ->post(route('admin.testimonial.store'), $this->testimonial_input([
                'image' => $this->fakePng('testimonial.png'),
                'last_name' => null,
            ]))
            ->assertRedirect(route('admin.testimonial.index'));

        $testimonial = Testimonial::query()->where('client_name', 'Acme Manufacturing')->firstOrFail();
        $oldImage = $testimonial->image;
        $this->createdPublicImages[] = public_path($oldImage);
        $this->assertSame('Jordan', $testimonial->first_name);
        $this->assertNull($testimonial->last_name);
        $this->assertSame(5, $testimonial->rating);
        $this->assertSame('Show', $testimonial->status);
        $this->assertFileExists(public_path($oldImage));

        $this->actingAs($user)
            ->get(route('admin.testimonial.index', ['search' => 'Acme', 'status' => '1']))
            ->assertOk()
            ->assertSee('Acme Manufacturing');

        $this->actingAs($user)
            ->get(route('admin.testimonial.edit', $testimonial))
            ->assertOk()
            ->assertSee('Acme Manufacturing')
            ->assertSee('Jordan');

        $this->actingAs($user)
            ->patch(route('admin.testimonial.update', $testimonial), $this->testimonial_input([
                'client_name' => 'Acme Industries',
                'first_name' => 'Taylor',
                'last_name' => 'Reed',
                'rating' => 4,
                'image' => $this->fakePng('replacement.png'),
            ]))
            ->assertRedirect(route('admin.testimonial.index'));

        $testimonial->refresh();
        $newImage = $testimonial->image;
        $this->createdPublicImages[] = public_path($newImage);
        $this->assertDatabaseHas('testimonials', [
            'id' => $testimonial->id,
            'client_name' => 'Acme Industries',
            'first_name' => 'Taylor',
            'last_name' => 'Reed',
            'rating' => 4,
        ]);
        $this->assertNotSame($oldImage, $newImage);
        $this->assertFileDoesNotExist(public_path($oldImage));
        $this->assertFileExists(public_path($newImage));

        $this->actingAs($user)
            ->patch(route('admin.testimonial.status', $testimonial))
            ->assertRedirect();
        $this->assertDatabaseHas('testimonials', ['id' => $testimonial->id, 'status' => 0]);
        $this->assertSame('Hidden', $testimonial->refresh()->status);

        $this->actingAs($user)
            ->delete(route('admin.testimonial.destroy', $testimonial))
            ->assertRedirect(route('admin.testimonial.index'));
        $this->assertDatabaseMissing('testimonials', ['id' => $testimonial->id]);
        $this->assertFileDoesNotExist(public_path($newImage));
    }

    public function test_testimonial_rating_must_be_between_one_and_five(): void
    {
        $user = $this->userWithPermissions(['Testimonial Add']);

        $this->actingAs($user)
            ->from(route('admin.testimonial.create'))
            ->post(route('admin.testimonial.store'), $this->testimonial_input(['rating' => 6]))
            ->assertRedirect(route('admin.testimonial.create'))
            ->assertSessionHasErrors(['rating']);

        $this->assertDatabaseCount('testimonials', 0);
    }

    public function test_testimonial_can_be_created_without_an_optional_image(): void
    {
        $user = $this->userWithPermissions(['Testimonial Add']);

        $this->actingAs($user)
            ->post(route('admin.testimonial.store'), $this->testimonial_input(['image' => null]))
            ->assertRedirect(route('admin.testimonial.index'));

        $this->assertDatabaseHas('testimonials', [
            'client_name' => 'Acme Manufacturing',
            'image' => null,
        ]);
    }

    public function test_testimonial_and_case_study_status_accessors_and_mutators_map_show_and_hidden_values(): void
    {
        $testimonial = Testimonial::create([
            ...$this->testimonial_input(),
            'status' => 'Hidden',
        ]);
        $caseStudy = CaseStudy::create([
            ...$this->caseStudyInput(),
            'status' => 'Show',
        ]);

        $this->assertSame('Hidden', $testimonial->status);
        $this->assertSame(0, $testimonial->getRawOriginal('status'));
        $this->assertSame('Show', $caseStudy->status);
        $this->assertSame(1, $caseStudy->getRawOriginal('status'));
    }

    public function test_case_studies_support_relations_filtering_status_and_full_crud(): void
    {
        $user = $this->userWithPermissions([
            'Case Study Add',
            'Case Study View',
            'Case Study Update',
            'Case Study Delete',
            'Case Study Status Change',
        ]);
        $client = Client::create(['name' => 'Northwind Textiles', 'status' => 1]);
        $testimonial = Testimonial::create($this->testimonial_input());

        $this->actingAs($user)
            ->post(route('admin.case-study.store'), $this->caseStudyInput([
                'client_id' => $client->id,
                'testimonial_id' => $testimonial->id,
            ]))
            ->assertRedirect(route('admin.case-study.index'));

        $caseStudy = CaseStudy::query()->where('title', 'Production workflow modernization')->firstOrFail();
        $this->assertTrue($caseStudy->client->is($client));
        $this->assertTrue($caseStudy->testimonial->is($testimonial));
        $this->assertSame('Hidden', $caseStudy->status);

        $this->actingAs($user)
            ->get(route('admin.case-study.index', ['search' => 'Northwind', 'status' => '0']))
            ->assertOk()
            ->assertSee('Production workflow modernization');

        $this->actingAs($user)
            ->get(route('admin.case-study.edit', $caseStudy))
            ->assertOk()
            ->assertSee('Northwind Textiles')
            ->assertSee('Production workflow modernization');

        $this->actingAs($user)
            ->patch(route('admin.case-study.update', $caseStudy), $this->caseStudyInput([
                'client_id' => $client->id,
                'testimonial_id' => null,
                'title' => 'Modernized production delivery',
                'status' => 1,
            ]))
            ->assertRedirect(route('admin.case-study.index'));

        $caseStudy->refresh();
        $this->assertDatabaseHas('case_studies', [
            'id' => $caseStudy->id,
            'title' => 'Modernized production delivery',
            'testimonial_id' => null,
            'status' => 1,
        ]);

        $this->actingAs($user)
            ->patch(route('admin.case-study.status', $caseStudy))
            ->assertRedirect();
        $this->assertDatabaseHas('case_studies', ['id' => $caseStudy->id, 'status' => 0]);
        $this->assertSame('Hidden', $caseStudy->refresh()->status);

        $this->actingAs($user)
            ->delete(route('admin.case-study.destroy', $caseStudy))
            ->assertRedirect(route('admin.case-study.index'));
        $this->assertDatabaseMissing('case_studies', ['id' => $caseStudy->id]);
    }

    public function test_case_study_requires_existing_client_and_testimonial_relations(): void
    {
        $user = $this->userWithPermissions(['Case Study Add']);

        $this->actingAs($user)
            ->from(route('admin.case-study.create'))
            ->post(route('admin.case-study.store'), $this->caseStudyInput([
                'client_id' => 999,
                'testimonial_id' => 999,
            ]))
            ->assertRedirect(route('admin.case-study.create'))
            ->assertSessionHasErrors(['client_id', 'testimonial_id']);

        $this->assertDatabaseCount('case_studies', 0);
    }

    public function test_case_study_can_be_created_without_optional_client_or_testimonial(): void
    {
        $user = $this->userWithPermissions(['Case Study Add']);

        $this->actingAs($user)
            ->post(route('admin.case-study.store'), $this->caseStudyInput())
            ->assertRedirect(route('admin.case-study.index'));

        $this->assertDatabaseHas('case_studies', [
            'title' => 'Production workflow modernization',
            'client_id' => null,
            'testimonial_id' => null,
            'status' => 0,
        ]);
    }

    public function test_deleting_a_client_or_testimonial_preserves_case_studies_and_nulls_the_optional_relations(): void
    {
        $client = Client::create(['name' => 'Partner', 'status' => 1]);
        $testimonial = Testimonial::create($this->testimonial_input());
        $caseStudy = CaseStudy::create([
            ...$this->caseStudyInput(),
            'client_id' => $client->id,
            'testimonial_id' => $testimonial->id,
        ]);

        $client->delete();
        $testimonial->delete();

        $this->assertDatabaseHas('case_studies', [
            'id' => $caseStudy->id,
            'client_id' => null,
            'testimonial_id' => null,
        ]);
    }

    public function test_testimonial_and_case_study_routes_require_their_view_permissions(): void
    {
        $user = User::factory()->create(['role' => 1]);

        $this->actingAs($user)
            ->get(route('admin.testimonial.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.case-study.index'))
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

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function testimonial_input(array $overrides = []): array
    {
        return array_replace([
            'client_name' => 'Acme Manufacturing',
            'first_name' => 'Jordan',
            'last_name' => 'Lee',
            'designation' => 'Operations Director',
            'comment' => 'The updated workflow improved our delivery process.',
            'rating' => 5,
            'image' => null,
            'status' => 1,
        ], $overrides);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function caseStudyInput(array $overrides = []): array
    {
        return array_replace([
            'client_id' => null,
            'testimonial_id' => null,
            'title' => 'Production workflow modernization',
            'industry' => 'Textile manufacturing',
            'location' => 'Dhaka, Bangladesh',
            'challenge' => 'Production data was spread across disconnected tools.',
            'solution' => 'A unified production workflow and reporting platform.',
            'results' => 'Reduced manual production reporting by 40%.',
            'status' => 0,
        ], $overrides);
    }
}
