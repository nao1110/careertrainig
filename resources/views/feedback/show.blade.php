@extends('layouts.app')

@section('title', 'フィードバック詳細')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">フィードバック詳細</h5>
                </div>
                <div class="card-body">
                    <!-- アポイントメント情報 -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">アポイントメント情報</h6>
                            <p><strong>受験者:</strong> {{ $feedback->appointment->candidate->name }}</p>
                            <p><strong>コンサルタント:</strong> {{ $feedback->consultant->name }}</p>
                            <p><strong>日時:</strong> {{ $feedback->appointment->appointment_datetime ? $feedback->appointment->appointment_datetime->format('Y年m月d日 H:i') : '未設定' }}</p>
                            <p><strong>選択ペルソナ:</strong> {{ $feedback->appointment->persona ? $feedback->appointment->persona->name : '未選択' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">総合評価</h6>
                            <div class="d-flex align-items-center">
                                <span class="fs-4 me-2">{{ $feedback->overall_rating ?? '未評価' }}</span>
                                @if($feedback->overall_rating)
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $feedback->overall_rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                @endif
                                @if($feedback->getAverageRating() > 0)
                                    <span class="ms-2 text-muted">({{ $feedback->getAverageRating() }} 平均)</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- 詳細評価セクション -->
                    <div class="mb-4">
                        <h6 class="mb-3">詳細評価</h6>
                        <div class="row">
                            @foreach($feedback->getDetailedRatings() as $criterion => $rating)
                                @if($rating)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>{{ $criterion }}</strong>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">{{ $rating }}</span>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }} small"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- フィードバック詳細 -->
                    <div class="mb-4">
                        <h6 class="mb-3">フィードバック詳細</h6>
                        
                        @if($feedback->strengths)
                        <div class="mb-3">
                            <h6 class="text-success"><i class="fas fa-plus-circle me-2"></i>良かった点</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $feedback->strengths }}
                            </div>
                        </div>
                        @endif

                        @if($feedback->improvements)
                        <div class="mb-3">
                            <h6 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>改善点</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $feedback->improvements }}
                            </div>
                        </div>
                        @endif

                        @if($feedback->specific_advice)
                        <div class="mb-3">
                            <h6 class="text-info"><i class="fas fa-lightbulb me-2"></i>具体的なアドバイス</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $feedback->specific_advice }}
                            </div>
                        </div>
                        @endif

                        @if($feedback->exam_tips)
                        <div class="mb-3">
                            <h6 class="text-primary"><i class="fas fa-graduation-cap me-2"></i>試験対策アドバイス</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $feedback->exam_tips }}
                            </div>
                        </div>
                        @endif

                        @if($feedback->consultant_comments)
                        <div class="mb-3">
                            <h6 class="text-muted"><i class="fas fa-comment me-2"></i>その他コメント</h6>
                            <div class="bg-light p-3 rounded">
                                {{ $feedback->consultant_comments }}
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- フィードバック作成日時 -->
                    <div class="text-muted small mb-3">
                        <i class="fas fa-clock me-1"></i>
                        フィードバック作成日時: {{ $feedback->created_at->format('Y年m月d日 H:i') }}
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>ダッシュボードに戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.fas.fa-star.small {
    font-size: 0.8rem;
}
</style>
@endsection
