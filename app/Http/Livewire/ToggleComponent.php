<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ToggleComponent extends Component
{
    public Model $model;
    public $column;

    public function toggle()
    {
        $this->model->{$this->column} = !$this->model->{$this->column};
        $this->model->save();

        session()->flash('success', 'Successfully set ' . $this->column . ' to ' . $this->model->{$this->column} . ' for ' . $this->model->name . '!');
    }

    public function render()
    {
        return view('livewire.toggle-component');
    }
}
