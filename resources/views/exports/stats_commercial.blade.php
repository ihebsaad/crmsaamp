<table>
    <thead>
        <tr>
            <th>Client</th>
            @foreach (range(0, 12) as $i)
                <th>{{ \Carbon\Carbon::now()->subMonths($i)->translatedFormat('F Y') }}</th>
            @endforeach
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stats as $item)
            <tr>
                <td>{{ $item->nom }}</td>
                <td>{{ $item->M }}</td>
                <td>{{ $item->M_1 }}</td>
                <td>{{ $item->M_2 }}</td>
                <td>{{ $item->M_3 }}</td>
                <td>{{ $item->M_4 }}</td>
                <td>{{ $item->M_5 }}</td>
                <td>{{ $item->M_6 }}</td>
                <td>{{ $item->M_7 }}</td>
                <td>{{ $item->M_8 }}</td>
                <td>{{ $item->M_9 }}</td>
                <td>{{ $item->M_10 }}</td>
                <td>{{ $item->M_11 }}</td>
                <td>{{ $item->M_12 }}</td>
                <td>{{ $item->TOTAL }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
