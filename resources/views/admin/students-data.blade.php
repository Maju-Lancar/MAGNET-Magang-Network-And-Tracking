<x-layouts.admin.admin>
    <flux:breadcrumbs class="mb-5">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" icon:variant="outline" />
        <flux:breadcrumbs.item href="{{ route('students-data') }}" class="text-black">Kelola Data Mahasiswa
        </flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <h1 class="text-base font-bold leading-6 text-black">Kelola Data Mahasiswa</h1>

    <div class="flex justify-between mt-4">
        <div class="flex gap-3">
            <flux:input class="rounded-3xl!" as="button" placeholder="Cari Data Mahasiswa" icon="magnifying-glass" />
            <flux:button variant="primary" class="bg-white! text-black! w-17 rounded-full!"
                icon="arrow-down-wide-narrow"></flux:button>
            <flux:button variant="primary" class="bg-white! text-black! w-17 rounded-full!" icon="funnel-plus">
            </flux:button>
        </div>
        <div class="flex gap-3">
            <flux:button variant="primary" class="bg-[#219EBC]!" icon="plus">Tambah Mahasiswa</flux:button>
            <flux:button variant="primary" class="bg-[#219EBC]!" icon="download">Import</flux:button>
            <flux:button variant="primary" class="bg-[#219EBC]!" icon="upload">Export</flux:button>
        </div>
    </div>

    <div class="overflow-y-auto flex flex-col items-center mt-4 ">
        <table class="table-auto w-full rounded-lg shadow">
            <thead class="bg-white text-black">
                <tr class="border-b">
                    <th class="text-center px-6 py-3">No</th>
                    <th class="text-left px-6 py-3">Nama</th>
                    <th class="text-left px-6 py-3">NIM</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-center px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white text-black">
                @for ($i = 0; $i < 10; $i++)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 text-center">{{ $i + 1 }}</td>
                        <td class="px-6 py-3">Budi Gunawan Herlambang Sejahtera Saputra Widodo</td>
                        <td class="px-6 py-3">1291921281281</td>
                        <td class="px-6 py-3">
                            <flux:badge color="green" variant="solid">Sedang magang</flux:badge>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <flux:button icon="ellipsis-vertical" />
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="flex mt-6">
            <flux:button icon="chevron-left" />
            @for ($i = 0; $i < 5; $i++)
                <flux:button variant="ghost">{{ $i + 1 }}</flux:button>
            @endfor
            <flux:button icon="chevron-right" />
        </div>
    </div>
    </div>

</x-layouts.admin.admin>