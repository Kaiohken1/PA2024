<form method="post" action="{{ route('property.index') }}">
@csrf
<div class=" w-36 ">


<select name="sort_type" onchange="this.form.submit()" class="select select-bordered w-full max-w-xs">
    <option value="price_asc">prix croissant</option>
    <option value="price_desc">prix décroissant</option>
    <option value="surface_asc">surface croissant</option>
    <option value="surface_desc">surface décroissant</option>
    <option value="guest_count_asc">voyageurs croissant</option>
    <option value="guest_count_desc">voyageurs décroissant</option>
  </select>


</div>

