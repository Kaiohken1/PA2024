@if (session('success'))
    <div class="p-4 mb-3 mt-3 text-center text-sm text-green-800 rounded-lg bg-green-50 dark:text-green-600"
        role="alert">
        {{ session('success') }}
    </div>
@elseif (session('error'))
    <div class="p-4 mb-3 mt-3 text-center text-sm text-red-800 rounded-lg bg-red-50 dark:text-red-600" role="alert">
        {{ session('error') }}
    </div>
@endif
