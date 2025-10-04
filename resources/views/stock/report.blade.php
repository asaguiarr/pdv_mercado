@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Stock Report</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Current Stock</th>
                <th>Total In</th>
                <th>Total Out</th>
                <th>Last Movement Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockReport as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->current_stock }}</td>
                <td>{{ $item->total_in }}</td>
                <td>{{ $item->total_out }}</td>
                <td>{{ $item->last_movement_date ? $item->last_movement_date->format('Y-m-d H:i') : 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
