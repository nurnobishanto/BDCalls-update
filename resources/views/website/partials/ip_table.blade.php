<table class="table table-bordered table-hover align-middle">
    <thead class="table-light">
    <tr>
        <th>S/N	</th>
        <th>Ip Number</th>
        <th>Price (৳)</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($ipNumbers as $ip)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $ip->number }}</td>
            <td>
                @if ($ip->price == 0)
                    <span class="badge bg-success">Free</span>
                @else
                    {{ number_format($ip->price, 2) }}
                @endif
            </td>
            <td>
                <span class="badge {{ $ip->status == 'available' ? 'bg-success' : 'bg-danger' }}">
                    {{ ucfirst($ip->status) }}
                </span>
            </td>
            <td>

            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No results found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
