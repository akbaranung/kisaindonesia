<?php

namespace App\Livewire\Admin;

use App\Models\CoinPackage;
use Livewire\Component;
use Livewire\WithPagination;

class CoinPackageIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';

    public $showModal = false;
    public $showDeleteModal = false;
    public $isEditMode = false;
    public $packageIdBeingDeleted = null;

    public $packageId;
    public $name = '';
    public $beans = 0;
    public $bonus_beans = 0;
    public $price = 0;
    public $discount_price = null;
    public $badge_label = '';
    public $is_active = true;
    public $order_priority = 0;

    public $paginationTheme = 'tailwind';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'beans' => 'required|integer|min:1',
            'bonus_beans' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'badge_label' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'order_priority' => 'required|integer|min:0',
        ];
    }

    protected $messages = [
        'discount_price.lt' => 'Harga diskon harus lebih kecil dari harga normal.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $package = CoinPackage::findOrFail($id);

        $this->packageId = $package->id;
        $this->name = $package->name;
        $this->beans = $package->beans;
        $this->bonus_beans = $package->bonus_beans;
        $this->price = $package->price;
        $this->discount_price = $package->discount_price;
        $this->badge_label = $package->badge_label;
        $this->is_active = $package->is_active;
        $this->order_priority = $package->order_priority;

        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validated = $this->validate();

        // Kosongkan discount_price jika bernilai 0 atau empty string
        if (empty($validated['discount_price'])) {
            $validated['discount_price'] = null;
        }

        if ($this->isEditMode) {
            $package = CoinPackage::findOrFail($this->packageId);
            $package->update($validated);
            $this->dispatch('show-toast', type: 'success', message: 'Paket koin berhasil diperbarui!');
        } else {
            CoinPackage::create($validated);
            $this->dispatch('show-toast', type: 'success', message: 'Paket koin baru berhasil ditambahkan!');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleStatus($id)
    {
        $package = CoinPackage::findOrFail($id);
        $package->is_active = !$package->is_active;
        $package->save();

        $statusText = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('show-toast', type: 'success', message: "Paket koin berhasil {$statusText}!");
    }

    public function confirmDelete($id)
    {
        $this->packageIdBeingDeleted = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->packageIdBeingDeleted) {
            $package = CoinPackage::findOrFail($this->packageIdBeingDeleted);
            $package->delete();

            $this->dispatch('show-toast', type: 'success', message: 'Paket koin berhasil dihapus!');
            $this->showDeleteModal = false;
            $this->packageIdBeingDeleted = null;
        }
    }

    public function resetForm()
    {
        $this->reset([
            'packageId',
            'name',
            'beans',
            'bonus_beans',
            'price',
            'discount_price',
            'badge_label',
            'is_active',
            'order_priority'
        ]);
        $this->resetValidation();
        $this->is_active = true;
        $this->beans = 0;
        $this->bonus_beans = 0;
        $this->price = 0;
        $this->order_priority = 0;
    }

    public function render()
    {
        $packages = CoinPackage::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('badge_label', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus !== '', function ($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->orderBy('order_priority', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.coin-package-index', [
            'packages' => $packages,
        ])->layout('layouts.admin');
    }
}
