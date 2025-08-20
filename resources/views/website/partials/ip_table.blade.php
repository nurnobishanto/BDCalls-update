<table class="table table-sm table-bordered table-hover align-middle">
    <thead class="table-success text-center">
    <tr>
        <th>S/N	</th>
        <th>Ip Number</th>
        <th>Price (৳)</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody class="text-center">
    @forelse ($ipNumbers as $ip)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $ip->number }}</td>
            <td>
                @if ($ip->price == 0)
                    <span class="badge bg-success">Free</span>
                @else
                    {{ number_format($ip->price) }}৳
                @endif
            </td>
            <td>
                @if($ip->status === 'available')
                    <span class="fw-bold text-success">Available</span>
                @elseif($ip->status === 'in_process')
                    <span class="fw-bold text-warning">In Process</span>
                @else
                    <span class="fw-bold text-danger">Sold Out</span>
                @endif
            </td>

            <td>
                @if($ip->status === 'available')
                    <a href="{{ route('number_purchase', ['id' => $ip->id]) }}"
                       class="badge bg-success text-light">Apply Now</a>
                @elseif($ip->status === 'in_process')
                    <span class="badge bg-warning text-dark">Pending</span>
                @endif
            </td>

        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">No results found.</td>
        </tr>
    @endforelse
    </tbody>
</table>
