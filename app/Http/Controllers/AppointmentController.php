<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Persona;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // ミドルウェアはルートで適用済み（web.phpで定義）
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isCandidate()) {
            $appointments = $user->candidateAppointments()
                ->with(['consultant'])
                ->orderByDesc('appointment_datetime')
                ->paginate(10);
        } else {
            $appointments = $user->consultantAppointments()
                ->with(['candidate'])
                ->orderByDesc('appointment_datetime')
                ->paginate(10);
        }

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 受験者のみが予約作成可能
        if (!auth()->user()->isCandidate()) {
            return redirect('/dashboard')->with('error', '受験者のみが予約を作成できます。');
        }

        // ペルソナを取得
        try {
            $personas = Persona::where('is_active', true)->get();
        } catch (\Exception $e) {
            \Log::error('Failed to fetch personas: ' . $e->getMessage());
            return redirect('/dashboard')->with('error', 'ペルソナの取得に失敗しました。');
        }
        
        // URL パラメータから日時を取得
        $selectedDatetime = null;
        if ($request->has('datetime')) {
            try {
                $selectedDatetime = Carbon::parse($request->get('datetime'));
            } catch (\Exception $e) {
                \Log::error('Invalid datetime parameter: ' . $e->getMessage());
            }
        }

        // 次の土日朝9時の選択肢を生成
        try {
            $weekendOptions = $this->generateWeekendOptions();
        } catch (\Exception $e) {
            \Log::error('Failed to generate weekend options: ' . $e->getMessage());
            $weekendOptions = [];
        }

        return view('appointments.create', compact('personas', 'selectedDatetime', 'weekendOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 受験者のみが予約作成可能
        if (!auth()->user()->isCandidate()) {
            return redirect('/dashboard')->with('error', '受験者のみが予約を作成できます。');
        }

        try {
            $validated = $request->validate([
                'appointment_datetime' => 'required|date|after:now',
            ]);

            // 指定された練習日朝9時チェック
            $datetime = Carbon::parse($validated['appointment_datetime']);
            if (!$this->isValidWeekendMorning($datetime)) {
                return back()->withErrors(['appointment_datetime' => '予約は指定された練習日のみ可能です。']);
            }

            // 重複チェック
            $existing = Appointment::where('appointment_datetime', $datetime)
                ->where('candidate_id', auth()->id())
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existing) {
                return back()->withErrors(['appointment_datetime' => 'この日時にはすでに予約があります。']);
            }

            // ペルソナをランダムに割り当て（講師のみが見れる）
            $randomPersona = Persona::where('is_active', true)->inRandomOrder()->first();

            $appointment = Appointment::create([
                'candidate_id' => auth()->id(),
                'appointment_datetime' => $datetime,
                'duration_minutes' => 45,
                'status' => 'pending',
                'persona_id' => $randomPersona ? $randomPersona->id : null,
                'consultation_topic' => 'キャリアコンサルタント面接練習',
                'consultation_type' => '面接練習',
                'special_requests' => '',
            ]);

            return redirect('/dashboard')->with('success', '予約を作成しました。コンサルタントの承認をお待ちください。');
            
        } catch (\Exception $e) {
            \Log::error('Failed to create appointment: ' . $e->getMessage());
            return back()->with('error', '予約の作成に失敗しました。もう一度お試しください。')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        // アクセス権チェック
        if (!$this->canAccess($appointment)) {
            return redirect('/dashboard')->with('error', 'この予約にアクセスする権限がありません。');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        // 受験者のみ、ペンディング状態のみ編集可能
        if (!auth()->user()->isCandidate() || 
            $appointment->candidate_id !== auth()->id() || 
            $appointment->status !== 'pending') {
            return redirect('/dashboard')->with('error', '編集できません。');
        }

        $personas = Persona::active()->get();
        $weekendOptions = $this->generateWeekendOptions();

        return view('appointments.edit', compact('appointment', 'personas', 'weekendOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // 受験者のみ、ペンディング状態のみ編集可能
        if (!auth()->user()->isCandidate() || 
            $appointment->candidate_id !== auth()->id() || 
            $appointment->status !== 'pending') {
            return redirect('/dashboard')->with('error', '編集できません。');
        }

        $validated = $request->validate([
            'appointment_datetime' => 'required|date|after:now',
            'persona_id' => 'nullable|exists:personas,id',
            'consultation_topic' => 'nullable|string|max:500',
            'special_requests' => 'nullable|string|max:500',
        ]);

        $datetime = Carbon::parse($validated['appointment_datetime']);
        if (!$this->isValidWeekendMorning($datetime)) {
            return back()->withErrors(['appointment_datetime' => '予約は土日の朝9時のみ可能です。']);
        }

        $appointment->update($validated);

        return redirect('/dashboard')->with('success', '予約を更新しました。');
    }

    /**
     * コンサルタントが依頼を承認
     */
    public function approve(Appointment $appointment)
    {
        if (!auth()->user()->isConsultant() || $appointment->status !== 'pending') {
            return redirect('/dashboard')->with('error', '承認できません。');
        }

        // まずコンサルタント情報を設定
        $appointment->update([
            'consultant_id' => auth()->id(),
            'status' => 'approved'
        ]);

        // コンサルタント情報が設定された状態でGoogle Meet URLを生成
        $appointment->refresh(); // データベースから最新情報を取得
        $googleMeetUrl = $this->generateGoogleMeetUrl($appointment);

        // Google Meet URLを設定
        $appointment->update([
            'google_meet_url' => $googleMeetUrl
        ]);

        \Log::info('Appointment approved with Google Meet URL', [
            'appointment_id' => $appointment->id,
            'consultant_id' => auth()->id(),
            'status' => $appointment->status,
            'meet_url' => $googleMeetUrl
        ]);

        return redirect('/dashboard')->with('success', '依頼を承認しました。受験者にGoogle Meet URLが通知されました。');
    }

    /**
     * コンサルタントが依頼を不可
     */
    public function reject(Appointment $appointment)
    {
        if (!auth()->user()->isConsultant() || $appointment->status !== 'pending') {
            return redirect('/dashboard')->with('error', '不可処理できません。');
        }

        // アポイントメントを更新（statusをrejectedに変更）
        $appointment->update([
            'status' => 'rejected'
        ]);

        \Log::info('Appointment rejected', [
            'appointment_id' => $appointment->id,
            'consultant_id' => auth()->id(),
            'status' => $appointment->status
        ]);

        return redirect('/dashboard')->with('success', '依頼を不可としました。');
    }

    /**
     * フォールバック用のGoogle Meet URL生成
     * MVPとして、ユーザーが新しいMeetルームを作成する方式に変更
     */
    private function generateFallbackMeetUrl($appointment)
    {
        // Google Meetの新しいミーティング作成URL
        // ユーザーがクリックすると自動的に新しいルームが作成される
        return "https://meet.google.com/new";
    }

    /**
     * Google Meet URLを生成（Google Calendar APIまたはフォールバック）
     */
    private function generateGoogleMeetUrl($appointment)
    {
        \Log::info('Starting Google Meet URL generation', [
            'appointment_id' => $appointment->id,
            'consultant_id' => $appointment->consultant_id,
            'candidate_email' => $appointment->candidate->email,
            'consultant_email' => $appointment->consultant ? $appointment->consultant->email : 'Not assigned'
        ]);
        
        try {
            // Google Calendar Service を使用してMeet URLを生成
            $googleCalendarService = new \App\Services\GoogleCalendarService();
            $meetUrl = $googleCalendarService->createAppointmentEvent($appointment);
            
            if ($meetUrl && str_starts_with($meetUrl, 'https://meet.google.com/')) {
                \Log::info('Generated Google Meet URL via Google Calendar API', [
                    'appointment_id' => $appointment->id,
                    'url' => $meetUrl
                ]);
                return $meetUrl;
            } else {
                \Log::warning('Google Calendar API returned invalid URL', [
                    'appointment_id' => $appointment->id,
                    'returned_url' => $meetUrl
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate Google Meet URL via API', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        // フォールバック：実際のGoogle Meet URL形式を生成
        $meetUrl = $this->generateFallbackMeetUrl($appointment);
        
        \Log::info('Generated fallback Google Meet URL', [
            'appointment_id' => $appointment->id,
            'url' => $meetUrl
        ]);
        
        return $meetUrl;
    }

    /**
     * 面談完了処理
     */
    public function complete(Appointment $appointment)
    {
        if (!auth()->user()->isConsultant() || 
            $appointment->consultant_id !== auth()->id() || 
            !in_array($appointment->status, ['approved', 'matched'])) {
            return redirect('/dashboard')->with('error', '完了処理できません。');
        }

        $appointment->update(['status' => 'completed']);

        return redirect('/dashboard')->with('success', '面談が完了しました。フィードバックの入力をお願いします。');
    }

    /**
     * アポイントメントをキャンセル
     */
    public function destroy(Appointment $appointment)
    {
        $user = auth()->user();
        
        // キャンセル可能な条件をチェック
        if (($user->isCandidate() && $appointment->candidate_id !== $user->id) ||
            ($user->isConsultant() && $appointment->consultant_id !== $user->id) ||
            $appointment->status === 'completed') {
            return redirect('/dashboard')->with('error', 'キャンセルできません。');
        }

        // Google Calendarイベントを削除
        if ($appointment->google_calendar_event_id) {
            $calendarService = new GoogleCalendarService();
            $calendarService->deleteEvent($appointment->google_calendar_event_id);
        }

        // アポイントメントをキャンセル状態に更新
        $appointment->update(['status' => 'cancelled']);

        return redirect('/dashboard')->with('success', 'アポイントメントをキャンセルしました。');
    }

    // ヘルパーメソッド
    private function isValidWeekendMorning($datetime)
    {
        // キャリアコンサルタント練習用の特定日程をチェック
        $practiceeDates = [
            '2025-10-05', '2025-10-06', '2025-10-12', '2025-10-13', 
            '2025-10-19', '2025-10-20', '2025-10-26', '2025-10-27',
            '2025-11-08', '2025-11-09', '2025-11-14', '2025-11-15', 
            '2025-11-16', '2025-11-22', '2025-11-23'
        ];
        
        $dateOnly = $datetime->format('Y-m-d');
        return in_array($dateOnly, $practiceeDates) && $datetime->hour === 9 && $datetime->minute === 0;
    }

    private function canAccess($appointment)
    {
        $user = auth()->user();
        return $appointment->candidate_id === $user->id || 
               $appointment->consultant_id === $user->id;
    }

    private function generateWeekendOptions()
    {
        $options = [];
        
        // キャリアコンサルタント練習用の特定日程を設定
        $practiceeDates = [
            // 10月の土日（今日より後の日程）
            '2025-10-06', // 日
            '2025-10-12', // 土
            '2025-10-13', // 日
            '2025-10-19', // 土
            '2025-10-20', // 日
            '2025-10-26', // 土
            '2025-10-27', // 日
            
            // 11月の練習日程
            '2025-11-02', // 土
            '2025-11-03', // 日
            '2025-11-08', // 金（特別日程）
            '2025-11-09', // 土
            '2025-11-14', // 金（特別日程）
            '2025-11-15', // 土
            '2025-11-16', // 日
            '2025-11-22', // 土
            '2025-11-23', // 日
            '2025-11-29', // 土
            '2025-11-30', // 日
        ];
        
        $now = Carbon::now();
        
        foreach ($practiceeDates as $dateString) {
            $date = Carbon::parse($dateString)->setTime(9, 0, 0);
            
            // 現在時刻より後の日時のみを含める
            if ($date->gt($now)) {
                $options[] = $date;
            }
        }
        
        return $options;
    }
}
