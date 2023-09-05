<?php

namespace App\Http\Livewire;

use App\Models\Hub\ApplicationSection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class SelectDropdown extends Component
{
    public Model $model;
    public $column;
    public $label;
    public $options;
    public $selectedOption;

    public function mount($model, $column, $label)
    {
        // Fetch options from the ApplicationSection model and use 'id' as value and 'name' as option text
        $this->options = ApplicationSection::pluck('name', 'id')->toArray();

        // Determine the default selected option based on the current value in the model
        $this->selectedOption = $this->model->{$this->column};
    }

    public function render()
    {
        return view('livewire.select-dropdown');
    }

    public function updatedSelectedOption($value)
    {
        // Handle the update when a new option is selected
        // Here, you can perform any action you want with the newly selected value
        // For example, you can store it in the database or perform some other logic

        $this->model->{$this->column} = $value;
        $this->model->save();

        session()->flash('input-success', 'Successfully updated ' . $this->label . '!');
    }
}
