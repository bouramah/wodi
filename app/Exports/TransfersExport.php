<?php

namespace App\Exports;

use App\Models\Transfer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransfersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transfers;

    public function __construct($transfers)
    {
        $this->transfers = $transfers;
    }

    public function collection()
    {
        return $this->transfers;
    }

    public function headings(): array
    {
        return [
            'Code',
            'Expéditeur',
            'Destinataire',
            'Montant',
            'Devise',
            'Statut',
            'Date de transfert',
            'Agent d\'envoi',
            'Agent de paiement'
        ];
    }

    public function map($transfer): array
    {
        return [
            $transfer->code,
            $transfer->sender->first_name . ' ' . $transfer->sender->last_name,
            $transfer->receiver->first_name . ' ' . $transfer->receiver->last_name,
            number_format($transfer->amount, 2),
            $transfer->currency->code,
            __('transfers.status.' . $transfer->status),
            $transfer->transfer_date->format('d/m/Y H:i'),
            $transfer->sendingAgent->first_name . ' ' . $transfer->sendingAgent->last_name,
            $transfer->payingAgent ? $transfer->payingAgent->first_name . ' ' . $transfer->payingAgent->last_name : '-'
        ];
    }
}
