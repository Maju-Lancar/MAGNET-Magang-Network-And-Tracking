<?php

use App\Models\{Perusahaan, KontrakMagang, Magang};
use Illuminate\Support\Facades\{Auth, Log};
use Livewire\WithFileUploads;
use function Livewire\Volt\{state, mount, rules, uses};

uses(WithFileUploads::class);

state([
    'company_type' => '',
    'selected_company_id' => '',
    'company_name' => '',
    'company_address' => '',
    'bidang_industri' => '',
    'lokasi_magang' => '',
    'surat_izin_magang' => null,
    'partner_companies' => [],
    'mahasiswa' => null,
    'debug_info' => [], // Add debug state
]);

rules([
    'company_name' => 'required_if:company_type,non_partner|string|max:255',
    'company_address' => 'required_if:company_type,non_partner|string',
    'bidang_industri' => 'required_if:company_type,non_partner|string',
    'selected_company_id' => 'required_if:company_type,partner|exists:perusahaan,id',
    'lokasi_magang' => 'required|string|max:255',
    'surat_izin_magang' => 'required|file|mimes:pdf|max:2048',
]);

mount(function () {
    try {
        Log::info('Mount function started');

        $this->mahasiswa = Auth::guard('mahasiswa')->user();

        Log::info('Auth check completed', [
            'mahasiswa_exists' => $this->mahasiswa ? true : false,
            'mahasiswa_id' => $this->mahasiswa->id ?? null,
            'auth_guard' => 'mahasiswa',
        ]);

        if (!$this->mahasiswa) {
            Log::warning('No authenticated mahasiswa found');
            session()->flash('error', 'Anda harus login sebagai mahasiswa.');
            return;
        }

        $this->partner_companies = Perusahaan::where('kategori', 'mitra')->get();

        Log::info('Partner companies loaded', [
            'count' => $this->partner_companies->count(),
            'companies' => $this->partner_companies->pluck('nama', 'id')->toArray(),
        ]);

        $this->debug_info[] = 'Mount completed successfully';
    } catch (\Exception $e) {
        Log::error('Error in mount function', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ]);

        session()->flash('error', 'Terjadi kesalahan saat memuat halaman. Error: ' . $e->getMessage());
        $this->debug_info[] = 'Mount failed: ' . $e->getMessage();
    }
});

