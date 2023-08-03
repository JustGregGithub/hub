<div class="mt-2 w-full">
    <label for="{{ $column }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <select name="{{ $column }}" class="border border-gray-300 rounded-md text-sm mt-2 w-full" wire:model="selectedOption">
        @foreach ($options as $value => $option)
            <option value="{{ $value }}">{{ $option }}</option>
        @endforeach
    </select>
    @if (session()->has('input-success'))
        <span class="text-green-700 mt-2">{{ session('input-success') }}</span>
    @endif
    @error('value') <span class="text-red-700 mt-2">{{ $message }}</span> @enderror
</div>
