<flux:sidebar
    stashable
    class="bg-zinc-50 dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    <a href="#">
        <h1 class="text-3xl font-black ps-2 mx-4 text-center text-[#219EBC]">MAGNET</h1>
    </a>

    <flux:navlist variant="outline">
        <flux:navlist.item icon="home" href="{{ route('mahasiswa.dashboard') }}" current>
            Dashboard
        </flux:navlist.item>

        <flux:navlist.item icon="flask-conical" href="{{ route('mahasiswa.pengajuan-magang') }}">
            Pengajuan Magang
        </flux:navlist.item>

        <flux:navlist.item icon="battery-medium" href="{{ route('mahasiswa.pembaruan-status') }}">
            Pembaruan Status
        </flux:navlist.item>

        <flux:navlist.item icon="file-chart-column-increasing" href="{{ route('mahasiswa.log-mahasiswa') }}">
            Log Magang
        </flux:navlist.item>
    </flux:navlist>

    <flux:spacer />

    <flux:dropdown position="top" align="start" class="max-lg:hidden">
        <flux:profile
            avatar="https://fluxui.dev/img/demo/user.png"
            name="Olivia Martin"
        />

        <flux:menu>
            <flux:menu.radio.group>
                <flux:menu.radio    >Olivia Martin</flux:menu.radio>
            </flux:menu.radio.group>

            <flux:menu.separator />

            <flux:navlist variant="outline" class="w-full">
                <flux:navlist.item icon="cog-6-tooth" href="{{ route('mahasiswa.setting-profile') }}">
                    Settings
                </flux:navlist.item>
            </flux:navlist>


            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full"
                >
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu>
    </flux:dropdown>
</flux:sidebar>
