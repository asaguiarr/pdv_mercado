<table id="stockTable" class="table table-striped">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade Atual</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $p)
        <tr @if($p->quantity <= 5) class="table-danger" @endif>
            <td>{{ $p->name }}</td>
            <td>{{ $p->quantity }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.addEventListener("DOMContentLoaded", function () {
    $('#stockTable').DataTable({
        pageLength: 10,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json"
        },
        order: [[1, 'asc']] // Ordena pela quantidade crescente
    });
});
</script>
