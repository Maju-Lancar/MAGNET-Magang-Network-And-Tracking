<?php

use function Livewire\Volt\{layout, rules, state};
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Mahasiswa;

layout('components.layouts.guest.with-navbar');

state([
    'nim' => null,
    'nama' => null,
    'email' => null,
    'jurusan' => 'Teknologi Informasi',
    'program_studi' => null,
    'angkatan' => null,
    'jenis_kelamin' => null,
    'tanggal_lahir' => null,
    'alamat' => null,
    'password' => null,
    'password_confirmation' => null,
]);

rules([
    'nim' => ['required', 'string', 'min:10', 'regex:/^\d+$/'],
    'nama' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'max:255'],
    'program_studi' => ['required', 'string', 'in:D4 Teknik Informatika,D4 Sistem Informasi Bisnis,D2 Pengembangan Piranti Lunak Situs'],
    'angkatan' => ['required', 'string'],
    'jenis_kelamin' => ['required', 'string', 'in:L,P'],
    'tanggal_lahir' => ['required', 'date'],
    'alamat' => ['required', 'string'],
    'password' => ['required', 'string', 'confirmed', Password::default()],
])->messages([
    'nim.required' => 'NIM tidak boleh kosong.',
    'nim.min' => 'NIM/NIP/NIDN harus terdiri dari minimal 10 karakter.',
    'nim.regex' => 'NIM/NIP/NIDN hanya boleh terdiri dari angka.',
    'nama.required' => 'Nama tidak boleh kosong.',
    'email.required' => 'Email tidak boleh kosong.',
    'email.email' => 'Format email tidak valid.',
    'program_studi.required' => 'Program studi harus dipilih.',
    'program_studi.in' => 'Program studi yang dipilih tidak valid.',
    'angkatan.required' => 'Angkatan tidak boleh kosong.',
    'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
    'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',
    'tanggal_lahir.required' => 'Tanggal lahir tidak boleh kosong.',
    'alamat.required' => 'Alamat tidak boleh kosong.',
    'password.required' => 'Password tidak boleh kosong.',
    'password.min' => 'Password harus terdiri dari minimal 8 karakter.',
    'password.confirmed' => 'Konfirmasi password tidak cocok.',
]);

$register = function (): void {
    $this->validate();

    event(
        new Registered(
            ($mahasiswa = Mahasiswa::create([
                'nama' => $this->nama,
                'nim' => $this->nim,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'jurusan' => $this->jurusan,
                'program_studi' => $this->program_studi,
                'angkatan' => $this->angkatan,
                'jenis_kelamin' => $this->jenis_kelamin,
                'tanggal_lahir' => $this->tanggal_lahir,
                'alamat' => $this->alamat,
            ])),
        ),
    );

    Auth::guard('mahasiswa')->login($mahasiswa);

    $this->redirectIntended(route('mahasiswa.persiapan-preferensi', absolute: false), navigate: true);
};

?>

<div class="flex flex-col gap-6 w-full h-fit justify-center items-center p-14">
    <div class="bg-white shadow-lg flex flex-col rounded-md p-7">
        <h1 class="text-black font-black text-center text-3xl">DAFTAR</h1>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="register" class="flex flex-col gap-4 pt-8">
            <flux:field>
                <flux:label>NIM</flux:label>
                <flux:input wire:model="nim" type="text" required autofocus autocomplete="nim"
                    placeholder="Masukkan NIM anda" />
                <flux:error name="nim" />
            </flux:field>

            <flux:field>
                <flux:label>Nama Lengkap</flux:label>
                <flux:input wire:model="nama" type="text" required autofocus autocomplete="nama"
                    placeholder="Nama Lengkap" />
                <flux:error name="nama" />
            </flux:field>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input wire:model="email" type="email" required autofocus autocomplete="email"
                    placeholder="Email" />
                <flux:error name="email" />
            </flux:field>

            <flux:field>
                <flux:label>Jurusan</flux:label>
                <flux:input wire:model="jurusan" type="text" value="Teknologi Informasi" placeholder="Jurusan"
                    disabled />
                <flux:error name="jurusan" />
            </flux:field>

            <flux:field>
                <flux:label>Program Studi</flux:label>
                <flux:input.group class="rounded-xl">
                    <flux:select wire:model="program_studi" placeholder="Pilih Program Studi">
                        <flux:select.option value="">Pilih Program Studi</flux:select.option>
                        <flux:select.option value="D4 Teknik Informatika">D4 Teknik Informatika</flux:select.option>
                        <flux:select.option value="D4 Sistem Informasi Bisnis">D4 Sistem Informasi Bisnis
                        </flux:select.option>
                        <flux:select.option value="D2 Pengembangan Piranti Lunak Situs">D2 Pengembangan Piranti Lunak
                            Situs</flux:select.option>
                    </flux:select>
                </flux:input.group>
                <flux:error name="program_studi" />
            </flux:field>

            <flux:field>
                <flux:label>Angkatan</flux:label>
                <flux:input wire:model="angkatan" type="text" required autofocus autocomplete="nama"
                    placeholder="Angkatan" />
                <flux:error name="angkatan" />
            </flux:field>

            <flux:field>
                <flux:label>Jenis Kelamin</flux:label>
                <flux:select placeholder="Pilih Jenis Kelamin" wire:model="jenis_kelamin">
                    <flux:select.option value="">Pilih Jenis Kelamin</flux:select.option>
                    <flux:select.option value="L">Laki-laki</flux:select.option>
                    <flux:select.option value="P">Perempuan</flux:select.option>
                </flux:select>
                <flux:error name="jenis_kelamin" />
            </flux:field>

            <flux:field>
                <flux:label>Tanggal Lahir</flux:label>
                <flux:input wire:model="tanggal_lahir" type="date" max="2999-12-31" required />
                <flux:error name="tanggal_lahir" />
            </flux:field>

            <flux:field>
                <flux:textarea wire:model="alamat" label="Alamat" placeholder="Domisili asal anda" required />
                <flux:error name="alamat" />
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input wire:model="password" type="password" required autocomplete="new-password"
                    placeholder="Password" viewable />
                <flux:description class="text-xs text-gray-500 mt-0!">Kata sandi minimal 8 karakter,
                    kombinasikan huruf besar, kecil, angka, dan simbol demi keamanan terbaik.</flux:description>
                <flux:error name="password" />
            </flux:field>

            <flux:field>
                <flux:label>Konfirmasi Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" required autocomplete="new-password"
                    placeholder="Konfirmasi password" viewable />
                <flux:error name="password_confirmation" />
            </flux:field>
            <div class="flex items-center justify-end my-1">
                <flux:button type="submit" variant="primary" class="w-full bg-magnet-sky-teal text-white">
                    {{ __('Daftar') }}
                </flux:button>
            </div>
        </form>
    </div>
</div>
