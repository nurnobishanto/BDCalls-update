<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // USERS
        $userCount = \App\Models\User::count();
        $usersLastMonth = \App\Models\User::where('created_at', '>=', now()->subMonth())->count();
        [$userDesc, $userIcon, $userColor] = $this->getTrendData($userCount, $usersLastMonth);

        // IP NUMBERS
        $ipNumbersCount = \App\Models\IpNumber::count();
        $ipNumbersLastMonth = \App\Models\IpNumber::where('created_at', '>=', now()->subMonth())->count();
        [$ipDesc, $ipIcon, $ipColor] = $this->getTrendData($ipNumbersCount, $ipNumbersLastMonth);

        // APPLY NUMBERS
        $applyNumbersCount = \App\Models\ApplyNumber::count();
        $applyNumbersLastMonth = \App\Models\ApplyNumber::where('created_at', '>=', now()->subMonth())->count();
        [$applyDesc, $applyIcon, $applyColor] = $this->getTrendData($applyNumbersCount, $applyNumbersLastMonth);

        // RECHARGE TOTAL (sum amount)
//        $rechargeSum = \App\Models\Recharge::sum('amount');
//        $rechargeSumLastMonth = \App\Models\Recharge::where('created_at', '>=', now()->subMonth())->sum('amount');
//        [$rechargeDesc, $rechargeIcon, $rechargeColor] = $this->getTrendData($rechargeSum, $rechargeSumLastMonth, true);

        // DUE BILL TOTAL (sum amount_due)
//        $dueBillSum = \App\Models\DueBill::sum('amount_due');
//        $dueBillSumLastMonth = \App\Models\DueBill::where('created_at', '>=', now()->subMonth())->sum('amount_due');
//        [$dueBillDesc, $dueBillIcon, $dueBillColor] = $this->getTrendData($dueBillSum, $dueBillSumLastMonth, true);

        // PAID BILL REQUESTS
//        $paidBillRequestsCount = \App\Models\PaidBillRequest::count();
//        $paidBillRequestsLastMonth = \App\Models\PaidBillRequest::where('created_at', '>=', now()->subMonth())->count();
//        [$paidBillDesc, $paidBillIcon, $paidBillColor] = $this->getTrendData($paidBillRequestsCount, $paidBillRequestsLastMonth);

        // PACKAGES
        $packageCount = \App\Models\Package::where('status',true)->count();
        $packageLastMonth = \App\Models\Package::where('status',true)->where('created_at', '>=', now()->subMonth())->count();
        [$packageDesc, $packageIcon, $packageColor] = $this->getTrendData($packageCount, $packageLastMonth);

        return [
            Stat::make('All Users', $userCount)
                ->description($userDesc)
                ->descriptionIcon($userIcon)
                ->color($userColor)
                ->url(url('admin/users'))
                ->extraAttributes(['class' => 'cursor-pointer']),

            Stat::make('IP Numbers', $ipNumbersCount)
                ->description($ipDesc)
                ->descriptionIcon($ipIcon)
                ->color($ipColor)
                ->url(url('admin/ip-numbers'))
                ->extraAttributes(['class' => 'cursor-pointer']),

            Stat::make('Apply Numbers', $applyNumbersCount)
                ->description($applyDesc)
                ->descriptionIcon($applyIcon)
                ->color($applyColor)
                ->url(url('admin/apply-numbers'))
                ->extraAttributes(['class' => 'cursor-pointer']),

//            Stat::make('Recharge Total', '$' . number_format($rechargeSum, 2))
//                ->description($rechargeDesc)
//                ->descriptionIcon($rechargeIcon)
//                ->color($rechargeColor)
//                ->url(url('admin/recharges'))
//                ->extraAttributes(['class' => 'cursor-pointer']),
//
//            Stat::make('Due Bill Total', '$' . number_format($dueBillSum, 2))
//                ->description($dueBillDesc)
//                ->descriptionIcon($dueBillIcon)
//                ->color($dueBillColor)
//                ->url(url('admin/due-bills'))
//                ->extraAttributes(['class' => 'cursor-pointer']),
//
//            Stat::make('Paid Bill Requests', $paidBillRequestsCount)
//                ->description($paidBillDesc)
//                ->descriptionIcon($paidBillIcon)
//                ->color($paidBillColor)
//                ->url(url('admin/paid-bill-requests'))
//                ->extraAttributes(['class' => 'cursor-pointer']),

            Stat::make('Packages', $packageCount)
                ->description($packageDesc)
                ->descriptionIcon($packageIcon)
                ->color($packageColor)
                ->url(url('admin/packages'))
                ->extraAttributes(['class' => 'cursor-pointer']),

        ];
    }
    protected function getTrendData($current, $previous, bool $isMoney = false): array
    {
        if ($previous > 0) {
            $change = (($current - $previous) / $previous) * 100;
            $changeRounded = round($change, 1);

            if ($changeRounded > 0) {
                $desc = ($isMoney ? '$' : '') . abs($changeRounded) . '% increase';
                $icon = 'heroicon-m-arrow-trending-up';
                $color = 'success';
            } elseif ($changeRounded < 0) {
                $desc = ($isMoney ? '$' : '') . abs($changeRounded) . '% decrease';
                $icon = 'heroicon-m-arrow-trending-down';
                $color = 'danger';
            } else {
                $desc = 'No change';
                $icon = null;
                $color = 'secondary';
            }
        } else {
            // No previous data — no trend available
            $desc = $isMoney ? '$0 change' : 'No data for comparison';
            $icon = null;
            $color = 'secondary';
        }

        return [$desc, $icon, $color];
    }
}
