@php
    $inputName = $inputName ?? 'city';
    $label = $label ?? 'Локација';
    $selectedValue = trim((string) ($selectedValue ?? ''));
    $selectedLabel = $selectedLabel ?? ($selectedValue !== '' ? $selectedValue : null);
    $placeholder = $placeholder ?? 'Избери локација';
    $containerClass = $containerClass ?? '';
    $triggerClass = $triggerClass ?? '';
    $panelClass = $panelClass ?? '';
    $iconWrapperClass = $iconWrapperClass ?? 'pointer-events-none absolute left-4 top-1/2 z-10 -translate-y-1/2 text-slate-400';
    $iconClass = $iconClass ?? 'h-5 w-5';
    $menuMaxHeightClass = $menuMaxHeightClass ?? 'max-h-[26rem]';
@endphp

<div class="{{ $containerClass }}" data-location-filter>
    <span class="mb-2 block text-sm font-semibold text-slate-700">{{ $label }}</span>

    <div class="relative">
        <input type="hidden" name="{{ $inputName }}" value="{{ $selectedValue }}" data-location-filter-input>

        <span class="{{ $iconWrapperClass }}">
            <svg viewBox="0 0 20 20" fill="currentColor" class="{{ $iconClass }}" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 2.75a5.75 5.75 0 00-5.75 5.75c0 4.21 4.615 8.047 5.132 8.463a1 1 0 001.236 0c.517-.416 5.132-4.252 5.132-8.463A5.75 5.75 0 0010 2.75zm0 7.5a1.75 1.75 0 100-3.5 1.75 1.75 0 000 3.5z" clip-rule="evenodd" />
            </svg>
        </span>

        <button
            type="button"
            class="{{ $triggerClass }}"
            data-location-filter-trigger
            aria-haspopup="true"
            aria-expanded="false"
        >
            <span data-location-filter-label data-placeholder="{{ $placeholder }}">
                {{ $selectedLabel ?: $placeholder }}
            </span>

            <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5 shrink-0 text-slate-400 transition duration-200" data-location-filter-chevron aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <div class="hidden {{ $panelClass }}" data-location-filter-panel>
            <div class="overflow-visible rounded-[1.55rem] border border-slate-200 bg-white p-2 shadow-[0_30px_60px_-32px_rgba(15,23,42,0.38)]">
                <div class="mb-2 border-b border-slate-100 pb-2">
                    <button
                        type="button"
                        class="flex w-full items-center justify-between rounded-[1rem] px-3.5 py-3 text-left text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:text-slate-900"
                        data-location-filter-select
                        data-location-value=""
                        data-location-label="{{ $placeholder }}"
                    >
                        <span>Сите локации</span>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">Сите</span>
                    </button>
                </div>

                <div class="{{ $menuMaxHeightClass }} overflow-y-auto pr-1">
                    <div class="space-y-1.5">
                        @foreach ($locationTree as $city)
                            @php
                                $cityName = $city['name'];
                                $cityMunicipalities = $city['municipalities'] ?? [];
                                $citySelected = $selectedValue === $cityName;
                                $cityHasSelectedMunicipality = collect($cityMunicipalities)->contains(
                                    fn (array $municipality): bool => $selectedValue === ($municipality['name'] ?? '')
                                );
                            @endphp

                            @if ($cityMunicipalities === [])
                                <button
                                    type="button"
                                    class="flex w-full items-center justify-between rounded-[1rem] border border-transparent px-3.5 py-3 text-left text-sm font-semibold transition hover:border-emerald-100 hover:bg-emerald-50/80 hover:text-emerald-700 {{ $citySelected ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-slate-700' }}"
                                    data-location-filter-select
                                    data-location-value="{{ $cityName }}"
                                    data-location-label="{{ $cityName }}"
                                >
                                    <span>{{ $cityName }}</span>
                                    @if ($citySelected)
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-emerald-600" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </button>
                            @else
                                <div class="relative" data-location-item @if($citySelected || $cityHasSelectedMunicipality) data-location-item-selected="true" @endif>
                                    <button
                                        type="button"
                                        class="flex w-full items-center justify-between rounded-[1rem] border border-transparent px-3.5 py-3 text-left text-sm font-semibold transition hover:border-emerald-100 hover:bg-emerald-50/80 hover:text-emerald-700 {{ $citySelected ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-slate-700' }}"
                                        data-location-filter-submenu-toggle
                                        aria-expanded="false"
                                        aria-label="Прикажи општини за {{ $cityName }}"
                                    >
                                        <span class="flex min-w-0 flex-col">
                                            <span class="truncate">{{ $cityName }}</span>
                                            <span class="mt-0.5 text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">
                                                {{ count($cityMunicipalities) }} општини
                                            </span>
                                        </span>

                                        <span class="ml-3 inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition">
                                            <svg viewBox="0 0 20 20" fill="currentColor" class="h-[1.05rem] w-[1.05rem] transition duration-200" data-location-filter-submenu-chevron aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.22 5.97a.75.75 0 011.06 0l4.25 4.25a.75.75 0 010 1.06l-4.25 4.25a.75.75 0 01-1.06-1.06L10.94 10 7.22 6.28a.75.75 0 010-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>

                                    <div class="mt-2 hidden rounded-[1.15rem] border border-slate-200 bg-slate-50/80 p-2.5" data-location-submenu>
                                        <button
                                            type="button"
                                            class="mb-2 flex w-full items-center justify-between rounded-[0.95rem] border border-transparent bg-white px-3 py-3 text-left text-sm font-semibold text-slate-700 transition hover:border-emerald-100 hover:bg-emerald-50/80 hover:text-emerald-700 {{ $citySelected ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : '' }}"
                                            data-location-filter-select
                                            data-location-value="{{ $cityName }}"
                                            data-location-label="{{ $cityName }}"
                                        >
                                            <span>{{ $cityName }} - сите општини</span>
                                            @if ($citySelected)
                                                <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-emerald-600" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                                                </svg>
                                            @endif
                                        </button>

                                        <div class="mb-2 px-2 py-1 text-[11px] font-bold uppercase tracking-[0.22em] text-slate-400">
                                            {{ $cityName }} општини
                                        </div>

                                        <div class="space-y-1">
                                            @foreach ($cityMunicipalities as $municipality)
                                                @php
                                                    $municipalityName = $municipality['name'];
                                                    $municipalitySelected = $selectedValue === $municipalityName;
                                                @endphp

                                                <button
                                                    type="button"
                                                    class="flex w-full items-center justify-between rounded-[0.95rem] border border-transparent bg-white px-3 py-2.5 text-left text-sm font-medium transition hover:border-emerald-100 hover:bg-emerald-50/80 hover:text-emerald-700 {{ $municipalitySelected ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'text-slate-700' }}"
                                                    data-location-filter-select
                                                    data-location-value="{{ $municipalityName }}"
                                                    data-location-label="{{ $municipalityName }}"
                                                >
                                                    <span>{{ $municipalityName }}</span>
                                                    @if ($municipalitySelected)
                                                        <svg viewBox="0 0 20 20" fill="currentColor" class="h-4 w-4 text-emerald-600" aria-hidden="true">
                                                            <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.42l-7.2 7.2a1 1 0 01-1.415 0l-3-3a1 1 0 111.414-1.42l2.293 2.294 6.493-6.494a1 1 0 011.415 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const desktopMedia = window.matchMedia('(min-width: 768px)');

                document.querySelectorAll('[data-location-filter]').forEach((root) => {
                    if (root.dataset.locationFilterReady === 'true') {
                        return;
                    }

                    root.dataset.locationFilterReady = 'true';

                    const input = root.querySelector('[data-location-filter-input]');
                    const trigger = root.querySelector('[data-location-filter-trigger]');
                    const panel = root.querySelector('[data-location-filter-panel]');
                    const label = root.querySelector('[data-location-filter-label]');
                    const chevron = root.querySelector('[data-location-filter-chevron]');

                    if (!input || !trigger || !panel || !label || !chevron) {
                        return;
                    }

                    const closeSubmenus = () => {
                        root.querySelectorAll('[data-location-item]').forEach((item) => {
                            const submenu = item.querySelector('[data-location-submenu]');
                            const toggle = item.querySelector('[data-location-filter-submenu-toggle]');
                            const submenuChevron = item.querySelector('[data-location-filter-submenu-chevron]');

                            if (submenu) {
                                submenu.classList.add('hidden');
                            }

                            if (toggle) {
                                toggle.setAttribute('aria-expanded', 'false');
                            }

                            if (submenuChevron) {
                                submenuChevron.classList.remove('rotate-90');
                            }
                        });
                    };

                    const closePanel = () => {
                        panel.classList.add('hidden');
                        trigger.setAttribute('aria-expanded', 'false');
                        chevron.classList.remove('rotate-180');
                        closeSubmenus();
                    };

                    const openPanel = () => {
                        panel.classList.remove('hidden');
                        trigger.setAttribute('aria-expanded', 'true');
                        chevron.classList.add('rotate-180');

                        const selectedItem = root.querySelector('[data-location-item-selected="true"]');

                        if (selectedItem) {
                            openSubmenu(selectedItem);
                        }
                    };

                    const openSubmenu = (item) => {
                        closeSubmenus();

                        const submenu = item.querySelector('[data-location-submenu]');
                        const toggle = item.querySelector('[data-location-filter-submenu-toggle]');
                        const submenuChevron = item.querySelector('[data-location-filter-submenu-chevron]');

                        if (!submenu || !toggle) {
                            return;
                        }

                        submenu.classList.remove('hidden');
                        toggle.setAttribute('aria-expanded', 'true');

                        if (submenuChevron) {
                            submenuChevron.classList.add('rotate-90');
                        }
                    };

                    trigger.addEventListener('click', () => {
                        if (panel.classList.contains('hidden')) {
                            openPanel();
                            return;
                        }

                        closePanel();
                    });

                    root.querySelectorAll('[data-location-filter-select]').forEach((button) => {
                        button.addEventListener('click', () => {
                            input.value = button.dataset.locationValue ?? '';
                            label.textContent = button.dataset.locationLabel ?? label.dataset.placeholder ?? '';
                            closePanel();
                        });
                    });

                    root.querySelectorAll('[data-location-filter-submenu-toggle]').forEach((toggle) => {
                        toggle.addEventListener('click', (event) => {
                            event.preventDefault();
                            event.stopPropagation();

                            const item = toggle.closest('[data-location-item]');

                            if (!item) {
                                return;
                            }

                            const submenu = item.querySelector('[data-location-submenu]');
                            const isOpen = submenu && !submenu.classList.contains('hidden');

                            if (isOpen) {
                                closeSubmenus();
                                return;
                            }

                            openSubmenu(item);
                        });
                    });

                    root.querySelectorAll('[data-location-item]').forEach((item) => {
                        item.addEventListener('mouseenter', () => {
                            if (!desktopMedia.matches) {
                                return;
                            }

                            openSubmenu(item);
                        });

                        item.addEventListener('mouseleave', () => {
                            if (!desktopMedia.matches) {
                                return;
                            }

                            closeSubmenus();
                        });
                    });

                    document.addEventListener('click', (event) => {
                        if (root.contains(event.target)) {
                            return;
                        }

                        closePanel();
                    });

                    document.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') {
                            closePanel();
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
