<?php

use App\Models\{KontrakMagang, Magang};
use Illuminate\Support\Facades\{Auth, Storage};
use Livewire\WithFileUploads;
use function Livewire\Volt\{state, mount, rules, uses};

uses(WithFileUploads::class);

state([
    'bukti_surat_selesai_magang' => null,
    'mahasiswa' => null,
    'kontrak_magang' => null,
]);

rules([
    'bukti_surat_selesai_magang' => 'required|file|mimes:pdf|max:2048',
]);

mount(function () {
    $this->mahasiswa = Auth::guard('mahasiswa')->user();

    if (!$this->mahasiswa) {
        session()->flash('error', 'Anda harus login sebagai mahasiswa.');
        return;
    }

    // Get active internship contract
    $this->kontrak_magang = KontrakMagang::with(['magang.perusahaan'])
        ->where('mahasiswa_id', $this->mahasiswa->id)
        ->first();
});

$completeInternship = function () {
    try {
        if (!$this->mahasiswa) {
            session()->flash('error', 'Data mahasiswa tidak ditemukan.');
            return;
        }

        if (!$this->kontrak_magang) {
            session()->flash('error', 'Kontrak magang tidak ditemukan.');
            return;
        }

        // Check if student is currently doing internship
        if (!$this->isCurrentlyDoingInternship()) {
            session()->flash('error', 'Anda tidak sedang melakukan magang aktif.');
            return;
        }

        $this->validate();

        // Store the uploaded file
        $filePath = $this->bukti_surat_selesai_magang->store('surat-selesai-magang', 'public');

        // Update mahasiswa status
        $updateData = ['status_magang' => 'selesai magang'];

        if (\Schema::hasColumn('mahasiswa', 'bukti_surat_selesai_magang')) {
            $updateData['bukti_surat_selesai_magang'] = $filePath;
        }

        $this->mahasiswa->update($updateData);

        // Update kontrak magang end date
        $kontrakUpdateData = ['waktu_akhir' => now()];

        if (\Schema::hasColumn('kontrak_magang', 'status')) {
            $kontrakUpdateData['status'] = 'selesai';
        }

        $this->kontrak_magang->update($kontrakUpdateData);

        session()->flash('success', 'Status magang berhasil diperbarui menjadi selesai. Surat selesai magang telah tersimpan.');

        // Reset form
        $this->reset(['bukti_surat_selesai_magang']);

        // Refresh data
        $this->mahasiswa->refresh();

        // Emit event to parent component to refresh
        $this->dispatch('refreshParent');
    } catch (\Illuminate\Validation\ValidationException $e) {
        session()->flash('error', 'Error validasi: ' . implode(', ', array_flatten($e->errors())));
    } catch (\Illuminate\Database\QueryException $e) {
        session()->flash('error', 'Error database: ' . $e->getMessage());
    } catch (\Exception $e) {
        session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')');
    }
};

$getInternshipInfo = function () {
    if (!$this->kontrak_magang || !$this->kontrak_magang->magang || !$this->kontrak_magang->magang->perusahaan) {
        return 'Informasi magang tidak tersedia';
    }

    $perusahaan = $this->kontrak_magang->magang->perusahaan;
    return "{$perusahaan->nama} - {$perusahaan->lokasi}";
};

$isCurrentlyDoingInternship = function () {
    if (!$this->mahasiswa || !$this->kontrak_magang) {
        return false;
    }

    // Check if student status indicates active internship
    $activeStatuses = ['sedang magang', 'magang berlangsung', 'aktif magang'];
    $currentStatus = strtolower($this->mahasiswa->status_magang ?? '');

    // Check mahasiswa status
    $isActiveByStatus = in_array($currentStatus, $activeStatuses);

    // Check if contract is still active (not finished)
    $contractStatus = strtolower($this->kontrak_magang->status ?? 'aktif');
    $isActiveByContract = !in_array($contractStatus, ['selesai', 'completed', 'finished']);

    // Check if end date hasn't passed or is null (ongoing)
    $isActiveByDate = is_null($this->kontrak_magang->waktu_akhir) || $this->kontrak_magang->waktu_akhir->isFuture();

    return $isActiveByStatus && $isActiveByContract && $isActiveByDate;
};

$hasCompletedInternship = function () {
    if (!$this->mahasiswa) {
        return false;
    }

    $completedStatuses = ['selesai', 'selesai magang', 'completed', 'finished'];
    $currentStatus = strtolower($this->mahasiswa->status_magang ?? '');

    return in_array($currentStatus, $completedStatuses);
};

?>

