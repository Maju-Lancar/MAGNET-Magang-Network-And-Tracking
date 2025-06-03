<?php

use function Livewire\Volt\{layout, state};

layout('components.layouts.user.main');

$mahasiswa = auth('mahasiswa')->user();
$berkas = $mahasiswa->berkasPengajuanMagang()->latest('updated_at')->first();
$formPengajuan = $berkas?->formPengajuanMagang()->first();

state([
    'mahasiswa' => $mahasiswa,
    'status' => $mahasiswa->status_magang,
    'pengajuanMagang' => $formPengajuan?->status, // bisa null jika belum pernah ajukan
    'alasanDitolak' => $formPengajuan?->keterangan, // jika kamu simpan alasan di field ini
]);

?>

<div class="text-black flex flex-col gap-6">
    <x-slot:user>mahasiswa</x-slot:user>

    <h2 class="text-base leading-6 font-bold">Pengajuan Magang</h2>

    @if ($status == 'belum magang')
        @if ($pengajuanMagang === null)
            {{-- Belum pernah mengajukan --}}
            <x-mahasiswa.pengajuan-magang.belum-diajukan />
        @elseif ($pengajuanMagang == 'menunggu' || $pengajuanMagang == 'diterima')
            {{-- Sedang diproses --}}
            <x-mahasiswa.pengajuan-magang.sedang-diproses />
        @elseif ($pengajuanMagang == 'ditolak')
            {{-- Ditolak, tampilkan alasan dan form ulang --}}
            <div class="bg-red-100 p-4 rounded shadow">
                <p class="text-red-600 font-semibold">Pengajuan Anda ditolak.</p>
                <p class="text-sm mt-1">Alasan: {{ $alasanDitolak ?? 'Tidak ada alasan yang diberikan.' }}</p>
            </div>
            <x-mahasiswa.pengajuan-magang.belum-diajukan />
        @endif
    @elseif ($status == 'sedang magang')
        {{-- Sedang magang, tidak perlu ajukan lagi --}}
        <div class="card bg-white shadow-md p-5 text-black flex flex-col gap-5">
            <p>Status magang Anda: Sedang Magang</p>
            <flux:text>Anda sedang menjalani magang. Tidak perlu mengajukan lagi.</flux:text>
        </div>
    @else
        {{-- Status magang tidak dikenali --}}
        <div class="card bg-white shadow-md p-5 text-black flex flex-col gap-5">
            <p>Status magang Anda tidak dikenali.</p>
            <flux:text>Silakan hubungi admin untuk informasi lebih lanjut.</flux:text>
        </div>
    @endif
</div>
