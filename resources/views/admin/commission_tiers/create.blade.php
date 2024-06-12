<x-admin-layout>
    <h1>Add New Commission Tier</h1>
    <form action="{{ route('commission_tiers.store') }}" method="POST">
        @csrf
        <label for="min_amount">Min Amount</label>
        <input type="text" name="min_amount" id="min_amount" required>
        <label for="max_amount">Max Amount</label>
        <input type="text" name="max_amount" id="max_amount">
        <label for="percentage">Percentage</label>
        <input type="text" name="percentage" id="percentage" required>
        <button type="submit">Save</button>
    </form>
</x-admin-layout>
