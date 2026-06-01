<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:superadmin,admin,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $this->logActivity('created', "Created user: {$user->name}", $user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:superadmin,admin,user',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        $this->logActivity('updated', "Updated user: {$user->name}", $user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $name = $user->name;
        $user->delete();
        $this->logActivity('deleted', "Deleted user: {$name}");

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['message' => 'No IDs provided'], 400);
        }

        // Prevent deleting yourself
        if (in_array(auth()->id(), $ids)) {
            return response()->json(['message' => 'You cannot delete your own account in bulk action!'], 400);
        }

        User::whereIn('id', $ids)->delete();
        $this->logActivity('bulk_deleted', 'Bulk deleted '.count($ids).' users');

        return response()->json(['message' => 'Users deleted successfully']);
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'xlsx');
        $filename = 'users-export-'.date('Y-m-d').'.'.$format;

        $users = User::all();

        return \Excel::download(new class($users) implements FromCollection, WithHeadings, WithMapping
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
                return ['ID', 'Nama', 'Email', 'Telepon', 'Role', 'Dibuat Pada'];
            }

            public function map($row): array
            {
                return [
                    $row->id,
                    $row->name,
                    $row->email,
                    $row->phone,
                    strtoupper($row->role),
                    $row->created_at->format('Y-m-d H:i'),
                ];
            }
        }, $filename);
    }
}
