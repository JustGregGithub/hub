<div class="mt-2 w-full">
    <label>
        {{ $label }}
        <input type="text" class="border border-gray-300 rounded-md text-sm mt-1 w-full" wire:model="value">
    </label>
    @if (session()->has('input-success'))
        <span class="text-green-700 mt-2">{{ session('input-success') }}</span>
    @endif
    @error('value') <span class="text-red-700 mt-2">{{ $message }}</span> @enderror
</div>
