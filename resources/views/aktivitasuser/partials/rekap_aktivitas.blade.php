<h3 style="margin-top: 30px;">📋 Rekap Jenis Aktivitas</h3>
<table id="rekapTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Aktivitas</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data["aktivitasSummary"] as $item)
        <tr>
            <td>{{ $item->activity }}</td>
            <td>{{ $item->total }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
