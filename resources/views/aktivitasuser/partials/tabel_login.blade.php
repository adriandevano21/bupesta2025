<h3>👥 Data Login User</h3>
<table id="loginTable" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Nama</th>
            <th>Username</th>
            <th>Kode Satker</th>
            <th>Tanggal Login</th>
            <th>Jumlah Aktivitas</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data["dailyLogins"] as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->username }}</td>
            <td>{{ $item->kode_satker }}</td>
            <td>{{ $item->login_date }}</td>
            <td>{{ $item->total_activity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
