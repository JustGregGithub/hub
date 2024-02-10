<div class="mt-2 w-full">
    <label for="{{ $column }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <select name="{{ $column }}" class="border border-gray-300 rounded-md text-sm mt-2 w-full dark:bg-neutral-800 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" wire:model="selectedOption">
        @foreach ($options as $value => $option)
            <option value="{{ $value }}">{{ $option }}</option>
        @endforeach
    </select>
    @if (session()->has('input-success'))
        <span class="text-green-700 mt-2">{{ session('input-success') }}</span>
    @endif
    @error('value') <span class="text-red-700 mt-2">{{ $message }}</span> @enderror
</div>
