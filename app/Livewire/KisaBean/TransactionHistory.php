<?php

namespace App\Livewire\KisaBean;

use App\Models\UserTransaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionHistory extends Component
{
    use WithPagination;

    public $filterType = 'all';

    public function setFilter($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function render()
    {
        $query = UserTransaction::where('user_id', Auth::id());

        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        $transactions = $query->latest()->paginate(10);

        return view('livewire.beans.transaction-hostory', ['transactions' => $transactions]);
    }
}
