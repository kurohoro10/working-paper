<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminClientController extends Controller
{

    public function index(): View
    {
        $this->authorize('viewAny', Client::class);

        $clients = Client::latest()->paginate(10);

        return view('admin.client.index', compact('clients'));
    }

    /**
     * Show the create-user form.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        $this->authorize('create', Client::class);

        return view('admin.client.create');
    }

    /**
     * Store a new client user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Client $client): RedirectResponse
    {
        $this->authorize('create', $client);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'company'    => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'address'    => ['nullable', 'string', 'max:500'],
            'notes'      => ['nullable', 'string'],
        ]);

        Client::create($validated);

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client user created successfully.');
    }

    public function show(Client $client): View
    {
        $this->authorize('view', $client);

        return view('admin.client.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        $this->authorize('update', $client);

        return view('admin.client.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['nullable', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'company'    => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'address'    => ['nullable', 'string', 'max:500'],
            'notes'      => ['nullable', 'string'],
        ]);

        $client->update($validated);

        return redirect()
            ->route('admin.clients.show', $client)
            ->with('success', 'Client updated successfully');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $this->authorize('delete', $client);

        $client->delete();

        return redirect()
            ->route('admin.clients.index')
            ->with('success', 'Client deleted successfully');
    }
}
