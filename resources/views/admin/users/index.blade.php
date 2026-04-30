@extends('admin.layout')

@section('title', 'Users Management')
@section('page-title', 'Users')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900">Manage Users</h1>
            <p class="text-gray-600 mt-1 text-sm">View and manage system users</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
            <i class="fas fa-plus mr-2"></i> New User
        </a>
    </div>

    <!-- Filters Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-search mr-1 text-gray-400"></i>Search
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Name, email, phone..." 
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition"
                    >
                </div>

                <!-- Role Filter -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-1 text-gray-400"></i>Role
                    </label>
                    <select name="role" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-toba-green focus:border-transparent transition">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-toba-green to-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold hover:shadow-lg hover:shadow-toba-green/30 transition-all shadow-md">
                        <i class="fas fa-search mr-2"></i> Apply Filters
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">User</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-br from-{{ $user->role === 'admin' ? 'orange-500 to-red-600' : 'blue-500 to-purple-600' }} rounded-full flex items-center justify-center text-white font-bold text-sm mr-3 shadow-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 text-sm">{{ $user->name }}</p>
                                        @if($user->id === auth()->id())
                                            <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                <i class="fas fa-user-circle mr-1"></i>You
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm space-y-1">
                                    <p class="text-gray-900">
                                        <i class="fas fa-envelope text-gray-400 mr-2"></i>
                                        {{ $user->email }}
                                    </p>
                                    @if($user->phone)
                                        <p class="text-gray-600">
                                            <i class="fas fa-phone text-gray-400 mr-2"></i>
                                            {{ $user->phone }}
                                        </p>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }}">
                                    <i class="fas fa-{{ $user->role === 'admin' ? 'user-shield' : 'user' }} mr-1.5"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <i class="far fa-calendar mr-2 text-gray-400"></i>{{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="View">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition" title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Delete">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </form>
                                    @else
                                        <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 text-gray-400 cursor-not-allowed" title="Cannot delete yourself">
                                            <i class="fas fa-ban text-sm"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-users text-5xl mb-4"></i>
                                    <p class="text-lg font-bold text-gray-600">No users found</p>
                                    <p class="text-sm text-gray-500 mt-1">Try adjusting your filters or add a new user</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
