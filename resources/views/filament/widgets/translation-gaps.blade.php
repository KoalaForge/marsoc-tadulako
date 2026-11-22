<x-filament-widgets::widget>
    <x-filament::section heading="Terjemahan belum lengkap" :description="count($gaps) ? 'Satu bahasa terisi, bahasa lain kosong — situs EN akan jatuh kembali ke teks Indonesia.' : null">
        @if (count($gaps) === 0)
            <p class="text-sm text-gray-500 dark:text-gray-400">Semua konten sudah dua bahasa.</p>
        @else
            <ul class="divide-y divide-gray-200 dark:divide-white/10">
                @foreach ($gaps as $gap)
                    <li class="flex items-center justify-between gap-4 py-2 text-sm">
                        <span>
                            <span class="font-medium">{{ $gap['type'] }}</span> · {{ $gap['label'] }}
                            <span class="text-gray-500 dark:text-gray-400">({{ implode(', ', $gap['fields']) }})</span>
                        </span>
                        <a href="{{ $gap['url'] }}" class="text-primary-600 dark:text-primary-400 hover:underline">Edit</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