$save = function () {
    try {
        Log::info('Save function started', [
            'mahasiswa_id' => $this->mahasiswa->id ?? null,
            'form_data' => [
                'company_type' => $this->company_type,
                'selected_company_id' => $this->selected_company_id,
                'company_name' => $this->company_name,
                'lokasi_magang' => $this->lokasi_magang,
                'has_file' => $this->surat_izin_magang ? true : false,
            ],
        ]);

        // Step 1: Check mahasiswa authentication
        if (!$this->mahasiswa) {
            Log::error('Save failed: No mahasiswa data found');
            session()->flash('error', 'Data mahasiswa tidak ditemukan.');
            $this->debug_info[] = 'Error: No mahasiswa data found';
            return;
        }

        Log::info('Mahasiswa data check passed', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'current_status' => $this->mahasiswa->status_magang,
        ]);

        // Step 2: Check internship status
        if ($this->mahasiswa->status_magang === 'sedang magang') {
            Log::warning('Save failed: Student already has active internship', [
                'mahasiswa_id' => $this->mahasiswa->id,
                'current_status' => $this->mahasiswa->status_magang,
            ]);
            session()->flash('error', 'Anda sudah memiliki status magang aktif.');
            $this->debug_info[] = 'Error: Student already has active internship status';
            return;
        }

        // Step 3: Validate form data
        Log::info('Starting form validation');
        try {
            $this->validate();
            Log::info('Form validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Form validation failed', [
                'errors' => $e->errors(),
                'failed_rules' => $e->validator->failed(),
            ]);
            $this->debug_info[] = 'Validation failed: ' . json_encode($e->errors());
            throw $e;
        }

        // Step 4: Check existing contracts
        $existingContract = KontrakMagang::where('mahasiswa_id', $this->mahasiswa->id)->first();
        if ($existingContract) {
            Log::error('Save failed: Student already has active contract', [
                'mahasiswa_id' => $this->mahasiswa->id,
                'existing_contract_id' => $existingContract->id,
                'contract_created_at' => $existingContract->created_at,
            ]);
            session()->flash('error', 'Anda sudah memiliki kontrak magang aktif.');
            $this->debug_info[] = 'Error: Active contract already exists (ID: ' . $existingContract->id . ')';
            return;
        }

        Log::info('No existing contract found, proceeding...');

        $magang_id = null;

        // Step 5: Handle company selection/creation
        if ($this->company_type === 'partner') {
            Log::info('Processing partner company', [
                'selected_company_id' => $this->selected_company_id,
            ]);

            $selectedCompany = Perusahaan::find($this->selected_company_id);
            if (!$selectedCompany) {
                Log::error('Partner company not found', [
                    'selected_company_id' => $this->selected_company_id,
                ]);
                session()->flash('error', 'Perusahaan mitra tidak ditemukan.');
                $this->debug_info[] = 'Error: Partner company not found (ID: ' . $this->selected_company_id . ')';
                return;
            }

            Log::info('Partner company found', [
                'company_id' => $selectedCompany->id,
                'company_name' => $selectedCompany->nama,
            ]);

            $magang = Magang::where('perusahaan_id', $selectedCompany->id)->first();
            if ($magang) {
                $magang_id = $magang->id;
                Log::info('Existing magang found for partner company', [
                    'magang_id' => $magang_id,
                ]);
            } else {
                Log::warning('No existing magang found for partner company', [
                    'company_id' => $selectedCompany->id,
                ]);
                $this->debug_info[] = 'Warning: No existing magang program for selected partner company';
            }
        } else {
            Log::info('Processing non-partner company', [
                'company_name' => $this->company_name,
                'bidang_industri' => $this->bidang_industri,
            ]);

            try {
                // Create new company
                $newCompany = Perusahaan::create([
                    'nama' => $this->company_name,
                    'bidang_industri' => $this->bidang_industri,
                    'lokasi' => $this->company_address,
                    'kategori' => 'non_mitra',
                    'rating' => 0,
                ]);

                Log::info('New company created', [
                    'company_id' => $newCompany->id,
                    'company_name' => $newCompany->nama,
                ]);

                // Create new magang program
                $magang = Magang::create([
                    'nama' => "Magang di {$this->company_name}",
                    'deskripsi' => "Program magang di {$this->company_name}",
                    'persyaratan' => 'Sesuai dengan persyaratan perusahaan',
                    'perusahaan_id' => $newCompany->id,
                ]);

                $magang_id = $magang->id;

                Log::info('New magang program created', [
                    'magang_id' => $magang_id,
                    'company_id' => $newCompany->id,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to create new company/magang', [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'company_data' => [
                        'nama' => $this->company_name,
                        'bidang_industri' => $this->bidang_industri,
                        'lokasi' => $this->company_address,
                    ],
                ]);
                $this->debug_info[] = 'Error creating company: ' . $e->getMessage();
                throw $e;
            }
        }

        // Step 6: Validate magang_id
        if (!$magang_id) {
            Log::error('No magang_id available for contract creation', [
                'company_type' => $this->company_type,
                'selected_company_id' => $this->selected_company_id,
            ]);
            session()->flash('error', 'Tidak dapat membuat kontrak magang. Program magang tidak ditemukan.');
            $this->debug_info[] = 'Error: No magang program available for contract';
            return;
        }

        // Step 7: Create internship contract
        Log::info('Creating internship contract', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'magang_id' => $magang_id,
        ]);

        try {
            $kontrak = KontrakMagang::create([
                'mahasiswa_id' => $this->mahasiswa->id,
                'magang_id' => $magang_id,
                'waktu_awal' => now(),
                'waktu_akhir' => now()->addMonths(3),
            ]);

            Log::info('Internship contract created successfully', [
                'contract_id' => $kontrak->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'magang_id' => $magang_id,
                'start_date' => $kontrak->waktu_awal,
                'end_date' => $kontrak->waktu_akhir,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create internship contract', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'contract_data' => [
                    'mahasiswa_id' => $this->mahasiswa->id,
                    'magang_id' => $magang_id,
                ],
            ]);
            $this->debug_info[] = 'Error creating contract: ' . $e->getMessage();
            throw $e;
        }

        // Step 8: Update student status
        Log::info('Updating student internship status');
        try {
            $this->mahasiswa->update(['status_magang' => 'sedang magang']);
            $this->mahasiswa->refresh();

            Log::info('Student status updated successfully', [
                'mahasiswa_id' => $this->mahasiswa->id,
                'new_status' => $this->mahasiswa->status_magang,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update student status', [
                'error' => $e->getMessage(),
                'mahasiswa_id' => $this->mahasiswa->id,
            ]);
            $this->debug_info[] = 'Error updating student status: ' . $e->getMessage();
            throw $e;
        }

        // Step 9: Get internship info and show success message
        $internshipInfo = $this->getInternshipInfo();

        Log::info('Save process completed successfully', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'final_status' => $this->mahasiswa->status_magang,
            'internship_info' => $internshipInfo,
        ]);

        session()->flash('success', "Data magang berhasil disimpan! Anda sedang magang di: {$internshipInfo}");
        $this->resetForm();
        $this->debug_info[] = 'Save completed successfully';
    } catch (\Exception $e) {
        Log::error('Unexpected error in save function', [
            'mahasiswa_id' => $this->mahasiswa->id ?? null,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
            'form_state' => [
                'company_type' => $this->company_type,
                'selected_company_id' => $this->selected_company_id,
                'company_name' => $this->company_name,
                'has_file' => $this->surat_izin_magang ? true : false,
            ],
        ]);

        session()->flash('error', 'Terjadi kesalahan yang tidak terduga. Error: ' . $e->getMessage() . ' (Line: ' . $e->getLine() . ')');
        $this->debug_info[] = 'Unexpected error: ' . $e->getMessage() . ' at line ' . $e->getLine();
    }
};

