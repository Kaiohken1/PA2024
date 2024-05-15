<form method="post" action="{{ route('property.index') }}">
@csrf
<div class="flex justify-center py-9">
<div class="grid grid-cols-8 gap-6 w-9/12">
<div class="flex flex-col">

<select name="sort_type" onchange="this.form.submit()" class="select select-bordered w-full max-w-xs">
    <option value="price_asc">prix croissant</option>
    <option value="price_desc">prix décroissant</option>
    <option value="surface_asc">surface croissant</option>
    <option value="surface_desc">surface décroissant</option>
    <option value="guest_count_asc">voyageurs croissant</option>
    <option value="guest_count_desc">voyageurs décroissant</option>
  </select>

</div>
</div>
</div>

