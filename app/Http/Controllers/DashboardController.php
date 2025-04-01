<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\User;
use App\Models\TemporaryDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        // Données communes pour tous les utilisateurs
        if ($user->hasRole('admin')) {
            $data['total_transfers'] = Transfer::count();
            $data['total_agents'] = User::role('agent')->count();
            $data['total_clients'] = User::role('client')->count();
        }

        // Statistiques des 7 derniers jours
        $lastWeekStart = Carbon::now()->subDays(7)->startOfDay();
        $data['recent_transfers'] = Transfer::where('created_at', '>=', $lastWeekStart)->count();

        // Statistiques par rôle
        if ($user->hasRole('admin')) {
            // Statistiques pour les administrateurs
            $data['pending_transfers'] = Transfer::where('status', 'pending')->count();
            $data['paid_transfers'] = Transfer::where('status', 'paid')->count();
            $data['cancelled_transfers'] = Transfer::where('status', 'cancelled')->count();

            // Montant total des transferts par statut
            $data['pending_amount'] = Transfer::where('status', 'pending')->sum('amount');
            $data['paid_amount'] = Transfer::where('status', 'paid')->sum('amount');

            // Statistiques par mois (6 derniers mois)
            $data['monthly_stats'] = $this->getMonthlyStats();

            // Top 5 des agents les plus actifs
            $data['top_agents'] = $this->getTopAgents();

            // Dépôts temporaires
            $data['total_deposits'] = TemporaryDeposit::count();
            $data['active_deposits'] = TemporaryDeposit::where('remaining_amount', '>', 0)->count();
            $data['total_deposit_amount'] = TemporaryDeposit::sum('amount');
            $data['remaining_deposit_amount'] = TemporaryDeposit::sum('remaining_amount');

        } elseif ($user->hasRole('agent')) {
            // Statistiques pour les agents
            $data['my_pending_transfers'] = Transfer::where('sending_agent_id', $user->id)
                ->where('status', 'pending')
                ->count();

            $data['my_paid_transfers'] = Transfer::where('paying_agent_id', $user->id)
                ->where('status', 'paid')
                ->count();

            $data['my_total_sent'] = Transfer::where('sending_agent_id', $user->id)->count();
            $data['my_total_paid'] = Transfer::where('paying_agent_id', $user->id)->count();

            // Montants des transferts de cet agent
            $data['my_sent_amount'] = Transfer::where('sending_agent_id', $user->id)->sum('amount');
            $data['my_paid_amount'] = Transfer::where('paying_agent_id', $user->id)->sum('amount');

            // Statistiques des transferts récents de l'agent
            $data['my_recent_transfers'] = Transfer::where(function($query) use ($user) {
                $query->where('sending_agent_id', $user->id)
                      ->orWhere('paying_agent_id', $user->id);
            })
            ->where('created_at', '>=', $lastWeekStart)
            ->count();

            // Statistiques des dépôts de l'agent
            $data['my_deposits'] = TemporaryDeposit::where('agent_id', $user->id)->count();
            $data['my_active_deposits'] = TemporaryDeposit::where('agent_id', $user->id)
                ->where('remaining_amount', '>', 0)
                ->count();
            $data['my_deposit_amount'] = TemporaryDeposit::where('agent_id', $user->id)->sum('amount');
            $data['my_remaining_deposit_amount'] = TemporaryDeposit::where('agent_id', $user->id)->sum('remaining_amount');
        }

        return view('dashboard', compact('data'));
    }

    private function getMonthlyStats()
    {
        $months = collect([]);

        // Récupérer les données des 6 derniers mois
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            $label = $month->format('M Y');

            $transfers = Transfer::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->get();

            $months->push([
                'label' => $label,
                'total' => $transfers->count(),
                'paid' => $transfers->where('status', 'paid')->count(),
                'pending' => $transfers->where('status', 'pending')->count(),
                'cancelled' => $transfers->where('status', 'cancelled')->count(),
                'amount' => $transfers->sum('amount'),
            ]);
        }

        return $months->reverse()->values();
    }

    private function getTopAgents($limit = 5)
    {
        $sentTransfers = DB::table('transfers')
            ->select(DB::raw('sending_agent_id as agent_id, COUNT(*) as sent_count'))
            ->groupBy('sending_agent_id');

        $paidTransfers = DB::table('transfers')
            ->select(DB::raw('paying_agent_id as agent_id, COUNT(*) as paid_count'))
            ->whereNotNull('paying_agent_id')
            ->groupBy('paying_agent_id');

        $agents = DB::table('users')
            ->joinSub($sentTransfers, 'sent', function ($join) {
                $join->on('users.id', '=', 'sent.agent_id');
            })
            ->leftJoinSub($paidTransfers, 'paid', function ($join) {
                $join->on('users.id', '=', 'paid.agent_id');
            })
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'sent.sent_count',
                DB::raw('COALESCE(paid.paid_count, 0) as paid_count'),
                DB::raw('(COALESCE(sent.sent_count, 0) + COALESCE(paid.paid_count, 0)) as total_count')
            )
            ->orderBy('total_count', 'desc')
            ->limit($limit)
            ->get();

        return $agents;
    }
}
