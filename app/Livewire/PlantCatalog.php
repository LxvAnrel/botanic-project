<?php

namespace App\Livewire;

use App\Models\Plant;
use Livewire\Component;
use Livewire\WithPagination;

class PlantCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $habitat = '';
    public $petFriendly = false;
    public $size = '';
    public $perPage = 12;

    protected $queryString = ['search', 'habitat', 'petFriendly', 'size'];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingHabitat() { $this->resetPage(); }
    public function updatingPetFriendly() { $this->resetPage(); }
    public function updatingSize() { $this->resetPage(); }

    public function setHabitat(string $value): void { $this->habitat = $value; $this->resetPage(); }
    public function setSize(string $value): void { $this->size = $value; $this->resetPage(); }
    public function togglePet(): void { $this->petFriendly = !$this->petFriendly; $this->resetPage(); }
    public function clearFilters(): void { $this->search = ''; $this->habitat = ''; $this->petFriendly = false; $this->size = ''; $this->resetPage(); }

    public function render()
    {
        $query = Plant::query();

        if ($this->search) {
            $query->search($this->search);
        }

        if ($this->habitat) {
            $query->sunlight($this->habitat);
        }

        if ($this->petFriendly) {
            $query->petFriendly();
        }

        if ($this->size) {
            $maxCm = match($this->size) {
                'pequeno' => 50,
                'medio'   => 150,
                'grande'  => 500,
                default   => null,
            };
            if ($maxCm) {
                $query->bySize($maxCm);
            }
        }

        $plants = $query->paginate($this->perPage);

        return view('livewire.plant-catalog', [
            'plants' => $plants,
        ]);
    }
}
