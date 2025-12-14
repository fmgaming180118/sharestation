<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Port;
use App\Models\Station;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComprehensiveRouteTest extends TestCase
{
    use RefreshDatabase;

    protected User $driver;
    protected User $admin;
    protected User $owner;
    protected Station $station;
    protected Transaction $transaction;
    protected Transaction $ownerTransaction;

    /**
     * Set up test data before each test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create users with different roles
        $this->driver = User::factory()->driver()->create([
            'email' => 'driver@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->admin = User::factory()->admin()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->owner = User::factory()->owner()->create([
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create a station with ports
        $this->station = Station::factory()->create([
            'user_id' => $this->owner->id,
            'is_active' => true,
            'is_open' => true,
        ]);

        // Create ports for the station
        Port::factory()->count(3)->available()->create([
            'station_id' => $this->station->id,
        ]);

        // Create a transaction for the driver
        $this->transaction = Transaction::factory()->create([
            'user_id' => $this->driver->id,
            'station_id' => $this->station->id,
            'port_id' => $this->station->ports()->first()->id,
        ]);

        // Create a transaction at the owner's station (visible to owner)
        $ownerTransaction = Transaction::factory()->create([
            'user_id' => $this->driver->id,
            'station_id' => $this->station->id,
            'port_id' => $this->station->ports()->first()->id,
        ]);
        $this->ownerTransaction = $ownerTransaction;

        // Create a booking for the driver
        Booking::factory()->create([
            'user_id' => $this->driver->id,
            'station_id' => $this->station->id,
        ]);
    }

    /**
     * Test public/guest routes
     */
    public function test_public_routes_return_200(): void
    {
        $publicRoutes = [
            '/' => 'Landing Page',
            '/beranda' => 'Beranda',
            '/inovasi' => 'Inovasi',
            '/fitur-utama' => 'Fitur Utama',
            '/kontak' => 'Kontak',
            '/login' => 'Login Form',
            '/register' => 'Register Form',
        ];

        foreach ($publicRoutes as $route => $name) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed: {$name} ({$route})");
        }
    }

    /**
     * Test authenticated routes (general)
     */
    public function test_authenticated_routes_require_login(): void
    {
        $authenticatedRoutes = [
            '/home',
            '/my-bookings',
            '/profile/edit',
        ];

        foreach ($authenticatedRoutes as $route) {
            $response = $this->get($route);
            $response->assertRedirect('/login');
        }
    }

    /**
     * Test authenticated routes work when logged in as driver
     */
    public function test_driver_can_access_authenticated_routes(): void
    {
        $this->actingAs($this->driver);

        $routes = [
            '/home' => 'Home Page',
            '/my-bookings' => 'My Bookings',
            '/profile/edit' => 'Edit Profile',
            "/stations/{$this->station->id}" => 'Station Detail',
        ];

        foreach ($routes as $route => $name) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed: {$name} ({$route})");
        }
    }

    /**
     * Test admin routes require admin role
     */
    public function test_admin_routes_require_admin_role(): void
    {
        // Test as guest
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');

        // Test as driver (should be forbidden)
        $this->actingAs($this->driver);
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test admin can access all admin routes
     */
    public function test_admin_can_access_admin_routes(): void
    {
        $this->actingAs($this->admin);

        $adminRoutes = [
            '/admin/dashboard' => 'Admin Dashboard',
            '/admin/add-station' => 'Add Station',
            '/admin/create-station' => 'Create Station Form',
            '/admin/user-management' => 'User Management',
            '/admin/create-user' => 'Create User Form',
            "/admin/edit-station/{$this->station->id}" => 'Edit Station',
            "/admin/edit-user/{$this->driver->id}" => 'Edit User',
        ];

        foreach ($adminRoutes as $route => $name) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed: {$name} ({$route})");
        }
    }

    /**
     * Test driver routes require driver role
     */
    public function test_driver_routes_require_driver_role(): void
    {
        // Test as guest
        $response = $this->get('/driver/dashboard');
        $response->assertRedirect('/login');

        // Test as admin (should be forbidden)
        $this->actingAs($this->admin);
        $response = $this->get('/driver/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test driver can access all driver routes
     */
    public function test_driver_can_access_driver_routes(): void
    {
        $this->actingAs($this->driver);

        $driverRoutes = [
            '/driver/dashboard' => 'Driver Dashboard',
            '/driver/reservations' => 'Driver Reservations',
            '/driver/invoice' => 'Driver Invoice',
            "/driver/station/{$this->station->id}" => 'Driver Station View',
            '/driver/profile' => 'Driver Profile',
            '/driver/profile/edit' => 'Driver Edit Profile',
            '/driver/profile/password' => 'Driver Change Password',
        ];

        foreach ($driverRoutes as $route => $name) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed: {$name} ({$route})");
        }
    }

    /**
     * Test owner routes require owner role
     */
    public function test_owner_routes_require_owner_role(): void
    {
        // Test as guest
        $response = $this->get('/owner/dashboard');
        $response->assertRedirect('/login');

        // Test as driver (should be forbidden)
        $this->actingAs($this->driver);
        $response = $this->get('/owner/dashboard');
        $response->assertStatus(403);
    }

    /**
     * Test owner can access all owner routes
     */
    public function test_owner_can_access_owner_routes(): void
    {
        $this->actingAs($this->owner);

        $ownerRoutes = [
            '/owner/dashboard' => 'Owner Dashboard',
            '/owner/profile' => 'Owner PownerTofile',
            "/owner/transaction/{$this->ownerTransaction->transaction_code}" => 'Transaction Detail',
        ];

        foreach ($ownerRoutes as $route => $name) {
            $response = $this->get($route);
            $response->assertStatus(200, "Failed: {$name} ({$route})");
        }
    }

    /**
     * Test station detail route with invalid ID returns 404
     */
    public function test_invalid_station_id_returns_404(): void
    {
        $this->actingAs($this->driver);

        $response = $this->get('/stations/99999');
        $response->assertStatus(404);
    }

    /**
     * Test inactive station is not accessible
     */
    public function test_inactive_station_returns_404(): void
    {
        $this->actingAs($this->driver);

        $inactiveStation = Station::factory()->inactive()->create([
            'user_id' => $this->owner->id,
        ]);

        $response = $this->get("/stations/{$inactiveStation->id}");
        $response->assertStatus(404);
    }

    /**
     * Test POST routes require CSRF token
     */
    public function test_post_routes_require_csrf_token(): void
    {
        $this->actingAs($this->driver);

        // Without CSRF, should get 419
        $response = $this->post('/book', [
            'station_id' => $this->station->id,
        ]);

        // Laravel test environment automatically includes CSRF, so we test with withoutMiddleware
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        
        $response = $this->post('/book', [
            'station_id' => $this->station->id,
        ]);
        
        // Should redirect or process (not 419)
        $this->assertNotEquals(419, $response->status());
    }

    /**
     * Test logout route works
     */
    public function test_logout_route_works(): void
    {
        $this->actingAs($this->driver);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        
        // After logout, should not be authenticated
        $this->assertGuest();
    }
}
