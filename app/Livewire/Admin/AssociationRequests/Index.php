<?php

namespace App\Livewire\Admin\AssociationRequests;

use Livewire\Component;
use App\Models\AssociationRequest;
use Illuminate\Support\Str;

class Index extends Component
{
    public $statusFilter = 'pending';

    public function approve($id)
    {
        $request = AssociationRequest::findOrFail($id);

        if (! $request->isPending()) {
            return;
        }

        $request->approve();

        $request->tokens()->create([
            'token' => Str::uuid(),
            'expires_at' => now()->addDays(3),
        ]);

        // aici ulterior trimitem email
    }

    public function reject($id)
    {
        $request = AssociationRequest::findOrFail($id);

        if (! $request->isPending()) {
            return;
        }

        $request->reject('Respins de administrator.');
    }

    public function render()
    {
        $requests = AssociationRequest::query()
            ->when($this->statusFilter, fn($q) =>
                $q->where('status', $this->statusFilter)
            )
            ->latest()
            ->get();

        return view('livewire.admin.association-requests.index', [
            'requests' => $requests,
        ]);
    }
}