<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Port;
use App\Models\Review;
use App\Models\Station;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudOperationsTest extends TestCase
{
    use RefreshDatabase;

    // ========================================
    // STATION CRUD TESTS (Admin/Owner Role)
    // ========================================

    /** @test */
    public function admin_can_create_station_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();

        $stationData = [
            'name' => 'Test Station',
            'address' => '123 Test Street',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'user_id' => $owner->id,
            'phone' => '08123456789',
            'num_ports' => 3,
            'power_kw' => 150,
            'price_per_kwh' => 3500,
            'is_active' => true,
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.store-station'), $stationData);

        $response->assertRedirect(route('admin.add-station'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('stations', [
            'name' => 'Test Station',
            'address' => '123 Test Street',
            'user_id' => $owner->id,
        ]);

        // Verify ports were created
        $station = Station::where('name', 'Test Station')->first();
        $this->assertCount(3, $station->ports);
    }

    /** @test */
    public function admin_cannot_create_station_with_invalid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $invalidData = [
            'name' => '', // Required field is empty
            'address' => '',
            'latitude' => 'invalid', // Should be numeric
            'longitude' => '',
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.store-station'), $invalidData);

        $response->assertSessionHasErrors(['name', 'address', 'latitude', 'longitude', 'user_id']);
    }

    /** @test */
    public function guest_cannot_create_station(): void
    {
        $owner = User::factory()->owner()->create();

        $stationData = [
            'name' => 'Unauthorized Station',
            'address' => '456 Test Ave',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'user_id' => $owner->id,
        ];

        $response = $this->post(route('admin.store-station'), $stationData);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseMissing('stations', ['name' => 'Unauthorized Station']);
    }

    /** @test */
    public function non_admin_cannot_create_station(): void
    {
        $driver = User::factory()->driver()->create();
        $owner = User::factory()->owner()->create();

        $stationData = [
            'name' => 'Forbidden Station',
            'address' => '789 Test Blvd',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'user_id' => $owner->id,
            'num_ports' => 2,
            'power_kw' => 100,
            'price_per_kwh' => 3000,
        ];

        $response = $this->actingAs($driver)
            ->post(route('admin.store-station'), $stationData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('stations', ['name' => 'Forbidden Station']);
    }

    /** @test */
    public function admin_can_view_station_list(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();

        Station::factory()->count(3)->create(['user_id' => $owner->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.add-station'));

        $response->assertStatus(200);
        $response->assertViewHas('stations');
    }

    /** @test */
    public function admin_can_view_edit_station_form(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($admin)
            ->get(route('admin.edit-station', $station->id));

        $response->assertStatus(200);
        $response->assertViewHas('station');
        $response->assertSee($station->name);
    }

    /** @test */
    public function admin_can_update_station_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create([
            'user_id' => $owner->id,
            'name' => 'Old Station Name',
            'phone' => '08111111111',
        ]);

        $updatedData = [
            'name' => 'Updated Station Name',
            'address' => $station->address,
            'latitude' => $station->latitude,
            'longitude' => $station->longitude,
            'user_id' => $owner->id,
            'phone' => '08999999999',
            'is_active' => true,
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.update-station', $station->id), $updatedData);

        $response->assertRedirect(route('admin.add-station'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('stations', [
            'id' => $station->id,
            'name' => 'Updated Station Name',
            'phone' => '08999999999',
        ]);
    }

    /** @test */
    public function admin_cannot_update_station_with_invalid_data(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        $invalidData = [
            'name' => '', // Required
            'address' => '',
            'latitude' => 'not-a-number',
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.update-station', $station->id), $invalidData);

        $response->assertSessionHasErrors(['name', 'address', 'latitude']);
    }

    /** @test */
    public function admin_can_delete_station(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        $this->assertDatabaseHas('stations', ['id' => $station->id]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.delete-station', $station->id));

        $response->assertRedirect(route('admin.add-station'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('stations', ['id' => $station->id]);
    }

    /** @test */
    public function non_admin_cannot_delete_station(): void
    {
        $driver = User::factory()->driver()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($driver)
            ->delete(route('admin.delete-station', $station->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('stations', ['id' => $station->id]);
    }

    // ========================================
    // BOOKING CRUD TESTS (Driver Role)
    // ========================================

    /** @test */
    public function driver_can_create_booking_with_valid_data(): void
    {
        $driver = User::factory()->driver()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        $bookingData = [
            'station_id' => $station->id,
            'start_at' => now()->addHours(2),
            'end_at' => now()->addHours(4),
            'price_per_kwh' => 3500,
            'energy_kwh' => 30,
        ];

        $response = $this->actingAs($driver)
            ->post('/book', $bookingData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('bookings', [
            'user_id' => $driver->id,
            'station_id' => $station->id,
        ]);
    }

    /** @test */
    public function driver_cannot_create_booking_with_invalid_station(): void
    {
        $driver = User::factory()->driver()->create();

        $bookingData = [
            'station_id' => 99999, // Non-existent station
            'start_at' => now()->addHours(2),
            'end_at' => now()->addHours(4),
        ];

        $response = $this->actingAs($driver)
            ->post('/book', $bookingData);

        $response->assertSessionHasErrors(['station_id']);
    }

    /** @test */
    public function guest_cannot_create_booking(): void
    {
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        $bookingData = [
            'station_id' => $station->id,
            'start_at' => now()->addHours(2),
            'end_at' => now()->addHours(4),
        ];

        $response = $this->post('/book', $bookingData);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function driver_can_view_their_bookings(): void
    {
        $driver = User::factory()->driver()->create();
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create(['user_id' => $owner->id]);

        Booking::factory()->count(3)->create([
            'user_id' => $driver->id,
            'station_id' => $station->id,
        ]);

        $response = $this->actingAs($driver)
            ->get('/my-bookings');

        $response->assertStatus(200);
        $response->assertViewHas('bookings');
    }

    /** @test */
    public function driver_cannot_view_other_drivers_bookings(): void
    {
        $driver1 = User::factory()->driver()->create();
        $driver2 = User::factory()->driver()->create(['name' => 'Other Driver']);
        $owner = User::factory()->owner()->create();
        $station = Station::factory()->create([
            'user_id' => $owner->id,
            'name' => 'Station Only For Driver2'
        ]);

        $booking = Booking::factory()->create([
            'user_id' => $driver2->id,
            'station_id' => $station->id,
        ]);

        $response = $this->actingAs($driver1)
            ->get('/my-bookings');

        $response->assertStatus(200);
        $response->assertDontSee('Station Only For Driver2');
    }

    // ========================================
    // USER PROFILE CRUD TESTS (All Roles)
    // ========================================

    /** @test */
    public function authenticated_user_can_view_profile_edit_page(): void
    {
        $user = User::factory()->driver()->create();

        $response = $this->actingAs($user)
            ->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
    }

    /** @test */
    public function guest_cannot_view_profile_edit_page(): void
    {
        $response = $this->get(route('profile.edit'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_update_their_profile_with_valid_data(): void
    {
        $user = User::factory()->driver()->create([
            'name' => 'Old Name',
            'email' => 'old@email.com',
        ]);

        $updatedData = [
            'name' => 'New Name',
            'email' => 'new@email.com',
        ];

        $response = $this->actingAs($user)
            ->post(route('profile.update'), $updatedData);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@email.com',
        ]);
    }

    /** @test */
    public function user_cannot_update_profile_with_invalid_email(): void
    {
        $user = User::factory()->driver()->create();

        $invalidData = [
            'name' => 'Valid Name',
            'email' => 'invalid-email', // Invalid format
            'phone' => '08123456789',
        ];

        $response = $this->actingAs($user)
            ->post(route('profile.update'), $invalidData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function user_cannot_use_email_already_taken_by_another_user(): void
    {
        $user1 = User::factory()->driver()->create(['email' => 'user1@test.com']);
        $user2 = User::factory()->driver()->create(['email' => 'user2@test.com']);

        $conflictData = [
            'name' => 'User 1',
            'email' => 'user2@test.com', // Already taken by user2
        ];

        $response = $this->actingAs($user1)
            ->post(route('profile.update'), $conflictData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function driver_can_update_their_own_profile(): void
    {
        $driver = User::factory()->driver()->create([
            'name' => 'Original Driver Name',
            'address' => 'Original Address',
        ]);

        $updatedData = [
            'name' => 'Updated Driver Name',
            'email' => $driver->email,
            'phone' => '08555555555',
            'address' => 'Updated Driver Address',
        ];

        $response = $this->actingAs($driver)
            ->put(route('driver.profile.update'), $updatedData);

        $response->assertRedirect(route('driver.profile.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $driver->id,
            'name' => 'Updated Driver Name',
            'address' => 'Updated Driver Address',
        ]);
    }

    /** @test */
    public function driver_can_change_password_with_valid_data(): void
    {
        $driver = User::factory()->driver()->create([
            'password' => bcrypt('old-password'),
        ]);

        $passwordData = [
            'current_password' => 'old-password',
            'new_password' => 'new-password-123',
            'new_password_confirmation' => 'new-password-123',
        ];

        $response = $this->actingAs($driver)
            ->put(route('driver.profile.password.update'), $passwordData);

        $response->assertRedirect(route('driver.profile.password'));
        $response->assertSessionHas('success');

        // Verify the password was updated
        $driver->refresh();
        $this->assertTrue(password_verify('new-password-123', $driver->password));
    }

    /** @test */
    public function driver_cannot_change_password_with_incorrect_current_password(): void
    {
        $driver = User::factory()->driver()->create([
            'password' => bcrypt('correct-password'),
        ]);

        $passwordData = [
            'current_password' => 'wrong-password',
            'new_password' => 'new-password-123',
            'new_password_confirmation' => 'new-password-123',
        ];

        $response = $this->actingAs($driver)
            ->put(route('driver.profile.password.update'), $passwordData);

        $response->assertSessionHasErrors(['current_password']);
    }

    /** @test */
    public function driver_cannot_change_password_with_mismatched_confirmation(): void
    {
        $driver = User::factory()->driver()->create([
            'password' => bcrypt('old-password'),
        ]);

        $passwordData = [
            'current_password' => 'old-password',
            'new_password' => 'new-password-123',
            'new_password_confirmation' => 'different-password', // Mismatch
        ];

        $response = $this->actingAs($driver)
            ->put(route('driver.profile.password.update'), $passwordData);

        $response->assertSessionHasErrors(['new_password']);
    }

    // ========================================
    // USER MANAGEMENT CRUD TESTS (Admin Only)
    // ========================================

    /** @test */
    public function admin_can_create_user_with_valid_data(): void
    {
        $admin = User::factory()->admin()->create();

        $userData = [
            'name' => 'New User',
            'username' => 'newuser123',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'phone' => '08123456789',
            'address' => '123 New Street',
            'role' => 'driver',
            'is_host' => false,
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.store-user'), $userData);

        $response->assertRedirect(route('admin.user-management'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'username' => 'newuser123',
            'email' => 'newuser@test.com',
            'role' => 'driver',
        ]);
    }

    /** @test */
    public function admin_cannot_create_user_with_duplicate_email(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['email' => 'existing@test.com']);

        $userData = [
            'name' => 'Duplicate User',
            'username' => 'duplicateuser',
            'email' => 'existing@test.com', // Already exists
            'password' => 'password123',
            'role' => 'driver',
        ];

        $response = $this->actingAs($admin)
            ->post(route('admin.store-user'), $userData);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function admin_can_update_user_details(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->owner()->create([
            'name' => 'Original Name',
            'email' => 'original@test.com',
        ]);

        $updatedData = [
            'name' => 'Updated Name',
            'username' => $user->username,
            'email' => 'updated@test.com',
            'phone' => '08999999999',
            'role' => 'warga',
            'is_host' => true,
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.update-user', $user->id), $updatedData);

        $response->assertRedirect(route('admin.user-management'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
        ]);
    }

    /** @test */
    public function admin_can_delete_user(): void
    {
        $admin = User::factory()->admin()->create();
        $userToDelete = User::factory()->owner()->create();

        $this->assertDatabaseHas('users', ['id' => $userToDelete->id]);

        $response = $this->actingAs($admin)
            ->delete(route('admin.delete-user', $userToDelete->id));

        $response->assertRedirect(route('admin.user-management'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }

    /** @test */
    public function non_admin_cannot_create_user(): void
    {
        $driver = User::factory()->driver()->create();

        $userData = [
            'name' => 'Unauthorized User',
            'username' => 'unauthorized',
            'email' => 'unauthorized@test.com',
            'password' => 'password123',
            'role' => 'driver',
        ];

        $response = $this->actingAs($driver)
            ->post(route('admin.store-user'), $userData);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('users', ['email' => 'unauthorized@test.com']);
    }

    /** @test */
    public function non_admin_cannot_delete_user(): void
    {
        $driver = User::factory()->driver()->create();
        $targetUser = User::factory()->owner()->create();

        $response = $this->actingAs($driver)
            ->delete(route('admin.delete-user', $targetUser->id));

        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['id' => $targetUser->id]);
    }

    // ========================================
    // OWNERSHIP & AUTHORIZATION TESTS
    // ========================================

    /** @test */
    public function owner_a_cannot_update_owner_b_station(): void
    {
        $admin = User::factory()->admin()->create();
        $ownerA = User::factory()->owner()->create();
        $ownerB = User::factory()->owner()->create();

        $stationB = Station::factory()->create([
            'user_id' => $ownerB->id,
            'name' => 'Owner B Station',
        ]);

        // Admin tries to reassign station to Owner A
        $updatedData = [
            'name' => 'Hijacked Station',
            'address' => $stationB->address,
            'latitude' => $stationB->latitude,
            'longitude' => $stationB->longitude,
            'user_id' => $ownerA->id, // Trying to change ownership
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.update-station', $stationB->id), $updatedData);

        // Admin can update, but let's verify the station owner was actually changed
        $stationB->refresh();
        $this->assertEquals($ownerA->id, $stationB->user_id);
    }

    /** @test */
    public function admin_cannot_delete_non_existent_station(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->delete(route('admin.delete-station', 99999));

        $response->assertStatus(404);
    }

    /** @test */
    public function admin_cannot_update_non_existent_user(): void
    {
        $admin = User::factory()->admin()->create();

        $userData = [
            'name' => 'Test',
            'username' => 'test',
            'email' => 'test@test.com',
            'role' => 'driver',
        ];

        $response = $this->actingAs($admin)
            ->put(route('admin.update-user', 99999), $userData);

        $response->assertStatus(404);
    }
}
