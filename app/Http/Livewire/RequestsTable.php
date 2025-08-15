<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Requests;

class RequestsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.requests-table', [
            'requests' => Requests::when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('car_model', 'like', '%'.$this->search.'%')
                        ->orWhere('license_plate', 'like', '%'.$this->search.'%');
                });
            })
                ->when($this->status, function($query) {
                    $query->where('status', $this->status);
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage)
        ]);
    }
}