$getInternshipInfo = function () {
    try {
        Log::info('Getting internship info', [
            'mahasiswa_id' => $this->mahasiswa->id ?? null,
        ]);

        if (!$this->mahasiswa) {
            Log::warning('No mahasiswa data for internship info');
            return 'Tidak diketahui';
        }

        $kontrak = KontrakMagang::with(['magang.perusahaan'])
            ->where('mahasiswa_id', $this->mahasiswa->id)
            ->latest()
            ->first();

        if (!$kontrak) {
            Log::warning('No contract found for internship info', [
                'mahasiswa_id' => $this->mahasiswa->id,
            ]);
            return 'Kontrak magang tidak ditemukan';
        }

        if (!$kontrak->magang) {
            Log::warning('No magang data in contract', [
                'contract_id' => $kontrak->id,
                'mahasiswa_id' => $this->mahasiswa->id,
            ]);
            return 'Data program magang tidak ditemukan';
        }

        if (!$kontrak->magang->perusahaan) {
            Log::warning('No company data in magang', [
                'magang_id' => $kontrak->magang->id,
                'contract_id' => $kontrak->id,
            ]);
            return 'Data perusahaan tidak ditemukan';
        }

        $perusahaan = $kontrak->magang->perusahaan;
        $info = "{$perusahaan->nama} - {$perusahaan->lokasi}";

        Log::info('Internship info retrieved successfully', [
            'mahasiswa_id' => $this->mahasiswa->id,
            'company_name' => $perusahaan->nama,
            'location' => $perusahaan->lokasi,
            'info' => $info,
        ]);

        return $info;
    } catch (\Exception $e) {
        Log::error('Error getting internship info', [
            'mahasiswa_id' => $this->mahasiswa->id ?? null,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);

        return 'Error mengambil informasi magang: ' . $e->getMessage();
    }
};

$resetForm = function () {
    Log::info('Resetting form', [
        'mahasiswa_id' => $this->mahasiswa->id ?? null,
    ]);

    $this->reset(['company_type', 'selected_company_id', 'company_name', 'company_address', 'bidang_industri', 'lokasi_magang', 'surat_izin_magang']);
    $this->debug_info[] = 'Form reset completed';
};

// Add debug function for development
$showDebugInfo = function () {
    return config('app.debug', false) && auth()->guard('mahasiswa')->check();
};

?>

<div class="space-y-4 border-t pt-4 mt-4">
    <h3 class="text-md font-semibold text-gray-700">Informasi Magang</h3>

    @if (!$mahasiswa)
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
            <strong>Authentication Error:</strong> Anda harus login sebagai mahasiswa untuk mengakses fitur ini.
        </div>
    @else
        @if (session()->has('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        <!-- Show form only if student is not currently doing internship -->
        @if ($mahasiswa->status_magang !== 'sedang magang')
            <!-- Company Type Selection -->
            <div>
                <x-flux::field>
                    <x-flux::label>Jenis Perusahaan</x-flux::label>
                    <x-flux::select wire:model.live="company_type" placeholder="Pilih jenis perusahaan">
                        <option value="">Pilih jenis perusahaan</option>
                        <option value="partner">Perusahaan Mitra</option>
                        <option value="non_partner">Perusahaan Non-Mitra</option>
                    </x-flux::select>
                    <x-flux::error for="company_type" />
                </x-flux::field>
            </div>

            <!-- Partner Company Selection -->
            @if ($company_type == 'partner')
                <div>
                    <x-flux::field>
                        <x-flux::label>Pilih Perusahaan Mitra</x-flux::label>
                        <x-flux::select wire:model="selected_company_id" placeholder="Pilih perusahaan">
                            <option value="">Pilih perusahaan</option>
                            @foreach ($partner_companies as $company)
                                <option value="{{ $company->id }}">
                                    {{ $company->nama }} - {{ $company->lokasi }}
                                </option>
                            @endforeach
                        </x-flux::select>
                        <x-flux::error for="selected_company_id" />

                        @if (config('app.debug', false))
                            <x-flux::description>Debug: {{ $partner_companies->count() }} partner companies
                                loaded</x-flux::description>
                        @endif
                    </x-flux::field>
                </div>
            @endif

            <!-- Non-Partner Company Form -->
            @if ($company_type == 'non_partner')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-flux::field>
                            <x-flux::label>Nama Perusahaan</x-flux::label>
                            <x-flux::input wire:model="company_name" placeholder="Masukkan nama perusahaan" />
                            <x-flux::error for="company_name" />
                        </x-flux::field>
                    </div>

                    <div>
                        <x-flux::field>
                            <x-flux::label>Bidang Industri</x-flux::label>
                            <x-flux::select wire:model="bidang_industri" placeholder="Pilih bidang industri">
                                <option value="">Pilih bidang industri</option>
                                <option value="Perbankan">Perbankan</option>
                                <option value="Kesehatan">Kesehatan</option>
                                <option value="Pendidikan">Pendidikan</option>
                                <option value="E-Commerce">E-Commerce</option>
                                <option value="Telekomunikasi">Telekomunikasi</option>
                                <option value="Transportasi">Transportasi</option>
                                <option value="Pemerintahan">Pemerintahan</option>
                                <option value="Manufaktur">Manufaktur</option>
                                <option value="Energi">Energi</option>
                                <option value="Media">Media</option>
                                <option value="Teknologi">Teknologi</option>
                                <option value="Agrikultur">Agrikultur</option>
                                <option value="Pariwisata">Pariwisata</option>
                                <option value="Keamanan">Keamanan</option>
                            </x-flux::select>
                            <x-flux::error for="bidang_industri" />
                        </x-flux::field>
                    </div>

                    <div class="md:col-span-2">
                        <x-flux::field>
                            <x-flux::label>Alamat Perusahaan</x-flux::label>
                            <x-flux::textarea wire:model="company_address"
                                placeholder="Masukkan alamat lengkap perusahaan" rows="3" />
                            <x-flux::error for="company_address" />
                        </x-flux::field>
                    </div>
                </div>
            @endif

            <!-- Common fields for both company types -->
            @if ($company_type)
                <div>
                    <x-flux::field>
                        <x-flux::label>Lokasi Magang</x-flux::label>
                        <x-flux::input wire:model="lokasi_magang" placeholder="Lokasi tempat magang" />
                        <x-flux::error for="lokasi_magang" />
                    </x-flux::field>
                </div>

                <div>
                    <x-flux::field>
                        <x-flux::label>Surat Izin Magang (PDF)</x-flux::label>
                        <x-flux::input type="file" wire:model="surat_izin_magang" accept=".pdf" />
                        <x-flux::description>Upload file PDF maksimal 2MB</x-flux::description>

                        @if ($surat_izin_magang && !$errors->has('surat_izin_magang'))
                            <div class="mt-2 text-sm text-green-600">
                                File terpilih: {{ $surat_izin_magang->getClientOriginalName() }}
                            </div>
                        @endif

                        <div wire:loading wire:target="surat_izin_magang" class="text-sm text-blue-600 mt-2">
                            Mengunggah file...
                        </div>
                        <x-flux::error for="surat_izin_magang" />
                    </x-flux::field>
                </div>

                <div class="flex justify-end space-x-2 pt-4">
                    <x-flux::button variant="ghost" wire:click="resetForm">
                        Reset Form
                    </x-flux::button>
                    <x-flux::button wire:click="save" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="save">Simpan Data Magang</span>
                        <span wire:loading wire:target="save">Menyimpan...</span>
                    </x-flux::button>
                </div>
            @endif
        @else
            <!-- Message when student already has active internship -->
            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50">
                <strong>Informasi:</strong> Anda sudah memiliki status magang aktif di:
                {{ $this->getInternshipInfo() }}
                <br>Form pembaruan magang tidak tersedia.
            </div>
        @endif
    @endif
</div>
