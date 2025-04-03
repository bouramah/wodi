<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Liste des transferts') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
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
            font-size: 11px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .status-pending {
            color: #f59e0b;
        }
        .status-paid {
            color: #10b981;
        }
        .status-cancelled {
            color: #ef4444;
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
        <p>Total: {{ count($transfers) }} transfert(s)</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>{{ __('Code') }}</th>
                <th>{{ __('Expéditeur') }}</th>
                <th>{{ __('Destinataire') }}</th>
                <th>{{ __('Montant') }}</th>
                <th>{{ __('De') }}</th>
                <th>{{ __('Vers') }}</th>
                <th>{{ __('Statut') }}</th>
                <th>{{ __('Date') }}</th>
                <th>{{ __('Agent') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transfers as $transfer)
                <tr>
                    <td>{{ $transfer->code }}</td>
                    <td>{{ $transfer->sender->first_name }} {{ $transfer->sender->last_name }}<br>
                        <small>{{ $transfer->sender->phone_number }}</small>
                    </td>
                    <td>{{ $transfer->receiver->first_name }} {{ $transfer->receiver->last_name }}<br>
                        <small>{{ $transfer->receiver->phone_number }}</small>
                    </td>
                    <td>{{ number_format($transfer->amount, 2) }} {{ $transfer->currency->code }}</td>
                    <td>{{ $transfer->sourceCountry ? $transfer->sourceCountry->name : 'Non spécifié' }}</td>
                    <td>{{ $transfer->destinationCountry ? $transfer->destinationCountry->name : 'Non spécifié' }}</td>
                    <td class="status-{{ $transfer->status }}">{{ __('transfers.status.' . $transfer->status) }}</td>
                    <td>{{ $transfer->transfer_date->format('d/m/Y') }}</td>
                    <td>{{ $transfer->sendingAgent->first_name }} {{ $transfer->sendingAgent->last_name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ __('Généré le') }}: {{ now()->format('d/m/Y H:i') }} | WODI TRANSFER</p>
    </div>
</body>
</html>
