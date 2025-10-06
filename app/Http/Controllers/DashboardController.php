<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect('/auth/google');
        }
        
        Log::info('Dashboard accessed', [
            'user_id' => $user->id,
            'user_type' => $user->user_type,
            'name' => $user->name
        ]);
        
        if ($user->user_type === 'consultant') {
            return $this->consultantDashboard();
        }
        
        return $this->candidateDashboard();
    }

    private function candidateDashboard()
    {
        $user = auth()->user();
        
        // 今後の予約（承認待ち・承認済み・マッチング済み）
        $upcomingAppointments = $user->candidateAppointments()
            ->where('appointment_datetime', '>', now())
            ->whereIn('status', ['pending', 'approved', 'matched'])
            ->with(['consultant', 'persona'])
            ->orderBy('appointment_datetime')
            ->get();

        // 過去の予約（完了済み）
        $pastAppointments = $user->candidateAppointments()
            ->where('appointment_datetime', '<', now())
            ->where('status', 'completed')
            ->with(['consultant', 'persona', 'feedback'])
            ->orderByDesc('appointment_datetime')
            ->get();

        // 承認待ちの予約数
        $pendingCount = $user->candidateAppointments()
            ->where('status', 'pending')
            ->count();

        // 次回利用可能な練習日の日程
        $nextWeekends = $this->getNextWeekends();

        return view('dashboard.candidate', compact(
            'user',
            'upcomingAppointments',
            'pastAppointments', 
            'pendingCount',
            'nextWeekends'
        ));
    }

    private function consultantDashboard()
    {
        $user = auth()->user();
        
        // 承認待ちの依頼
        $pendingRequests = Appointment::where('status', 'pending')
            ->whereNull('consultant_id')
            ->with(['candidate', 'persona'])
            ->orderBy('appointment_datetime')
            ->get();

        // 確定済みの今後の予約
        $upcomingAppointments = $user->consultantAppointments()
            ->where('appointment_datetime', '>', now())
            ->whereIn('status', ['approved', 'matched'])
            ->with(['candidate', 'persona'])
            ->orderBy('appointment_datetime')
            ->get();

        // 完了した面談
        $completedAppointments = $user->consultantAppointments()
            ->where('status', 'completed')
            ->with(['candidate', 'persona'])
            ->orderByDesc('appointment_datetime')
            ->get();

        return view('dashboard.consultant', compact(
            'user',
            'pendingRequests',
            'upcomingAppointments',
            'completedAppointments'
        ));
    }

    private function getNextWeekends()
    {
        // キャリアコンサルタント練習用の特定日程を設定
        $practiceeDates = [
            // 10月の土日
            '2025-10-05', '2025-10-06', '2025-10-12', '2025-10-13', 
            '2025-10-19', '2025-10-20', '2025-10-26', '2025-10-27',
            
            // 11月の練習日程
            '2025-11-08', '2025-11-09', '2025-11-14', '2025-11-15', 
            '2025-11-16', '2025-11-22', '2025-11-23'
        ];
        
        $weekends = [];
        $now = now();
        
        foreach ($practiceeDates as $dateString) {
            $date = \Carbon\Carbon::parse($dateString)->setTime(9, 0, 0);
            
            // 過去の日時は除外
            if ($date->gt($now)) {
                $weekends[] = $date;
            }
        }
        
        return collect($weekends);
    }
}
