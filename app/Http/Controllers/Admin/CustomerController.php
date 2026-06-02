<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerController extends Controller
{
    use LogsActivity;

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $customer = Customer::create($validated);
            $this->logActivity('created', "Created customer manual: {$customer->name}", $customer);

            DB::commit();

            return redirect()->route('admin.customers.index')->with('success', 'Customer added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Creation Failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to add customer. '.$e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_bookings')) {
            $query->where('total_bookings', '>=', $request->min_bookings);
        }

        if ($request->filled('min_spent')) {
            $query->where('total_spent', '>=', $request->min_spent);
        }

        $customers = $query->latest('last_booking_at')->paginate(20);

        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['bookings' => function ($q) {
            $q->latest('createdAt');
        }, 'bookings.package']);

        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $customer->update($validated);
            $this->logActivity('updated', "Updated customer profile: {$customer->name}", $customer);

            DB::commit();

            return redirect()->route('admin.customers.index')->with('success', 'Customer profile updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customer Update Failed: '.$e->getMessage());

            return back()->withInput()->with('error', 'Failed to update customer. '.$e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();
        $this->logActivity('deleted', "Deleted customer: {$name}");

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        Customer::whereIn('id', $ids)->delete();
        $this->logActivity('bulk_deleted', 'Bulk deleted '.count($ids).' customers');

        return response()->json(['message' => 'Customers deleted successfully']);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'customers-export-'.date('Y-m-d').'.'.$format;

        $customers = Customer::all();

        return \Excel::download(new class($customers) implements FromCollection, WithHeadings, WithMapping
        {
            protected $data;

            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return ['ID', 'Nama', 'Email', 'Telepon', 'Alamat', 'Total Bookings', 'Total Spent', 'Last Booking'];
            }

            public function map($row): array
            {
                return [
                    $row->id,
                    $row->name,
                    $row->email,
                    $row->phone,
                    $row->address,
                    $row->total_bookings,
                    $row->total_spent,
                    $row->last_booking_at ? $row->last_booking_at->format('Y-m-d') : '-',
                ];
            }
        }, $filename);
    }
}
