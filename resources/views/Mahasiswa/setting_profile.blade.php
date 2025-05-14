<x-layouts.mahasiswa.mahasiswa>
    <flux:breadcrumbs class="mb-5">
        <flux:breadcrumbs.item href="{{ route('mahasiswa.setting-profile') }}" icon="home" icon:variant="outline" />
        <flux:breadcrumbs.item href="{{ route('mahasiswa.setting-profile') }}" class="text-black">Setting Profile</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    <div class="bg-[#E0F7FA] min-h-screen p-6">
        <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow flex gap-8 flex-wrap">
           <!-- Tombol Upload atau Edit + Hapus -->
            <div id="photoButtons" class="flex flex-col items-center gap-3">
                <div id="profilePreview"
                    class="w-[230px] h-[306px] flex-shrink-0 rounded-[10px] bg-[lightgray] bg-center bg-cover bg-no-repeat"
                    style="aspect-ratio: 115 / 153; background-image: url('/path/to/default.jpg');"></div>

                <input type="file" id="photoInput" accept="image/*" class="hidden" />

                <!-- Tombol Upload (jika belum ada foto) -->
                <button type="button" id="uploadButton"
                    onclick="document.getElementById('photoInput').click()"
                    class="rounded-[8px] bg-[var(--Default-green-400,#34D399)] text-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.10),0px_2px_4px_-1px_rgba(0,0,0,0.06)] flex h-[38px] px-[21px] py-[7px] justify-center items-center gap-[8px]">
                    Upload Foto
                </button>

                <!-- Tombol Edit & Hapus -->
                <div id="editDeleteBtns" class="hidden flex gap-3">
                    <!-- Edit Foto -->
                    <flux:navlist.item
                        icon="pencil"
                        onclick="document.getElementById('photoInput').click()"
                        class="!text-black rounded-[8px] bg-[var(--Default-green-400,#34D399)] shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.10),0px_2px_4px_-1px_rgba(0,0,0,0.06)] flex h-[38px] px-[21px] py-[7px] justify-center items-center gap-[8px]">
                        Edit Foto
                    </flux:navlist.item>

                    <!-- Hapus Foto -->
                    <flux:navlist.item
                        icon="trash-2"
                        onclick="removePhoto()"
                        class="!text-black rounded-[8px] bg-[var(--Default-red-400,#F87171)] shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.10),0px_2px_4px_-1px_rgba(0,0,0,0.06)] flex h-[38px] px-[21px] py-[7px] justify-center items-center gap-[8px]">
                        Hapus Foto
                    </flux:navlist.item>
                </div>
            </div>


            <!-- Form -->
            <div class="flex-1">
                <h2 class="text-lg font-semibold mb-4 text-black">Data Diri Mahasiswa</h2>

                <form class="space-y-4 text-black">
                    <!-- Nama -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">Nama</label>
                        <input type="text" name="nama" class="w-full rounded-md border-gray-300 shadow-sm text-black" />
                    </div>

                    <!-- NIM -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">NIM</label>
                        <input type="text" name="nim"
                               oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                               class="w-full rounded-md border-gray-300 shadow-sm text-black bg-white/50 focus:ring focus:ring-green-200 focus:border-green-400" />
                    </div>

                    <!-- Jurusan -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">Jurusan</label>
                        <input type="text" name="jurusan" class="w-full rounded-md border-gray-300 shadow-sm text-black" />
                    </div>

                    <!-- Program Studi -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">Program Studi</label>
                        <input type="text" name="prodi" class="w-full rounded-md border-gray-300 shadow-sm text-black" />
                    </div>

                    <!-- Jenis Kelamin -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="w-full rounded-md border-gray-300 shadow-sm text-black">
                            <option value="" disabled selected class="text-black/30">Pilih jenis kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <!-- Alamat -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">Alamat</label>
                        <textarea name="alamat" class="w-full rounded-md border-gray-300 shadow-sm text-black min-h-[80px] resize-y"></textarea>
                    </div>

                    <!-- Prestasi -->
                    <div class="flex items-center gap-2 p-[6px_13px] align-self-stretch">
                        <label class="w-1/4 font-medium">Prestasi</label>
                        <input type="text" name="prestasi_1" class="w-full rounded-md border-gray-300 shadow-sm text-black" />
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="rounded-[8px] bg-[var(--Default-green-400,#34D399)] text-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.10),0px_2px_4px_-1px_rgba(0,0,0,0.06)] flex h-[38px] px-[21px] py-[7px] justify-center items-center gap-[8px]">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Foto Profil -->
    <script>
        const profilePreview = document.getElementById('profilePreview');
        const photoInput = document.getElementById('photoInput');
        const uploadButton = document.getElementById('uploadButton');
        const editDeleteBtns = document.getElementById('editDeleteBtns');

        // Cek apakah sudah ada foto saat pertama load
        const defaultPhoto = '/path/to/default.jpg';
        const currentPhoto = profilePreview.style.backgroundImage.includes(defaultPhoto);

        if (!currentPhoto) {
            uploadButton.classList.add('hidden');
            editDeleteBtns.classList.remove('hidden');
        }

        photoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                profilePreview.style.backgroundImage = `url('${e.target.result}')`;
                uploadButton.classList.add('hidden');
                editDeleteBtns.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });

        function removePhoto() {
            profilePreview.style.backgroundImage = `url('${defaultPhoto}')`;
            photoInput.value = '';
            uploadButton.classList.remove('hidden');
            editDeleteBtns.classList.add('hidden');
        }
    </script>

</x-layouts.mahasiswa.mahasiswa>
