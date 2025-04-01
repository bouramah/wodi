<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Liste des transferts') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('Liste des transferts') }}</h1>
        @if(request('filter_type') === 'date_range')
            <p>{{ __('Période') }}: {{ request('start_date') }} - {{ request('end_date') }}</p>
        @elseif(request('filter_type') === 'month')
            <p>{{ __('Mois') }}: {{ request('month') }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Expéditeur') }}</th>
                <th>{{ __('Destinataire') }}</th>
                <th>{{ __('Montant') }}</th>
                <th>{{ __('Statut') }}</th>
                <th>{{ __('Date') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transfers as $transfer)
                <tr>
                    <td>{{ $transfer->code }}</td>
                    <td>{{ $transfer->sender->first_name }} {{ $transfer->sender->last_name }}</td>
                    <td>{{ $transfer->receiver->first_name }} {{ $transfer->receiver->last_name }}</td>
                    <td>{{ number_format($transfer->amount, 2) }} {{ $transfer->currency->code }}</td>
                    <td>{{ __('transfers.status.' . $transfer->status) }}</td>
                    <td>{{ $transfer->transfer_date->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ __('Généré le') }}: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