<div class="space-y-4 border-t pt-4 mt-4">
    <h3 class="text-md font-semibold text-gray-700">Dokumen Selesai Magang</h3>

    @if (!$mahasiswa)
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
            Anda harus login sebagai mahasiswa untuk mengakses fitur ini.
        </div>
    @else
        @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                {{ session('error') }}
            </div>
        @endif

        @if ($this->hasCompletedInternship())
            <!-- Student has already completed internship -->
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                <strong>Status:</strong> Magang telah selesai<br>
                @if ($mahasiswa->bukti_surat_selesai_magang)
                    <strong>Surat selesai magang:</strong>
                    <a href="{{ Storage::url($mahasiswa->bukti_surat_selesai_magang) }}" target="_blank"
                        class="text-blue-600 hover:underline">
                        Lihat dokumen
                    </a>
                @endif

                @if ($kontrak_magang)
                    <br><strong>Lokasi magang:</strong> {{ $this->getInternshipInfo() }}
                    <br><strong>Periode:</strong>
                    {{ $kontrak_magang->waktu_awal->format('d M Y') }} -
                    {{ $kontrak_magang->waktu_akhir ? $kontrak_magang->waktu_akhir->format('d M Y') : 'Sekarang' }}
                @endif
            </div>
        @elseif ($this->isCurrentlyDoingInternship())
            <!-- Student is currently doing internship - show upload form -->
            @if ($kontrak_magang)
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50">
                    <strong>Mahasiswa:</strong> {{ $mahasiswa->nama }} ({{ $mahasiswa->nim }})<br>
                    <strong>Status saat ini:</strong>
                    {{ ucwords(str_replace('_', ' ', $mahasiswa->status_magang)) }}<br>
                    <strong>Lokasi magang:</strong> {{ $this->getInternshipInfo() }}<br>
                    <strong>Mulai magang:</strong> {{ $kontrak_magang->waktu_awal->format('d M Y') }}<br>
                </div>

                <!-- File upload form for completing internship -->
                <form wire:submit.prevent="completeInternship">
                    <div>
                        <flux:field>
                            <flux:label>Bukti Surat Selesai Magang (PDF)</flux:label>
                            <flux:input type="file" wire:model="bukti_surat_selesai_magang" accept=".pdf" />
                            <flux:description>
                                Upload surat keterangan selesai magang dalam format PDF (maksimal 2MB)
                            </flux:description>

                            @if ($bukti_surat_selesai_magang && !$errors->has('bukti_surat_selesai_magang'))
                                <div class="mt-2 text-sm text-green-600">
                                    File terpilih: {{ $bukti_surat_selesai_magang->getClientOriginalName() }}
                                </div>
                            @endif

                            <div wire:loading wire:target="bukti_surat_selesai_magang"
                                class="text-sm text-blue-600 mt-2">
                                Mengunggah file...
                            </div>

                            <flux:error for="bukti_surat_selesai_magang" />
                        </flux:field>
                    </div>

                    <div class="flex justify-end pt-4">
                        <flux:button type="submit" wire:loading.attr="disabled"
                            :disabled="!$bukti_surat_selesai_magang">
                            <span wire:loading.remove wire:target="completeInternship">
                                Selesaikan Magang
                            </span>
                            <span wire:loading wire:target="completeInternship">
                                Memproses...
                            </span>
                        </flux:button>
                    </div>
                </form>
            @else
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                    <strong>Peringatan:</strong> Kontrak magang tidak ditemukan untuk mahasiswa
                    {{ $mahasiswa->nama ?? 'Unknown' }}<br>
                    <strong>Mahasiswa ID:</strong> {{ $mahasiswa->id ?? 'N/A' }}<br>
                    <strong>Status mahasiswa:</strong> {{ $mahasiswa->status_magang ?? 'N/A' }}
                </div>
            @endif
        @else
            <!-- Student is not currently doing internship and hasn't completed one -->
            <div class="p-4 mb-4 text-sm text-gray-800 rounded-lg bg-gray-50">
                <strong>Status:</strong> Tidak sedang melakukan magang<br>
                <strong>Status mahasiswa:</strong>
                {{ ucwords(str_replace('_', ' ', $mahasiswa->status_magang ?? 'Belum ada status')) }}<br>

                @if (!$kontrak_magang)
                    <div class="mt-2 text-sm text-yellow-700">
                        Belum ada kontrak magang yang terdaftar.
                    </div>
                @else
                    <strong>Kontrak terakhir:</strong> {{ $this->getInternshipInfo() }}<br>
                    @if ($kontrak_magang->waktu_akhir)
                        <strong>Berakhir:</strong> {{ $kontrak_magang->waktu_akhir->format('d M Y') }}
                    @endif
                @endif
            </div>
        @endif
    @endif
</div>
