<form method="post" action="{{ route('property.index') }}">
@csrf
<div class="flex flex-col">
<button class="btn btn-neutral btn-xs flex items-center flex-col" name="sort_type" value="price_desc">
<x-fas-sort-up />
prix
</button>
<button class="btn btn-neutral btn-xs flex items-center flex-col" name="sort_type" value="price_asc">
<x-fas-sort-down />
prix
</button>
</div>
<br>
<div class="flex flex-col">
<button class="btn btn-neutral btn-xs flex items-center flex-col">
<x-fas-sort-up />
surface
</button>
<button class="btn btn-neutral btn-xs flex items-center flex-col">
<x-fas-sort-down />
surface
</button>
</div>
</form>
