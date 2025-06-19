<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class HistorySettingController extends Controller
{
    public function index()
    {
        $cleanupPeriod = Setting::getValue('history_cleanup_period', 'monthly');
        $retentionDays = Setting::getValue('history_retention_days', '30');

        return view('settings.history', compact('cleanupPeriod', 'retentionDays'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cleanup_period' => 'required|in:biweekly,monthly',
            'retention_days' => 'required|in:14,30', // 14 hari atau 30 hari
        ]);

        Setting::setValue('history_cleanup_period', $request->cleanup_period);
        Setting::setValue('history_retention_days', $request->retention_days);

        return redirect()->route('settings.history')
            ->with('success', 'Pengaturan riwayat pemesanan berhasil diperbarui.');
    }
}
