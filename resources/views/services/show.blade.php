<x-admin-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-950 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-white">
                    <h2 class="text-2xl font-bold mb-4">{{ $service->name }}</h2>
                    <p><strong>Prix:</strong> {{ $service->price }}€</p>
                    <p><strong>Description:</strong> {{ $service->description }}</p>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
