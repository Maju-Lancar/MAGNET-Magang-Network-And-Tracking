<x-layouts.mahasiswa.mahasiswa>
    <flux:breadcrumbs class="mb-5">
        <flux:breadcrumbs.item href="{{ route('mahasiswa.log-mahasiswa') }}" icon="home" icon:variant="outline" />
        <flux:breadcrumbs.item href="{{ route('mahasiswa.log-mahasiswa') }}" class="text-black">Log Mahasiswa</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="w-full overflow-x-hidden px-4">
        <div class="flex flex-col w-full max-w-4xl mx-auto p-[30px] gap-[30px] rounded-[15px] bg-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.10),0px_2px_4px_-1px_rgba(0,0,0,0.06)]">
            <h2 class="text-lg font-semibold text-black">Log Magang</h2>

            <!-- Form -->
            <form id="logForm" class="flex flex-col items-start gap-[30px] w-full text-black">
                
                <!-- Hari, Tanggal -->
                <div class="flex flex-col items-start gap-[4px] w-full">
                    <label for="tanggal" class="text-sm font-medium text-black">Hari, Tanggal</label>
                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        class="w-full border border-gray-300 rounded-md p-2 shadow-sm text-black"
                        placeholder="Pilih tanggal"
                    />
                </div>

                <!-- Jam Masuk -->
                <div class="flex flex-col items-start gap-[4px] w-full">
                    <label for="jam_masuk" class="text-sm font-medium">Jam Masuk</label>
                    <input type="time" id="jam_masuk" name="jam_masuk"
                           class="w-full border border-gray-300 rounded-md p-2 shadow-sm" />
                </div>

                <!-- Jam Pulang -->
                <div class="flex flex-col items-start gap-[4px] w-full">
                    <label for="jam_pulang" class="text-sm font-medium">Jam Pulang</label>
                    <input type="time" id="jam_pulang" name="jam_pulang"
                           class="w-full border border-gray-300 rounded-md p-2 shadow-sm" />
                </div>

                <!-- Kegiatan -->
                <div class="flex flex-col items-start gap-[4px] w-full">
                    <label for="kegiatan" class="text-sm font-medium">Kegiatan</label>
                    <textarea id="kegiatan" name="kegiatan" placeholder="Isi kegiatan anda hari ini"
                              class="w-full border border-gray-300 rounded-md p-2 shadow-sm resize-y min-h-[80px]"></textarea>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end gap-4 w-full">
                    <button type="submit" class="bg-cyan-700 hover:bg-cyan-800 text-white px-4 py-2 rounded-md shadow">
                        Ajukan Tanda Tangan
                    </button>
                </div>
            </form>
        </div>
    </div>

   
</x-layouts.mahasiswa.mahasiswa>
