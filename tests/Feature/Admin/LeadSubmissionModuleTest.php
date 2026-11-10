<?php

namespace Tests\Feature\Admin;

use App\Mail\FormSubmissionMail;
use App\Models\Admin\Contact;
use App\Models\Admin\DemoRequest;
use App\Models\Admin\Setting;
use App\Models\Admin\Subscribe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LeadSubmissionModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_is_saved_and_duplicate_emails_are_rejected(): void
    {
        $this->from('/')
            ->post(route('subscribe.store'), ['email' => 'subscriber@example.com'])
            ->assertRedirect('/')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('subscribes', ['email' => 'subscriber@example.com']);

        $this->from('/')
            ->post(route('subscribe.store'), ['email' => 'not-an-email'])
            ->assertSessionHasErrors('email');

        $this->from('/')
            ->post(route('subscribe.store'), ['email' => 'subscriber@example.com'])
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('subscribes', 1);
    }

    public function test_contact_submission_is_saved_and_emailed_to_submitter_and_receiver(): void
    {
        Mail::fake();
        Setting::query()->create(['key' => 'email', 'value' => 'receiver@example.com']);

        $this->from('/')
            ->post(route('contact.store'), [
                'first_name' => 'Taylor',
                'last_name' => 'Smith',
                'email' => 'sender@example.com',
                'phone' => '+1 555 0100',
                'company' => 'Example Co',
                'note' => '<script>alert("x")</script>',
            ])
            ->assertRedirect('/')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('contacts', [
            'first_name' => 'Taylor',
            'email' => 'sender@example.com',
            'company' => 'Example Co',
        ]);
        Mail::assertSent(FormSubmissionMail::class, 2);
        Mail::assertSent(FormSubmissionMail::class, fn (FormSubmissionMail $mail): bool => $mail->isConfirmation
            && $mail->hasTo('sender@example.com'));
        Mail::assertSent(FormSubmissionMail::class, fn (FormSubmissionMail $mail): bool => ! $mail->isConfirmation
            && $mail->hasTo('receiver@example.com'));
        Mail::assertSent(FormSubmissionMail::class, function (FormSubmissionMail $mail): bool {
            if (! $mail->isConfirmation || ! $mail->hasTo('sender@example.com')) {
                return false;
            }

            $rendered = $mail->render();

            $this->assertStringContainsString('&lt;script&gt;', $rendered);
            $this->assertStringNotContainsString('<script>alert("x")</script>', $rendered);

            return true;
        });
    }

    public function test_demo_request_is_saved_and_emailed_to_submitter_and_receiver(): void
    {
        Mail::fake();
        Setting::query()->create(['key' => 'email', 'value' => 'receiver@example.com']);

        $this->from('/')
            ->post(route('request.demo.send'), [
                'company' => 'Example Co',
                'contacting_person_first_name' => 'Jordan',
                'contacting_person_last_name' => 'Lee',
                'email' => 'sender@example.com',
                'phone' => '+1 555 0100',
                'address' => '123 Example Street',
                'machine_qty' => 4,
                'status' => 3,
            ])
            ->assertRedirect('/')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('demo_requests', [
            'company' => 'Example Co',
            'email' => 'sender@example.com',
            'machine_qty' => 4,
            'status' => 0,
        ]);
        Mail::assertSent(FormSubmissionMail::class, 2);
        Mail::assertSent(FormSubmissionMail::class, fn (FormSubmissionMail $mail): bool => $mail->isConfirmation
            && $mail->hasTo('sender@example.com'));
        Mail::assertSent(FormSubmissionMail::class, fn (FormSubmissionMail $mail): bool => ! $mail->isConfirmation
            && $mail->hasTo('receiver@example.com'));
    }

    public function test_contact_and_demo_requests_reject_missing_required_fields_without_sending_mail(): void
    {
        Mail::fake();

        $this->from('/')
            ->post(route('contact.store'), [])
            ->assertSessionHasErrors(['first_name', 'email', 'phone', 'company', 'note']);

        $this->from('/')
            ->post(route('request.demo.send'), [])
            ->assertSessionHasErrors([
                'company',
                'contacting_person_first_name',
                'email',
                'phone',
                'address',
                'machine_qty',
            ]);

        $this->assertDatabaseCount('contacts', 0);
        $this->assertDatabaseCount('demo_requests', 0);
        Mail::assertNothingSent();
    }

    public function test_demo_request_rejects_non_positive_machine_quantities(): void
    {
        Mail::fake();

        $this->from('/')
            ->post(route('request.demo.send'), [
                'company' => 'Example Co',
                'contacting_person_first_name' => 'Jordan',
                'email' => 'sender@example.com',
                'phone' => '555-0100',
                'address' => '123 Example Street',
                'machine_qty' => 0,
            ])
            ->assertSessionHasErrors('machine_qty');

        $this->assertDatabaseCount('demo_requests', 0);
        Mail::assertNothingSent();
    }

    public function test_admin_submission_lists_require_their_matching_permissions(): void
    {
        $user = User::factory()->create(['role' => 1]);

        $this->actingAs($user)
            ->get(route('admin.subscribe.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.contact.index'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.demo-request.index'))
            ->assertForbidden();
    }

    public function test_admin_submission_lists_are_paginated(): void
    {
        $user = User::factory()->create(['role' => 1]);
        $user->givePermissionTo([
            Permission::findOrCreate('Subscribe View', 'web'),
            Permission::findOrCreate('Contact View', 'web'),
            Permission::findOrCreate('Demo Request View', 'web'),
        ]);

        for ($index = 0; $index < 16; $index++) {
            Subscribe::query()->create(['email' => "subscriber{$index}@example.com"]);
            Contact::query()->create([
                'first_name' => "Contact{$index}",
                'email' => "contact{$index}@example.com",
                'phone' => '555-0100',
                'company' => 'Example Co',
                'note' => 'Contact message',
            ]);
            DemoRequest::query()->create([
                'company' => 'Example Co',
                'contacting_person_first_name' => "Person{$index}",
                'email' => "demo{$index}@example.com",
                'phone' => '555-0100',
                'address' => '123 Example Street',
                'machine_qty' => 1,
            ]);
        }

        $this->actingAs($user)
            ->get(route('admin.subscribe.index'))
            ->assertOk()
            ->assertSee('subscriber15@example.com')
            ->assertDontSee('subscriber0@example.com')
            ->assertSee('page=2');

        $this->actingAs($user)
            ->get(route('admin.contact.index'))
            ->assertOk()
            ->assertSee('contact15@example.com')
            ->assertDontSee('contact0@example.com')
            ->assertSee('page=2');

        $this->actingAs($user)
            ->get(route('admin.demo-request.index'))
            ->assertOk()
            ->assertSee('demo15@example.com')
            ->assertDontSee('demo0@example.com')
            ->assertSee('page=2');
    }

    public function test_guest_is_redirected_from_admin_submission_lists(): void
    {
        $this->get(route('admin.subscribe.index'))
            ->assertRedirect(route('login'));
    }
}
