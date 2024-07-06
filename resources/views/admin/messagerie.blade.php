<x-admin-layout>
    <div class="flex h text-white">
        <div class="w-full sm:w-1/4 bg-slate text-white p-0 m-0 border-r overflow-y-auto">
            @livewire('chat-list')
        </div>
        <div class="w-full sm:w-3/4 p-4 m-0 hidden sm:block">
            <div class="flex-grow p-6 bg-slate-100">
                <h1 class="text-2xl font-bold mb-4">Bienvenue dans votre messagerie</h1>
                <p class="mb-4">Utilisez cette section pour gérer vos messages et communiquer avec les prestataires en charge</p>
                <div class="bg-slate p-4 rounded-lg shadow-md text-white">
                    <ul class="list-disc list-inside text-white">
                        <li>Visualisez vos conversations récentes</li>
                        <li>Envoyez et recevez des messages en temps réel</li>
                        <li>Recevez des notifications pour les nouveaux messages</li>
                    </ul>
                </div>
                <div class="mt-6">
                    Les conversations liées à des interventions terminées sont automatiquement closes.
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
