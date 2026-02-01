<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Perfil') }}</flux:heading>

    <x-settings.layout :heading="__('Perfil')" :subheading="__('Actualiza tu nombre y dirección de correo electrónico')">
        <form wire:submit="updateProfileInformation" class="my-6 w-full space-y-6">
            <flux:input wire:model="nombre_completo" :label="__('Nombre completo')" type="text" required autofocus
                autocomplete="name" />

            <div>
                <flux:input wire:model="email" :label="__('Correo electrónico')" type="email" required
                    autocomplete="email" />

                @if ($this->hasUnverifiedEmail)
                    <div>
                        <flux:text class="mt-4">
                            {{ __('Tu dirección de correo electrónico no ha sido verificada.') }}

                            <flux:link class="text-sm cursor-pointer" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click aquí para reenviar el correo de verificación.') }}
                            </flux:link>
                        </flux:text>

                        @if (session('status') === 'verification-link-sent')
                            <flux:text class="mt-2 font-medium !dark:text-green-400 !text-green-600">
                                {{ __('Un nuevo enlace de verificación ha sido enviado a tu dirección de correo electrónico.') }}
                            </flux:text>
                        @endif
                    </div>
                @endif
            </div>

            @if(auth()->user()->isCliente())
                <div class="space-y-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="lg">{{ __('Información del Cliente') }}</flux:heading>

                    <!-- Profile Photo -->
                    <div class="flex items-center gap-6" x-data="{
                                    async handleFileSelect(event) {
                                        const file = event.target.files[0];
                                        if (!file) return;

                                        const options = {
                                            maxSizeMB: 1,
                                            maxWidthOrHeight: 400,
                                            useWebWorker: true,
                                            initialQuality: 0.7
                                        };

                                        try {
                                            const optimized = await ImageOptimizer.compress(file, 'AVATAR');
                                            @this.upload('foto_perfil', optimized);
                                        } catch (error) {
                                            console.error('Error optimizing image:', error);
                                            @this.upload('foto_perfil', file);
                                        }
                                    }
                                }">
                        <div class="shrink-0">
                            @if ($foto_perfil)
                                <img src="{{ $foto_perfil->temporaryUrl() }}" class="size-20 rounded-full object-cover">
                            @elseif (auth()->user()->profile_photo_url)
                                <img src="{{ auth()->user()->profile_photo_url }}" class="size-20 rounded-full object-cover">
                            @else
                                <div
                                    class="size-20 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-400">
                                    <flux:icon.photo class="size-8" />
                                </div>
                            @endif
                        </div>
                        <div>
                            <flux:label>{{ __('Foto de Perfil') }}</flux:label>
                            <input type="file" x-on:change="handleFileSelect" class="block w-full text-sm text-zinc-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-zinc-800 dark:file:text-zinc-300
                                    " />
                            <flux:error name="foto_perfil" />
                        </div>
                    </div>

                    <flux:input wire:model="direccion" :label="__('Dirección de entrega')" type="text"
                        placeholder="Calle Ejemplo, 123" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="telefono" :label="__('Teléfono')" type="tel"
                            placeholder="+34 600 000 000" />
                        <flux:input wire:model="tarjeta_mock" :label="__('Tarjeta (Mock)')" type="text"
                            placeholder="**** **** **** 1234" icon="credit-card" />
                    </div>
                </div>
            @endif

            @if(auth()->user()->isRestaurante())
                <div class="space-y-6 pt-6 border-t border-zinc-200 dark:border-zinc-700">
                    <flux:heading size="lg">{{ __('Información del Restaurante') }}</flux:heading>

                    <!-- Profile Photo -->
                    <div class="flex items-center gap-6" x-data="{
                                    async handleFileSelect(event) {
                                        const file = event.target.files[0];
                                        if (!file) return;

                                        const options = {
                                            maxSizeMB: 1,
                                            maxWidthOrHeight: 400,
                                            useWebWorker: true,
                                            initialQuality: 0.7
                                        };

                                        try {
                                            const compressedFile = await imageCompression(file, options);
                                            const finalFile = new File([compressedFile], file.name, { type: file.type });
                                            @this.upload('foto_perfil', finalFile);
                                        } catch (error) {
                                            console.error('Error compressing image:', error);
                                            @this.upload('foto_perfil', file);
                                        }
                                    }
                                }">
                        <div class="shrink-0">
                            @if ($foto_perfil)
                                <img src="{{ $foto_perfil->temporaryUrl() }}" class="size-20 rounded-full object-cover">
                            @elseif (auth()->user()->profile_photo_url)
                                <img src="{{ auth()->user()->profile_photo_url }}" class="size-20 rounded-full object-cover">
                            @else
                                <div
                                    class="size-20 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center text-zinc-400">
                                    <flux:icon.photo class="size-8" />
                                </div>
                            @endif
                        </div>
                        <div>
                            <flux:label>{{ __('Logo / Imagen de Perfil') }}</flux:label>
                            <input type="file" x-on:change="handleFileSelect" class="block w-full text-sm text-zinc-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100
                                        dark:file:bg-zinc-800 dark:file:text-zinc-300
                                    " />
                            <flux:error name="foto_perfil" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="tipo_cocina" :label="__('Tipo de cocina')" type="text"
                            placeholder="Ej: Italiana, Mexicana..." />
                        <flux:input wire:model="telefono" :label="__('Teléfono de contacto')" type="tel"
                            placeholder="+34 900 000 000" />
                    </div>

                    <flux:textarea wire:model="descripcion" :label="__('Descripción')"
                        placeholder="Cuéntanos sobre tu restaurante..." rows="3" />

                    <flux:input wire:model="direccion" :label="__('Dirección del local')" type="text"
                        placeholder="Plaza Mayor, 1" />

                    <flux:input wire:model="redes_sociales" :label="__('Redes Sociales')" type="text"
                        placeholder="https://instagram.com/mi_restaurante" />

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input wire:model="NIF" :label="__('NIF')" type="text" placeholder="B12345678" />
                        <flux:input wire:model="cuenta_bancaria_mock" :label="__('Cuenta Bancaria (Mock)')" type="text"
                            placeholder="ES00 0000 0000 0000 0000 0000" icon="credit-card" />
                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Guardar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Guardado.') }}
                </x-action-message>
            </div>
        </form>

        @if ($this->showDeleteUser)
            <livewire:settings.delete-user-form />
        @endif
    </x-settings.layout>
</section>