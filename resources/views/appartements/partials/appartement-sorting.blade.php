<form method="post" action="{{ route('property.index') }}">
@csrf
<div class="flex justify-center">
<div class="grid grid-cols-8 gap-6 w-9/12">
<div class="flex flex-col">
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="price_desc ">
<x-fas-sort-up />
prix
</button>
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="price_asc">
<x-fas-sort-down />
prix
</button>
</div>

<div class="flex flex-col">
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="surface_desc ">
<x-fas-sort-up />
surface
</button>
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="surface_asc">
<x-fas-sort-down />
surface
</button>
</div>

<div class="flex flex-col">
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="guest_count_desc ">
<x-fas-sort-up />
voyageurs
</button>
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="guest_count_asc">
<x-fas-sort-down />
voyageurs
</button>
</div>

<div class="flex flex-col">
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="rating_desc ">
<x-fas-sort-up />
notes
</button>
<button class="btn btn-neutral btn-xs flex items-center content-center flex-col w-1/2" name="sort_type" value="rating_asc">
<x-fas-sort-down />
notes
</button>
</div>
</div>
</div>

