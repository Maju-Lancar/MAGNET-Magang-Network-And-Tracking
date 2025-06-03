<?php

use function Livewire\Volt\{layout, rules, state, protect};

layout('components.layouts.user.main');

state([
    'mahasiswa' => auth('mahasiswa')->user(),
]);

?>

<div class="text-black flex flex-col gap-6">
    <x-slot:user>admin</x-slot:user>
    <div class="card bg-white shadow-md">
        <form action="{{ route('mahasiswa.store-pengajuan-magang') }}" method="POST" enctype="multipart/form-data"
            id="pengajuan-form">
            @csrf
            <div class="card-body">
                <flux:input readonly value="{{ $mahasiswa->nama }}" type="text" label="Nama Lengkap" />
                <flux:input readonly value="{{ $mahasiswa->nim }}" type="text" label="NIM" />
                <flux:input readonly value="Teknologi Informasi" type="text" label="Jurusan" />
                <flux:input readonly value="{{ $mahasiswa->program_studi ?? '-' }}" type="text"
                    label="Program Studi" />

                <flux:field>
                    <flux:input type="file" name="cv" label="CV" accept=".pdf"
                        description="Format: PDF. Maksimal ukuran file: 2 MB." />
                    @error('cv')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:input type="file" name="transkrip_nilai" label="Transkrip Nilai" accept=".pdf"
                        description="Format: PDF. Maksimal ukuran file: 2 MB." />
                    @error('transkrip_nilai')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>

                <flux:field>
                    <flux:input type="file" name="portfolio" label="Portofolio (Opsional)" accept=".pdf"
                        description="Format: PDF. Maksimal ukuran file: 2 MB." />
                    @error('portfolio')
                        <flux:error>{{ $message }}</flux:error>
                    @enderror
                </flux:field>
            </div>

            <div class="card-actions flex justify-end p-5">
                <flux:button type="button" class="bg-magnet-sky-teal! text-white!" onclick="window.history.back();">
                    Kembali</flux:button>
                <flux:modal.trigger name="confirm-form" class="mr-2">
                    <flux:button type="button" class="bg-magnet-sky-teal! text-white!">Kirim</flux:button>
                </flux:modal.trigger>

                <flux:modal name="confirm-form" class="min-w-[22rem]">
                    <div class="space-y-6">
                        <div>
                            <flux:heading size="lg">Kirim Pengajuan Magang</flux:heading>
                            <flux:text class="mt-2">
                                <p>Apakah anda sudah yakin ingin mengirim pengajuan magang?</p>
                            </flux:text>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button variant="ghost">Batal</flux:button>
                            </flux:modal.close>
                            <flux:modal.close>
                                <flux:button type="button" onclick="document.getElementById('pengajuan-form').submit()"
                                    class="bg-magnet-sky-teal! text-white!">
                                    Ya, Kirim
                                </flux:button>
                            </flux:modal.close>
                        </div>
                    </div>
                </flux:modal>
            </div>
        </form>
    </div>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    @if ($errors->any())
        <script>
            let errors = '';
            @foreach ($errors->all() as $error)
                errors += '{{ $error }}\n';
            @endforeach
            alert('Terjadi kesalahan:\n' + errors);
        </script>
    @endif
</div>
