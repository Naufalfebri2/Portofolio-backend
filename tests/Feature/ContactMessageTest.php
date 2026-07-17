<?php

namespace Tests\Feature;

use App\Mail\NewContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // The /api/messages route is rate-limited (5/min) in production,
        // which is exactly what we want for real traffic — but it would
        // make several tests in this class interfere with each other
        // when run back-to-back. Disable it for these tests specifically.
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_valid_submission_creates_message_and_sends_email(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/messages', [
            'name' => 'Jane Recruiter',
            'email' => 'jane@example.com',
            'company' => 'Acme Corp',
            'subject' => 'Interview',
            'event_date' => now()->addDays(3)->format('Y-m-d'),
            'message' => 'We would like to schedule an interview with you.',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'whatsapp_url']);

        $this->assertDatabaseHas('messages', [
            'name' => 'Jane Recruiter',
            'email' => 'jane@example.com',
        ]);

        Mail::assertSent(NewContactMessage::class);
    }

    public function test_event_date_is_required(): void
    {
        $response = $this->postJson('/api/messages', [
            'name' => 'Jane Recruiter',
            'email' => 'jane@example.com',
            'message' => 'Missing the event date on purpose.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['event_date']);
    }

    public function test_event_date_cannot_be_in_the_past(): void
    {
        $response = $this->postJson('/api/messages', [
            'name' => 'Jane Recruiter',
            'email' => 'jane@example.com',
            'event_date' => now()->subDay()->format('Y-m-d'),
            'message' => 'This date is already in the past.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['event_date']);
    }

    public function test_honeypot_field_silently_blocks_submission(): void
    {
        Mail::fake();

        $response = $this->postJson('/api/messages', [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'event_date' => now()->addDay()->format('Y-m-d'),
            'message' => 'This is an automated spam submission.',
            'website' => 'http://spam-site.com',
        ]);

        // The bot still gets a 201 "success" response — it's never told
        // it was caught — but nothing is actually persisted or emailed.
        $response->assertStatus(201);

        $this->assertDatabaseMissing('messages', [
            'email' => 'bot@example.com',
        ]);

        Mail::assertNotSent(NewContactMessage::class);
    }

    public function test_message_must_be_at_least_ten_characters(): void
    {
        $response = $this->postJson('/api/messages', [
            'name' => 'Jane Recruiter',
            'email' => 'jane@example.com',
            'event_date' => now()->addDay()->format('Y-m-d'),
            'message' => 'Too short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['message']);
    }
}
