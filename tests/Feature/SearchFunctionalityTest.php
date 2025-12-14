<?php

namespace Tests\Feature;

use App\Models\Station;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    protected User $driver;
    protected User $admin;
    protected User $owner;

    /**
     * Set up test data before each test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->driver = User::factory()->driver()->create([
            'name' => 'John Driver',
            'email' => 'driver@test.com',
        ]);

        $this->admin = User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
        ]);

        $this->owner = User::factory()->owner()->create([
            'name' => 'Jane Owner',
            'email' => 'owner@test.com',
            'username' => 'janeowner',
        ]);

        // Create searchable stations
        Station::factory()->create([
            'user_id' => $this->owner->id,
            'name' => 'Jakarta Central Station',
            'address' => 'Jl. Sudirman No. 123, Jakarta',
            'is_active' => true,
        ]);

        Station::factory()->create([
            'user_id' => $this->owner->id,
            'name' => 'Bandung Power Hub',
            'address' => 'Jl. Asia Afrika No. 45, Bandung',
            'is_active' => true,
        ]);

        Station::factory()->create([
            'user_id' => $this->owner->id,
            'name' => 'Surabaya Charging Point',
            'address' => 'Jl. Pemuda No. 78, Surabaya',
            'is_active' => true,
        ]);
    }

    /**
     * Test station search by name - successful match
     */
    public function test_station_search_by_name_returns_matching_results(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/home?search=Jakarta');
        
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');
        $response->assertDontSee('Bandung Power Hub');
        $response->assertDontSee('Surabaya Charging Point');
    }

    /**
     * Test station search by address - successful match
     */
    public function test_station_search_by_address_returns_matching_results(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/home?search=Bandung');
        
        $response->assertStatus(200);
        $response->assertSee('Bandung Power Hub');
        $response->assertDontSee('Jakarta Central Station');
    }

    /**
     * Test station search with partial match
     */
    public function test_station_search_with_partial_match_works(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/home?search=Charging');
        
        $response->assertStatus(200);
        $response->assertSee('Surabaya Charging Point');
    }

    /**
     * Test station search with no results
     */
    public function test_station_search_with_no_results_handles_gracefully(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/home?search=NonExistentStationXYZ');
        
        $response->assertStatus(200);
        // Should not crash, even if no results
        $response->assertDontSee('Jakarta Central Station');
        $response->assertDontSee('Bandung Power Hub');
    }

    /**
     * Test station search without query returns all stations
     */
    public function test_station_without_search_query_returns_all_stations(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/home');
        
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');
        $response->assertSee('Bandung Power Hub');
        $response->assertSee('Surabaya Charging Point');
    }

    /**
     * Test admin station search by name
     */
    public function test_admin_station_search_by_name_works(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/add-station?search=Jakarta');
        
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');
        $response->assertDontSee('Bandung Power Hub');
    }

    /**
     * Test admin station search with no results
     */
    public function test_admin_station_search_with_no_results_handles_gracefully(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/add-station?search=NonExistentXYZ');
        
        $response->assertStatus(200);
        // Should handle empty results gracefully
    }

    /**
     * Test admin user search by name
     */
    public function test_admin_user_search_by_name_works(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/user-management?search=Jane');
        
        $response->assertStatus(200);
        $response->assertSee('Jane Owner');
    }

    /**
     * Test admin user search by email
     */
    public function test_admin_user_search_by_email_works(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/user-management?search=owner@test.com');
        
        $response->assertStatus(200);
        $response->assertSee('owner@test.com');
    }

    /**
     * Test admin user search by username
     */
    public function test_admin_user_search_by_username_works(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/user-management?search=janeowner');
        
        $response->assertStatus(200);
        $response->assertSee('janeowner');
    }

    /**
     * Test admin user search with no results
     */
    public function test_admin_user_search_with_no_results_handles_gracefully(): void
    {
        $this->actingAs($this->admin);

        $response = $this->get('/admin/user-management?search=NonExistentUserXYZ');
        
        $response->assertStatus(200);
        // Should not crash with no results
    }

    /**
     * Test search is case-insensitive
     */
    public function test_search_is_case_insensitive(): void
    {
        $this->actingAs($this->driver);

        // Test lowercase
        $response = $this->get('/home?search=jakarta');
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');

        // Test uppercase
        $response = $this->get('/home?search=JAKARTA');
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');

        // Test mixed case
        $response = $this->get('/home?search=JaKaRtA');
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');
    }

    /**
     * Test search with special characters handles gracefully
     */
    public function test_search_with_special_characters_handles_gracefully(): void
    {
        $this->actingAs($this->driver);

        $specialChars = ['<script>', '"; DROP TABLE stations;--', '%', '_'];

        foreach ($specialChars as $char) {
            $response = $this->get('/home?search=' . urlencode($char));
            $response->assertStatus(200);
            // Should not crash or cause SQL injection
        }
    }

    /**
     * Test empty search string returns all results
     */
    public function test_empty_search_string_returns_all_results(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/home?search=');
        
        $response->assertStatus(200);
        $response->assertSee('Jakarta Central Station');
        $response->assertSee('Bandung Power Hub');
    }

    /**
     * Test search with whitespace only
     */
    public function test_search_with_whitespace_only_returns_all_results(): void
    {
        $this->actingAs($this->driver);

        // URL encode the spaces to avoid BadRequestException
        $response = $this->get('/home?search=' . urlencode('   '));
        
        $response->assertStatus(200);
        // Should handle whitespace gracefully and return results
    }
}
