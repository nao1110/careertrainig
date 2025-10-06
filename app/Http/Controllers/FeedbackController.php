<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Feedback;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FeedbackController extends Controller
{
    use AuthorizesRequests;

    /**
     * フィードバック一覧表示
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isCandidate()) {
            // 受験生: 自分宛のフィードバックを表示
            $feedbacks = Feedback::where('candidate_id', $user->id)
                ->with(['appointment', 'consultant'])
                ->orderByDesc('created_at')
                ->get();
        } else {
            // コンサルタント: 自分が作成したフィードバックを表示
            $feedbacks = Feedback::where('consultant_id', $user->id)
                ->with(['appointment', 'candidate'])
                ->orderByDesc('created_at')
                ->get();
        }

        return view('feedback.index', compact('feedbacks'));
    }

    /**
     * フィードバック作成フォームの表示
     */
    public function create(Appointment $appointment)
    {
        // コンサルタントのみフィードバックを作成できる
        if (!auth()->user()->isConsultant()) {
            abort(403, 'コンサルタントのみフィードバックを作成できます。');
        }

        // アポイントメントが完了済みかチェック
        if ($appointment->status !== 'completed') {
            return redirect()->route('dashboard')->with('error', 'アポイントメントが完了していません。');
        }

        // 既にフィードバックが存在するかチェック
        if ($appointment->feedback) {
            return redirect()->route('dashboard')->with('error', '既にフィードバックが作成されています。');
        }

        // アポイントメントの担当コンサルタントかチェック
        if ($appointment->consultant_id !== auth()->id()) {
            abort(403, 'このアポイントメントの担当コンサルタントではありません。');
        }

        return view('feedback.create', compact('appointment'));
    }

    /**
     * フィードバック詳細の表示
     */
    public function show(Feedback $feedback)
    {
        // 関連データをロード
        $feedback->load(['appointment.candidate', 'appointment.persona', 'consultant']);

        // アクセス権限をチェック
        $user = auth()->user();
        if (!$user->isConsultant() && !$user->isCandidate()) {
            abort(403, 'フィードバックの閲覧権限がありません。');
        }

        // 候補者の場合は自分のフィードバックのみ閲覧可能
        if ($user->isCandidate() && $feedback->candidate_id !== $user->id) {
            abort(403, '他の候補者のフィードバックは閲覧できません。');
        }

        // コンサルタントの場合は自分が作成したフィードバックのみ閲覧可能
        if ($user->isConsultant() && $feedback->consultant_id !== $user->id) {
            abort(403, '他のコンサルタントが作成したフィードバックは閲覧できません。');
        }

        return view('feedback.show', compact('feedback'));
    }

    /**
     * フィードバックの保存
     */
    public function store(Request $request, Appointment $appointment)
    {
        // コンサルタントのみフィードバックを作成できる
        if (!auth()->user()->isConsultant()) {
            abort(403, 'コンサルタントのみフィードバックを作成できます。');
        }

        $request->validate([
            'overall_rating' => 'required|integer|between:1,5',
            'listening_skills' => 'nullable|integer|between:1,5',
            'questioning_skills' => 'nullable|integer|between:1,5',
            'empathy_skills' => 'nullable|integer|between:1,5',
            'goal_setting_skills' => 'nullable|integer|between:1,5',
            'solution_skills' => 'nullable|integer|between:1,5',
            'strengths' => 'nullable|string|max:1000',
            'improvements' => 'nullable|string|max:1000',
            'specific_advice' => 'nullable|string|max:1000',
            'consultant_comments' => 'nullable|string|max:1000',
            'exam_tips' => 'nullable|string|max:1000',
        ]);

        // アポイントメントが完了済みかチェック
        if ($appointment->status !== 'completed') {
            return redirect()->route('dashboard')->with('error', 'アポイントメントが完了していません。');
        }

        // 既にフィードバックが存在するかチェック
        if ($appointment->feedback) {
            return redirect()->route('dashboard')->with('error', '既にフィードバックが作成されています。');
        }

        // アポイントメントの担当コンサルタントかチェック
        if ($appointment->consultant_id !== auth()->id()) {
            abort(403, 'このアポイントメントの担当コンサルタントではありません。');
        }

        // フィードバックを作成
        Feedback::create([
            'appointment_id' => $appointment->id,
            'consultant_id' => auth()->id(),
            'candidate_id' => $appointment->candidate_id,
            'overall_rating' => $request->overall_rating,
            'listening_skills' => $request->listening_skills,
            'questioning_skills' => $request->questioning_skills,
            'empathy_skills' => $request->empathy_skills,
            'goal_setting_skills' => $request->goal_setting_skills,
            'solution_skills' => $request->solution_skills,
            'strengths' => $request->strengths,
            'improvements' => $request->improvements,
            'specific_advice' => $request->specific_advice,
            'consultant_comments' => $request->consultant_comments,
            'exam_tips' => $request->exam_tips,
            'recommended_for_exam' => true, // 全員試験受験予定のため固定値
        ]);

        return redirect()->route('dashboard')->with('success', 'フィードバックを作成しました。');
    }
}
