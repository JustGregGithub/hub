<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class InputBox extends Component
{
    public Model $model;
    public string $value;
    public string $column;
    public string $label = '';

    public function mount(Model $model, string $column, string $label)
    {
        $this->model = $model;
        $this->column = $column;
        $this->label = $label;
        $this->value = $this->model->{$this->column} ?? '';
    }

    public function updatedValue()
    {
        $this->validate([
            'value' => 'required',
            'column' => 'required'
        ]);

        //check if the value is different from the original
        if ($this->model->{$this->column} === $this->value) {
            return;
        }

        //check the type of the column and error if it's not the same as the value
        if (gettype($this->model->{$this->column}) !== gettype($this->value)) {
            session()->flash('input-error', 'The value you entered is not the same type as the column!');
            return;
        }

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
