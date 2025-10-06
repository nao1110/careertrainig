@extends('layouts.app')

@section('title', 'フィードバック一覧')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>
                        <i class="fas fa-clipboard-list me-2"></i>
                        フィードバック一覧
                    </h4>
                </div>
                <div class="card-body">
                    @if($feedbacks->count() > 0)
                        <div class="row">
                            @foreach($feedbacks as $feedback)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border-primary">
                                        <div class="card-header bg-light">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="fw-bold">
                                                    {{ $feedback->appointment->appointment_datetime->format('Y年n月j日 H:i') }}
                                                </span>
                                                <span class="badge 
                                                    @if($feedback->overall_rating >= 4) bg-success
                                                    @elseif($feedback->overall_rating >= 3) bg-warning  
                                                    @else bg-danger
                                                    @endif">
                                                    評価: {{ $feedback->overall_rating }}/5
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if(auth()->user()->isCandidate())
                                                <p><strong><i class="fas fa-user-tie me-1"></i>コンサルタント:</strong><br>
                                                {{ $feedback->consultant->name }}</p>
                                            @else
                                                <p><strong><i class="fas fa-user-graduate me-1"></i>受験者:</strong><br>
                                                {{ $feedback->candidate->name }}</p>
                                            @endif
                                            
                                            @if($feedback->appointment->persona)
                                                <p><strong><i class="fas fa-user me-1"></i>ペルソナ:</strong><br>
                                                {{ $feedback->appointment->persona->name }}</p>
                                            @endif

                                            @if($feedback->strengths)
                                                <div class="mb-2">
                                                    <strong class="text-success"><i class="fas fa-thumbs-up me-1"></i>良かった点:</strong>
                                                    <p class="small">{{ Str::limit($feedback->strengths, 100) }}</p>
                                                </div>
                                            @endif

                                            @if($feedback->improvements)
                                                <div class="mb-2">
                                                    <strong class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>改善点:</strong>
                                                    <p class="small">{{ Str::limit($feedback->improvements, 100) }}</p>
                                                </div>
                                            @endif

                                            <div class="text-muted small">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                作成日: {{ $feedback->created_at->format('Y年n月j日 H:i') }}
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('feedback.show', $feedback) }}" class="btn btn-primary btn-sm w-100">
                                                <i class="fas fa-eye me-1"></i>詳細を見る
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">フィードバックがありません</h5>
                            @if(auth()->user()->isCandidate())
                                <p class="text-muted">面談を完了すると、コンサルタントからのフィードバックがここに表示されます。</p>
                                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-1"></i>新しい面談を予約
                                </a>
                            @else
                                <p class="text-muted">完了した面談に対してフィードバックを作成すると、ここに表示されます。</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-tachometer-alt me-1"></i>ダッシュボードに戻る
                                </a>
                            @endif
                        </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>ダッシュボードに戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
</style>
@endpush