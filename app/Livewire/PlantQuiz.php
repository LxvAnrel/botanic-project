<?php

namespace App\Livewire;

use App\Models\Plant;
use Livewire\Component;

class PlantQuiz extends Component
{
    public $step = 1;
    public $answers = [];
    public $result = null;
    public $error = null;

    private array $stepKeys = [
        1 => 'hasPets',
        2 => 'light',
        3 => 'space',
        4 => 'experience',
    ];

    public function nextStep()
    {
        $key = $this->stepKeys[$this->step];

        if (!isset($this->answers[$key])) {
            $this->error = 'Selecione uma opção para continuar.';
            return;
        }

        $this->error = null;

        if ($this->step < 4) {
            $this->step++;
        } else {
            $this->calculateMatch();
        }
    }

    public function updatedAnswers(): void
    {
        $this->error = null;
    }

    public function previousStep()
    {
        if ($this->step > 1) {
            $this->step--;
            $this->error = null;
        }
    }

    public function calculateMatch()
    {
        $scores = [];
        $plants = Plant::all();

        foreach ($plants as $plant) {
            $score = 0;

            // Filtro Pet-Friendly
            if ($this->answers['hasPets'] ?? false) {
                if ($plant->toxica_pets) {
                    $score = -1000;
                }
            }

            // Filtro Luz
            if (isset($this->answers['light'])) {
                if ($plant->habitat_luz === $this->answers['light']) {
                    $score += 10;
                }
            }

            // Filtro Tamanho
            if (isset($this->answers['space'])) {
                $maxSize = $this->answers['space'] === 'small' ? 50 : ($this->answers['space'] === 'medium' ? 150 : 300);
                if ($plant->porte_max_cm && $plant->porte_max_cm <= $maxSize) {
                    $score += 5;
                }
            }

            // Filtro Experiência
            if (isset($this->answers['experience'])) {
                if ($this->answers['experience'] === 'beginner') {
                    $score += 2;
                } else {
                    $score += 5;
                }
            }

            if ($score >= 0) {
                $scores[$plant->id] = $score;
            }
        }

        if (count($scores) > 0) {
            arsort($scores);
            $bestPlantId = array_key_first($scores);
            $this->result = Plant::find($bestPlantId);
        }
    }

    public function resetQuiz()
    {
        $this->step = 1;
        $this->answers = [];
        $this->result = null;
        $this->error = null;
    }

    public function render()
    {
        return view('livewire.plant-quiz');
    }
}
