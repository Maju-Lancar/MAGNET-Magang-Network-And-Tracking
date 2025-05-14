<flux:navbar class="px-6 fixed py-4 top-0 w-full z-50 flex justify-between bg-[#041D56]!">
    <flux:navbar.item href="{{ route('home') }}">
        <flux:text class="font-black text-white text-2xl">{{ config('app.name') }}</flux:text>
    </flux:navbar.item>
    <div class="flex">
        <flux:navbar.item href="#alur">
            <flux:text class=" text-white text-lg">Pedoman Magang</flux:text>
        </flux:navbar.item>
        <flux:navbar.item href="#tata-tertib">
            <flux:text class=" text-white text-lg">Tata Tertib</flux:text>
        </flux:navbar.item>
        <flux:navbar.item href="#kendala">
            <flux:text class=" text-white text-lg">Pusat Bantuan</flux:text>
        </flux:navbar.item>
    </div>
</flux:navbar>