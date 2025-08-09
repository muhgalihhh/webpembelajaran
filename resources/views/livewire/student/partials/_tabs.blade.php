<div class="mb-6 overflow-hidden bg-white border border-gray-300 rounded-lg">
    <div class="flex">
        <button wire:click.prevent="setTab('text')"
            class="flex-1 px-6 py-4 text-blue-600 border-r border-gray-300 {{ $activeTab === 'text' ? 'bg-blue-100 font-bold' : 'hover:bg-gray-100 font-medium' }}">
            Buku Digital (PDF)
        </button>
        <button wire:click.prevent="setTab('video')"
            class="flex-1 px-6 py-4 text-blue-600 {{ $activeTab === 'video' ? 'bg-blue-100 font-bold' : 'hover:bg-gray-50 font-medium' }}">
            Video Pembelajaran
        </button>
    </div>
</div>
