<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Reservation;
use App\Models\Table;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Database\Seeders\TableSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class);
        $this->seed(TableSeeder::class);
        $this->seed(MenuSeeder::class);
    }

    /**
     * Halaman Publik Dapat Diakses Tanpa Login
     */
    public function test_public_pages_are_accessible(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('RestoUs');
        $this->get('/menu')->assertStatus(200)->assertSee('Nasi Goreng Spesial RestoUs');
        $this->get('/rules')->assertStatus(200)->assertSee('0822-5478-1995');
        $this->get('/login')->assertStatus(200)->assertSee('Masuk');
        $this->get('/register')->assertStatus(200)->assertSee('Daftar Akun Baru');
    }

    /**
     * Halaman Admin Terproteksi: Tamu dialihkan ke login
     */
    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin/reservations');
        $response->assertRedirect('/login');
    }

    /**
     * Customer Tidak Bisa Mengakses Panel Admin
     */
    public function test_customer_cannot_access_admin_panel(): void
    {
        $customer = User::where('role', 'customer')->first();
        $response = $this->actingAs($customer)->get('/admin/reservations');
        $response->assertRedirect('/');
    }

    /**
     * Admin Bisa Login Menggunakan Username admin dan Password admin123
     */
    public function test_admin_can_login_with_username_and_password(): void
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/reservations');
        $this->assertAuthenticated();

        // Akses dasbor admin setelah login
        $admin = User::where('username', 'admin')->first();
        $this->actingAs($admin)->get('/admin/reservations')->assertStatus(200)->assertSee('Manajemen Pemesanan');
        $this->actingAs($admin)->get('/admin/menus')->assertStatus(200)->assertSee('Batas 50 Porsi/Hari');
        $this->actingAs($admin)->get('/admin/tables')->assertStatus(200)->assertSee('Meja 01');
    }

    /**
     * Customer Bisa Mendaftar Akun Baru
     */
    public function test_customer_can_register_new_account(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ahmad Tamu',
            'username' => 'ahmad_tamu',
            'email' => 'ahmad@example.com',
            'phone' => '08987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'username' => 'ahmad_tamu',
            'role' => 'customer',
        ]);
    }

    /**
     * Customer Bisa Masuk dengan Akun Google Demo
     */
    public function test_customer_can_login_with_google_demo(): void
    {
        $response = $this->get('/auth/google');
        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'pengunjung.google@gmail.com',
            'role' => 'customer',
        ]);
    }

    /**
     * Alur Pemesanan Meja oleh Customer Terautentikasi
     */
    public function test_authenticated_customer_can_book_table(): void
    {
        $customer = User::where('role', 'customer')->first();
        $table = Table::first();
        $menu = Menu::first();
        $tomorrow = now()->addDay()->toDateString();

        $response = $this->actingAs($customer)->post('/booking', [
            'customer_name' => $customer->name,
            'customer_phone' => $customer->phone,
            'customer_email' => $customer->email,
            'reservation_date' => $tomorrow,
            'reservation_time' => '13:00',
            'table_id' => $table->id,
            'guest_count' => 2,
            'menu_quantities' => [
                $menu->id => 1,
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reservations', [
            'table_id' => $table->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Customer Terautentikasi Dapat Mengakses dan Mengunduh Faktur / Invoice
     */
    public function test_customer_can_view_and_download_invoice(): void
    {
        $user = User::where('role', 'customer')->first();
        $customer = \App\Models\Customer::firstOrCreate(
            ['phone' => '08123456789'],
            ['name' => 'Budi Tamu', 'email' => 'budi@example.com']
        );
        $table = Table::first();
        $tomorrow = now()->addDay()->toDateString();

        $reservation = Reservation::create([
            'customer_id'      => $customer->id,
            'table_id'         => $table->id,
            'reservation_date' => $tomorrow,
            'reservation_time' => '12:00',
            'guest_count'      => 2,
            'status'           => 'pending',
            'total_amount'     => 50000,
            'payment_deadline' => now()->addHour(),
        ]);

        $response = $this->actingAs($user)->get('/booking/' . $reservation->id . '/invoice');

        $response->assertStatus(200)
            ->assertSee('FAKTUR RESERVASI')
            ->assertSee('#INV-RES-' . str_pad($reservation->id, 4, '0', STR_PAD_LEFT))
            ->assertSee('0822-5478-1995');
    }
}
