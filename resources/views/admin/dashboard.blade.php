<x-layouts.admin.admin>
  <flux:breadcrumbs class="mb-5">
    <flux:breadcrumbs.item href="{{ route('dashboard') }}" icon="home" icon:variant="outline" />
    <flux:breadcrumbs.item href="{{ route('dashboard') }}" class="text-black">Dashboard</flux:breadcrumbs.item>
  </flux:breadcrumbs>
  <div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

      <div class="bg-white rounded-sm shadow-md h-48 p-4">
        <p class="text-gray-700">Konten Card 1</p>

      </div>


      <div class="bg-white rounded-sm shadow-md h-48 p-4">
        <p class="text-gray-700">Konten Card 2</p>
      </div>
    </div>


    <div class="bg-white rounded-sm shadow-md h-64 p-4">
      <p class="text-gray-700">Konten Card Besar</p>
    </div>
  </div>

</x-layouts.admin.admin>