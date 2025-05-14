<flux:header class="block! bg-white lg:bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700">
    <flux:navbar class="lg:hidden w-full">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="start">
            <flux:profile avatar="https://fluxui.dev/img/demo/user.png" />
            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                    <flux:menu.radio>Truly Delta</flux:menu.radio>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <flux:menu.item icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
            </flux:menu>
        </flux:dropdown>
    </flux:navbar>
    <flux:navbar scrollable class="flex! justify-end! ">
        <div class="pt-2 flex gap-5 text-magnet-sky-teal">
            <a href="#">
                <flux:icon.headset />
            </a>
            <a href="#">
                <flux:icon.bookmark />
            </a>
            <a href="#">
                <flux:icon.bell />
            </a>
        </div>
    </flux:navbar>
</flux:header>