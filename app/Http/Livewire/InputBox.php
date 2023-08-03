<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class InputBox extends Component
{
    public Model $model;
    public string $value;
    public string $column;

    public function mount(Model $model, string $column, string $label)
    {
        $this->model = $model;
        $this->column = $column;
        $this->label = $label;
        $this->value = $this->model->{$this->column};
    }

    public function updatedValue()
    {
        $this->validate([
            'value' => 'required|string',
            'column' => 'required|string'
        ]);

        $this->model->{$this->column} = $this->value;
        $this->model->save();

        $this->emit('saved');

        session()->flash('input-success', 'Successfully updated ' . $this->label . '!');
    }

    public function render()
    {
        return view('livewire.input-box');
    }
}
