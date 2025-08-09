<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
    <tr>
        <th>#</th>
        <th>Number</th>
        <th>Price</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($ipNumbers as $index => $ip)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $ip->number }}</td>
            <td>{{ number_format($ip->price, 2) }}</td>
            <td>
                    <span class="badge {{ $ip->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                        {{ ucfirst($ip->status) }}
                    </span>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center">No results found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
