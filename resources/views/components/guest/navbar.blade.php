<flux:navbar class="px-6 sticky py-4 top-0 w-full z-50 flex justify-between bg-magnet-deep-ocean-blue!">
    <flux:navbar.item href="{{ route('landing-page') }}">
        <flux:text class="font-black text-white text-2xl">{{ config('app.name') }}</flux:text>
    </flux:navbar.item>
    <div class="flex">
        <flux:navbar.item href="#alur">
            <flux:text class=" text-white">Pedoman Magang</flux:text>
        </flux:navbar.item>
        <flux:navbar.item href="#tata-tertib">
            <flux:text class=" text-white">Tata Tertib</flux:text>
        </flux:navbar.item>
        <flux:navbar.item href="#kendala">
            <flux:text class=" text-white">Pusat Bantuan</flux:text>
        </flux:navbar.item>
    </div>
</flux:navbar>
