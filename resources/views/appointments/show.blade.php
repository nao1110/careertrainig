@extends('layouts.app')

@section('title', '予約詳細')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">予約詳細</h5>
                    <a href="{{ route('appointments.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>一覧に戻る
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- 基本情報 -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-3">基本情報</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th>日時:</th>
                                    <td>{{ $appointment->appointment_datetime->format('Y年m月d日 H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>時間:</th>
                                    <td>{{ $appointment->duration_minutes }}分</td>
                                </tr>
                                <tr>
                                    <th>ステータス:</th>
                                    <td>
                                        @switch($appointment->status)
                                            @case('pending')
                                                <span class="badge bg-warning">承認待ち</span>
                                                @break
                                            @case('matched')
                                                <span class="badge bg-success">マッチング済み</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-primary">完了</span>
                                                @break
                                            @case('cancelled')
                                                <span class="badge bg-secondary">キャンセル</span>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- 人物情報 -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-3">参加者</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <th>受験者:</th>
                                    <td>{{ $appointment->candidate->name }}</td>
                                </tr>
                                <tr>
                                    <th>コンサルタント:</th>
                                    <td>{{ $appointment->consultant ? $appointment->consultant->name : '未定' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- ペルソナ情報（コンサルタントのみ表示） -->
                    @if(auth()->user()->isConsultant() && $appointment->persona)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">ペルソナ情報</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>{{ $appointment->persona->name }}（{{ $appointment->persona->age }}歳）</h6>
                                    <p><strong>職業:</strong> {{ $appointment->persona->occupation }}</p>
                                    <p><strong>相談カテゴリ:</strong> {{ $appointment->persona->concern_category }}</p>
                                    <p><strong>相談内容:</strong> {{ $appointment->persona->concern_summary }}</p>
                                    @if($appointment->persona->background)
                                        <p><strong>背景:</strong> {{ $appointment->persona->background }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 相談内容 -->
                    @if($appointment->consultation_topic || $appointment->special_requests)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">相談内容・要望</h6>
                            @if($appointment->consultation_topic)
                                <div class="mb-3">
                                    <strong>相談トピック:</strong>
                                    <p class="mt-1">{{ $appointment->consultation_topic }}</p>
                                </div>
                            @endif
                            @if($appointment->special_requests)
                                <div class="mb-3">
                                    <strong>特別な要望:</strong>
                                    <p class="mt-1">{{ $appointment->special_requests }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- フィードバック -->
                    @if($appointment->feedback)
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">フィードバック</h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <strong>総合評価:</strong>
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $appointment->feedback->overall_rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            ({{ $appointment->feedback->overall_rating }}/5)
                                        </div>
                                    </div>
                                    
                                    @if($appointment->feedback->strengths)
                                        <div class="mb-3">
                                            <strong>良かった点・強み:</strong>
                                            <p class="mt-1">{{ $appointment->feedback->strengths }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($appointment->feedback->areas_for_improvement)
                                        <div class="mb-3">
                                            <strong>改善点・課題:</strong>
                                            <p class="mt-1">{{ $appointment->feedback->areas_for_improvement }}</p>
                                        </div>
                                    @endif
                                    
                                    @if($appointment->feedback->specific_advice)
                                        <div class="mb-3">
                                            <strong>具体的なアドバイス:</strong>
                                            <p class="mt-1">{{ $appointment->feedback->specific_advice }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- アクションボタン -->
                    <div class="mt-4">
                        @if(auth()->user()->isCandidate() && $appointment->status === 'pending')
                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-outline-primary me-2">
                                <i class="fas fa-edit me-1"></i>編集
                            </a>
                            <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger" 
                                        onclick="return confirm('本当にキャンセルしますか？')">
                                    <i class="fas fa-times me-1"></i>キャンセル
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->isConsultant() && $appointment->status === 'pending')
                            <form action="{{ route('appointments.approve', $appointment) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="fas fa-check me-1"></i>承認
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->isConsultant() && $appointment->status === 'matched')
                            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-flag-checkered me-1"></i>完了
                                </button>
                            </form>
                        @endif

                        @if(auth()->user()->isConsultant() && $appointment->status === 'completed' && !$appointment->feedback)
                            <a href="{{ route('feedback.create', $appointment) }}" class="btn btn-warning">
                                <i class="fas fa-comment me-1"></i>フィードバック作成
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
