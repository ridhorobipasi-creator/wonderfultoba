<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\GalleryImage;
use App\Models\Package;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminControllersTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'superadmin',
            'email' => 'admin@test.com',
        ]);
    }

    /** @test */
    public function test_admin_can_access_dashboard()
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    /** @test */
    public function test_admin_can_view_packages_index()
    {
        Package::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/packages');

        $response->assertStatus(200);
        $response->assertViewIs('admin.packages.index');
        $response->assertViewHas('packages');
    }

    /** @test */
    public function test_admin_can_filter_packages_by_status()
    {
        Package::factory()->create(['status' => 'active']);
        Package::factory()->create(['status' => 'inactive']);

        $response = $this->actingAs($this->admin)->get('/admin/packages?status=active');

        $response->assertStatus(200);
        $packages = $response->viewData('packages');
        $this->assertEquals(1, $packages->total());
    }

    /** @test */
    public function test_admin_can_create_customer_with_transaction()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/customers', [
            'name' => 'Test Customer',
            'email' => 'test@customer.com',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'notes' => 'Test Notes',
        ]);

        $response->assertRedirect('/admin/customers');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('customers', [
            'name' => 'Test Customer',
            'email' => 'test@customer.com',
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
            'description' => 'Created customer manual: Test Customer',
        ]);
    }

    /** @test */
    public function test_customer_creation_rolls_back_on_error()
    {
        // Try to create customer with duplicate email
        Customer::factory()->create(['email' => 'duplicate@test.com']);

        $response = $this->actingAs($this->admin)->post('/admin/customers', [
            'name' => 'Test Customer',
            'email' => 'duplicate@test.com', // Duplicate email
            'phone' => '08123456789',
        ]);

        $response->assertSessionHasErrors('email');

        // Should only have 1 customer (the first one)
        $this->assertEquals(1, Customer::count());
    }

    /** @test */
    public function test_admin_can_bulk_delete_customers()
    {
        $customers = Customer::factory()->count(3)->create();
        $ids = $customers->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->postJson('/admin/customers/bulk-destroy', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Customers deleted successfully']);

        $this->assertEquals(0, Customer::count());

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'bulk_deleted',
            'description' => 'Bulk deleted 3 customers',
        ]);
    }

    /** @test */
    public function test_admin_can_export_customers()
    {
        Customer::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get('/admin/customers/export?format=xlsx');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function test_admin_can_view_blog_show_page()
    {
        $blog = Blog::factory()->create([
            'title' => 'Test Blog',
            'content' => 'Test Content',
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/blogs/{$blog->id}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.blogs.show');
        $response->assertSee('Test Blog');
    }

    /** @test */
    public function test_admin_can_bulk_delete_blogs()
    {
        $blogs = Blog::factory()->count(3)->create();
        $ids = $blogs->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->postJson('/admin/blogs/bulk-destroy', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Blogs deleted successfully']);

        $this->assertEquals(0, Blog::count());
    }

    /** @test */
    public function test_admin_can_filter_blogs_by_category()
    {
        Blog::factory()->create(['category' => 'Tips Wisata']);
        Blog::factory()->create(['category' => 'Destinasi']);

        $response = $this->actingAs($this->admin)->get('/admin/blogs?category=Tips+Wisata');

        $response->assertStatus(200);
        $blogs = $response->viewData('blogs');
        $this->assertEquals(1, $blogs->total());
    }

    /** @test */
    public function test_admin_can_edit_gallery_item()
    {
        $gallery = GalleryImage::factory()->create([
            'caption' => 'Old Caption',
            'category' => 'tour',
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/gallery/{$gallery->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.gallery.edit');
        $response->assertSee('Old Caption');
    }

    /** @test */
    public function test_admin_can_update_gallery_item()
    {
        $gallery = GalleryImage::factory()->create([
            'caption' => 'Old Caption',
            'category' => 'tour',
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/gallery/{$gallery->id}", [
            'caption' => 'New Caption',
            'category' => 'outbound',
        ]);

        $response->assertRedirect('/admin/gallery');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('gallery_images', [
            'id' => $gallery->id,
            'caption' => 'New Caption',
            'category' => 'outbound',
        ]);
    }

    /** @test */
    public function test_admin_can_bulk_delete_packages()
    {
        $packages = Package::factory()->count(3)->create();
        $ids = $packages->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->postJson('/admin/packages/bulk-destroy', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Packages deleted successfully']);

        $this->assertEquals(0, Package::count());
    }

    /** @test */
    public function test_package_creation_with_transaction_works()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->post('/admin/packages', [
            'name' => 'Test Package',
            'shortDescription' => 'Short desc',
            'description' => 'Long description',
            'price' => 1000000,
            'duration' => '3D2N',
            'status' => 'active',
            'isFeatured' => true,
        ]);

        $response->assertRedirect('/admin/packages');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('packages', [
            'name' => 'Test Package',
            'price' => 1000000,
        ]);

        // Check activity log
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'created',
        ]);
    }

    /** @test */
    public function test_admin_can_view_customer_create_page()
    {
        $response = $this->actingAs($this->admin)->get('/admin/customers/create');

        $response->assertStatus(200);
        $response->assertViewIs('admin.customers.create');
    }

    /** @test */
    public function test_admin_can_filter_customers_by_min_bookings()
    {
        Customer::factory()->create(['total_bookings' => 5]);
        Customer::factory()->create(['total_bookings' => 2]);

        $response = $this->actingAs($this->admin)->get('/admin/customers?min_bookings=3');

        $response->assertStatus(200);
        $customers = $response->viewData('customers');
        $this->assertEquals(1, $customers->total());
    }

    /** @test */
    public function test_admin_can_bulk_delete_bookings()
    {
        $bookings = Booking::factory()->count(3)->create();
        $ids = $bookings->pluck('id')->toArray();

        $response = $this->actingAs($this->admin)->postJson('/admin/bookings/bulk-destroy', [
            'ids' => $ids,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'Bookings deleted successfully']);

        $this->assertEquals(0, Booking::count());
    }

    /** @test */
    public function test_error_handling_preserves_input_on_failure()
    {
        // Try to create customer with invalid data
        $response = $this->actingAs($this->admin)->post('/admin/customers', [
            'name' => 'Test',
            'email' => 'invalid-email', // Invalid email
        ]);

        $response->assertSessionHasErrors('email');
        $response->assertSessionHasInput('name', 'Test');
    }
}
