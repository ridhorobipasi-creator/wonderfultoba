<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    use HandlesImageUploads;
    public function index()
    {
        $clients = Client::orderBy('sortOrder')->get();
        return view('admin.clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'required|image|mimes:png,svg,webp|max:1024',
            'website' => 'nullable|url',
        ]);

        $path = $this->uploadAndIndex($request->file('logo'), 'clients', 'branding');

        Client::create([
            'name' => $request->name,
            'logo' => $path,
            'website' => $request->website,
            'sortOrder' => Client::max('sortOrder') + 1
        ]);

        return redirect()->back()->with('success', 'Client added!');
    }

    public function destroy(Client $client)
    {
        if ($client->logo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $client->logo));
        }
        $client->delete();
        return redirect()->back()->with('success', 'Client removed!');
    }
}
