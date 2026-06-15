<?php

namespace App\Livewire;

use App\Models\Plant;
use Livewire\Component;

class PlantCatalog extends Component
{
    public $search = '';
    public $habitat = '';
    public $petFriendly = false;
    public $size = '';
    public $perPage = 12;

    protected $queryString = ['search', 'habitat', 'petFriendly', 'size'];

    public function updatingSearch() { $this->perPage = 12; }

    public function setHabitat(string $value): void { $this->habitat = $value; $this->perPage = 12; }
    public function setSize(string $value): void { $this->size = $value; $this->perPage = 12; }
    public function togglePet(): void { $this->petFriendly = !$this->petFriendly; $this->perPage = 12; }
    public function clearFilters(): void { $this->search = ''; $this->habitat = ''; $this->petFriendly = false; $this->size = ''; $this->perPage = 12; }
    public function loadMore(): void { $this->perPage += 9; }

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

        $total  = (clone $query)->count();
        $plants = $query->take($this->perPage)->get();

        return view('livewire.plant-catalog', [
            'plants' => $plants,
            'total'  => $total,
        ]);
    }
}
