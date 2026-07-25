<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Payroll;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialEngineTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $admin;
    protected Category $incomeCategory;
    protected Category $expenseCategory;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default users
        $this->owner = User::create([
            'name' => 'Test Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Seed default categories
        $this->incomeCategory = Category::create([
            'name' => 'Eco-Plumbing Income',
            'type' => 'income',
        ]);

        $this->expenseCategory = Category::create([
            'name' => 'Rent Expense',
            'type' => 'expense',
        ]);
    }

    /**
     * Uji validasi pencatatan transaksi agar tipe transaksi sinkron dengan kategori.
     */
    public function test_transaction_type_must_match_category_type()
    {
        $this->actingAs($this->admin);

        // 1. Submit matching type (income - income) -> Should succeed
        $response = $this->post(route('transactions.store'), [
            'category_id' => $this->incomeCategory->id,
            'title' => 'Layanan Eco-Plumbing Ardy',
            'amount' => 1500000,
            'transaction_date' => now()->toDateString(),
            'type' => 'income',
        ]);
        
        $response->assertRedirect(route('transactions.index'));
        $this->assertDatabaseHas('transactions', [
            'title' => 'Layanan Eco-Plumbing Ardy',
            'type' => 'income',
            'amount' => 1500000,
        ]);

        // 2. Submit MISMATCHED type (income category - expense transaction) -> Should fail validation
        $responseMismatch = $this->post(route('transactions.store'), [
            'category_id' => $this->incomeCategory->id,
            'title' => 'Pembelian Token Listrik Kantor',
            'amount' => 200000,
            'transaction_date' => now()->toDateString(),
            'type' => 'expense',
        ]);

        $responseMismatch->assertSessionHasErrors(['type']);
        $this->assertDatabaseMissing('transactions', [
            'title' => 'Pembelian Token Listrik Kantor',
        ]);
    }

    /**
     * Uji pembatasan hak akses middleware: admin ditolak akses rute owner.
     */
    public function test_admin_cannot_access_owner_only_routes()
    {
        // 1. Admin tries to access users CRUD (Owner-only) -> Should get 403
        $this->actingAs($this->admin);
        $response = $this->get(route('users.index'));
        $response->assertStatus(403);

        // 2. Owner tries to access users CRUD -> Should get 200
        $this->actingAs($this->owner);
        $responseOwner = $this->get(route('users.index'));
        $responseOwner->assertStatus(200);
    }

    /**
     * Uji pembatasan tampilan laba bersih dan payroll pada dasbor bagi admin.
     */
    public function test_admin_cannot_see_net_profit_and_payroll_aggregates_on_dashboard()
    {
        // Add payroll and transactions
        Payroll::create([
            'employee_name' => 'Asep',
            'role_type' => 'staff',
            'base_salary' => 4500000,
            'period' => now()->format('Y-m'),
            'status' => 'paid',
        ]);

        Transaction::create([
            'category_id' => $this->incomeCategory->id,
            'user_id' => $this->owner->id,
            'title' => 'Project Eco',
            'amount' => 5000000,
            'transaction_date' => now()->toDateString(),
            'type' => 'income',
        ]);

        // 1. Login as Admin -> Dashboard should NOT display Net Profit and total salaries
        $this->actingAs($this->admin);
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Akses dibatasi (Owner Only)');

        // 2. Login as Owner -> Dashboard should display Net Profit
        $this->actingAs($this->owner);
        $responseOwner = $this->get(route('dashboard'));
        $responseOwner->assertStatus(200);
        $responseOwner->assertDontSee('Akses dibatasi (Owner Only)');
    }

    /**
     * Uji integritas Soft Deletes pada transaksi.
     */
    public function test_transaction_soft_deletes()
    {
        $this->actingAs($this->admin);

        $tx = Transaction::create([
            'category_id' => $this->incomeCategory->id,
            'user_id' => $this->admin->id,
            'title' => 'Project Soft Delete',
            'amount' => 100000,
            'transaction_date' => now()->toDateString(),
            'type' => 'income',
        ]);

        // Delete transaction
        $response = $this->delete(route('transactions.destroy', $tx));
        $response->assertRedirect(route('transactions.index'));

        // Assert soft deleted
        $this->assertSoftDeleted($tx);
    }

    /**
     * Uji aturan foreign key onDelete('restrict') pada kategori.
     */
    public function test_cannot_delete_category_with_transactions()
    {
        $this->actingAs($this->owner);

        Transaction::create([
            'category_id' => $this->incomeCategory->id,
            'user_id' => $this->owner->id,
            'title' => 'Project Restrict Test',
            'amount' => 100000,
            'transaction_date' => now()->toDateString(),
            'type' => 'income',
        ]);

        // Trying to delete category should fail in CategoryController check or DB exception
        $response = $this->delete(route('categories.destroy', $this->incomeCategory));
        
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $this->incomeCategory->id]);
    }
}
