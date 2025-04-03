<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Reçu de transfert') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .receipt-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .receipt-number {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
        }
        .amount {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 2px solid #000;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
        }
        .signature {
            margin-top: 50px;
            text-align: center;
        }
        .signature-line {
            width: 200px;
            border-bottom: 1px solid #000;
            margin: 0 auto 5px;
        }
        @media print {
            body {
                padding: 0;
                max-width: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-info">
            <h1>WODI TRANSFER</h1>
            <p>Votre partenaire de confiance pour les transferts d'argent</p>
        </div>
        <div class="receipt-title">{{ __('Reçu de transfert') }}</div>
        <div class="receipt-number">{{ $transfer->code }}</div>
    </div>

    <div class="section">
        <div class="section-title">{{ __('Informations du transfert') }}</div>
        <div class="info-row">
            <span class="info-label">{{ __('Date') }}:</span>
            <span>{{ $transfer->transfer_date->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('Statut') }}:</span>
            <span>{{ __('transfers.status.' . $transfer->status) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('Pays d\'origine') }}:</span>
            <span>{{ $transfer->sourceCountry ? $transfer->sourceCountry->name : 'Non spécifié' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('Pays de destination') }}:</span>
            <span>{{ $transfer->destinationCountry ? $transfer->destinationCountry->name : 'Non spécifié' }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">{{ __('Expéditeur') }}</div>
        <div class="info-row">
            <span class="info-label">{{ __('Nom complet') }}:</span>
            <span>{{ $transfer->sender->first_name }} {{ $transfer->sender->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('Téléphone') }}:</span>
            <span>{{ $transfer->sender->phone_number }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">{{ __('Destinataire') }}</div>
        <div class="info-row">
            <span class="info-label">{{ __('Nom complet') }}:</span>
            <span>{{ $transfer->receiver->first_name }} {{ $transfer->receiver->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('Téléphone') }}:</span>
            <span>{{ $transfer->receiver->phone_number }}</span>
        </div>
    </div>

    <div class="amount">
        {{ number_format($transfer->amount, 2) }} {{ $transfer->currency->code }}
    </div>

    <div class="section">
        <div class="section-title">{{ __('Informations supplémentaires') }}</div>
        <div class="info-row">
            <span class="info-label">{{ __('Agent d\'envoi') }}:</span>
            <span>{{ $transfer->sendingAgent->first_name }} {{ $transfer->sendingAgent->last_name }}</span>
        </div>
        @if($transfer->payingAgent)
        <div class="info-row">
            <span class="info-label">{{ __('Agent de paiement') }}:</span>
            <span>{{ $transfer->payingAgent->first_name }} {{ $transfer->payingAgent->last_name }}</span>
        </div>
        @endif
        @if($transfer->payment_date)
        <div class="info-row">
            <span class="info-label">{{ __('Date de paiement') }}:</span>
            <span>{{ $transfer->payment_date->format('d/m/Y') }}</span>
        </div>
        @endif
    </div>

    <div class="signature">
        <div class="signature-line"></div>
        <div>{{ __('Signature de l\'agent') }}</div>
    </div>

    <div class="footer">
        <p>{{ __('Ce reçu est une preuve de votre transaction. Veuillez le conserver.') }}</p>
        <p>{{ __('Généré le') }}: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            {{ __('Imprimer le reçu') }}
        </button>
    </div>
</body>
</html>
