<div>
    @if($show)
        <div class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay with animation -->
                <div class="fixed inset-0 bg-zinc-900/75 backdrop-blur-sm transition-opacity" wire:click="cancel" x-data
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white dark:bg-zinc-900 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                    x-data x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                    <div class="bg-white dark:bg-zinc-900 px-6 pt-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <!-- Icon -->
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-orange-100 dark:bg-orange-900/30 sm:mx-0 sm:h-12 sm:w-12">
                                <svg class="h-7 w-7 text-orange-600 dark:text-orange-400" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>

                            <!-- Content -->
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2" id="modal-title">
                                    {{ $title }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                                        {{ $message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="bg-zinc-50 dark:bg-zinc-800/50 px-6 py-4 sm:flex sm:flex-row-reverse gap-3">
                        <button wire:click="confirm" type="button"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-orange-600 text-base font-semibold text-white hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 sm:w-auto sm:text-sm transition-colors">
                            {{ $confirmText }}
                        </button>
                        <button wire:click="cancel" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border-2 border-zinc-300 dark:border-zinc-600 shadow-sm px-5 py-2.5 bg-white dark:bg-zinc-900 text-base font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                            {{ $cancelText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>