<x-admin-layout>
    <h1>Commission Tiers</h1>
    <a href="{{ route('commission_tiers.create') }}">Add New Tier</a>
    <table>
        <thead>
            <tr>
                <th>Min Amount</th>
                <th>Max Amount</th>
                <th>Percentage</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tiers as $tier)
                <tr>
                    <td>{{ $tier->min_amount }}</td>
                    <td>{{ $tier->max_amount }}</td>
                    <td>{{ $tier->percentage }}</td>
                    <td>
                        <a href="{{ route('commission_tiers.edit', $tier->id) }}">Edit</a>
                        <form action="{{ route('commission_tiers.destroy', $tier->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin-layout>