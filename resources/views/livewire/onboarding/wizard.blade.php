<x-layouts::app :title="__('Dashboard')">
    <div class="max-w-2xl mx-auto py-10">

        {{-- STEP 1 – Setare parolă --}}
        @if($step === 'password')

            <h2 class="text-xl font-bold mb-6">Setare parolă</h2>

            <div class="space-y-4">
                <div>
                    <label class="block mb-1">Parolă</label>
                    <input type="password"
                        wire:model.defer="password"
                        class="w-full border rounded px-3 py-2">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-1">Confirmare parolă</label>
                    <input type="password"
                        wire:model.defer="password_confirmation"
                        class="w-full border rounded px-3 py-2">
                </div>

                <button wire:click="setPassword"
                        class="bg-blue-600 text-white px-4 py-2 rounded">
                    Continuă
                </button>
            </div>

        @endif


        {{-- STEP 2 – Configurare clădire --}}
        @if($step === 'building')

            <h2 class="text-xl font-bold mb-6">Configurare structură imobil</h2>

            <div class="space-y-4">

                <div>
                    <label class="block mb-1">Preset</label>
                    <select wire:model="preset"
                            class="w-full border rounded px-3 py-2">
                        <option value="P+4">P+4</option>
                        <option value="P+8">P+8</option>
                        <option value="P+10">P+10</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Apartamente / nivel</label>
                    <input type="number"
                        wire:model="apartments_per_floor"
                        min="1"
                        class="w-full border rounded px-3 py-2">
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox"
                        wire:model="include_commercial_ground">
                    <label>Spații comerciale la parter</label>
                </div>

                @if($include_commercial_ground)
                    <div>
                        <label class="block mb-1">Număr spații comerciale</label>
                        <input type="number"
                            wire:model="commercial_units_count"
                            min="1"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div>
                        <label class="block mb-1">Suprafață spațiu comercial (mp)</label>
                        <input type="number"
                            step="0.01"
                            wire:model="commercial_area"
                            class="w-full border rounded px-3 py-2">
                    </div>
                @endif

                <div class="flex space-x-4">
                    <button wire:click="previewStructure"
                            class="bg-blue-600 text-white px-4 py-2 rounded">
                        Previzualizează
                    </button>

                    <button wire:click="$set('step', 'password')"
                            class="bg-gray-300 px-4 py-2 rounded">
                        Înapoi
                    </button>
                </div>

            </div>

        @endif


        {{-- STEP 3 – Previzualizare --}}
        @if($step === 'preview')

            <h2 class="text-xl font-bold mb-6">Previzualizare</h2>

            <div class="space-y-2 mb-6">
                <p><strong>Total unități:</strong> {{ $preview_total_units }}</p>
                <p><strong>Suprafață totală:</strong> {{ number_format($preview_total_area, 2) }} mp</p>
            </div>

            <div class="flex space-x-4">
                <button wire:click="generateAssociation"
                        class="bg-green-600 text-white px-4 py-2 rounded">
                    Confirmă și creează
                </button>

                <button wire:click="$set('step', 'building')"
                        class="bg-gray-300 px-4 py-2 rounded">
                    Înapoi
                </button>
            </div>

        @endif


        {{-- STEP 4 – Finalizat --}}
        @if($step === 'completed')

            <h2 class="text-xl font-bold mb-4">Asociația a fost creată!</h2>
            <p>Vei fi redirecționat automat către dashboard.</p>

        @endif

    </div>
</x-layouts::app>