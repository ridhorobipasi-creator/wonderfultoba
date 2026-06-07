<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OutboundQuoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can submit outbound quote request.
     */
    public function test_user_can_submit_outbound_quote_request()
    {
        // 1. Setup: Create necessary settings
        Setting::create([
            'key' => 'general',
            'value' => ['whatsapp' => '6281260460461'],
        ]);

        // 2. Action: Submit the form
        $response = $this->post(route('outbound.quote.submit'), [
            'company_name' => 'PT Testing Indonesia',
            'participants' => '50',
            'location' => 'Parapat',
            'activity_type' => 'Team Building',
            'estimated_date' => '2026-12-01',
            'whatsapp' => '08123456789',
        ]);

        // 3. Assert: Check redirect and session
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHas('success');
        $response->assertSessionHas('whatsappUrl');

        $waUrl = session('whatsappUrl');
        $this->assertStringContainsString('wa.me/6281260460461', $waUrl);
        $this->assertStringContainsString('PT+Testing+Indonesia', $waUrl);
    }

    /**
     * Test quote submission requires validation.
     */
    public function test_quote_submission_requires_validation()
    {
        // Action: Submit empty form
        $response = $this->post(route('outbound.quote.submit'), []);

        // Assert: Validation errors
        $response->assertSessionHasErrors(['company_name', 'participants', 'location', 'whatsapp']);
    }
}
