@extends('layouts.app')

@section('title', 'ペルソナサンプル事例')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-users me-2"></i>ペルソナサンプル事例</h2>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>ダッシュボードに戻る
                </a>
            </div>

            <div class="alert alert-info mb-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>このページについて</strong><br>
                キャリアコンサルティングでよく見られる相談者のペルソナ事例です。相談者の背景や悩みを理解し、適切なコンサルティング手法を検討する際の参考にご活用ください。
            </div>

            <div class="row">
                @foreach($samplePersonas as $index => $persona)
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="fas fa-user-circle me-2"></i>
                            <div>
                                <h5 class="mb-0">{{ $persona['name'] }}</h5>
                                <small>{{ $persona['age'] }}</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="text-primary"><i class="fas fa-graduation-cap me-1"></i>背景</h6>
                                <p class="small text-muted mb-2">{{ $persona['background'] }}</p>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-primary"><i class="fas fa-home me-1"></i>家族構成</h6>
                                <p class="small text-muted mb-2">{{ $persona['family'] }}</p>
                            </div>

                            @if($persona['consultation_month'])
                            <div class="mb-3">
                                <h6 class="text-primary"><i class="fas fa-calendar me-1"></i>相談月</h6>
                                <p class="small text-muted mb-2">{{ $persona['consultation_month'] }}</p>
                            </div>
                            @endif

                            <div class="mb-3">
                                <h6 class="text-primary"><i class="fas fa-comment-dots me-1"></i>相談したいこと</h6>
                                <div class="consultation-content">
                                    <p class="small">{{ $persona['consultation_content'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-lightbulb me-1"></i>
                                サンプルケース {{ $index + 1 }}
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 p-4 bg-light rounded">
                <h5><i class="fas fa-question-circle me-2"></i>これらのペルソナの活用方法</h5>
                <ul class="mb-0">
                    <li><strong>受験者の方</strong>：実際のキャリアコンサルティングではこのような多様な相談者がいることを理解し、面談の準備に役立ててください。</li>
                    <li><strong>キャリアコンサルタントの方</strong>：各ペルソナに対してどのような支援アプローチが適切か検討し、実際の面談でのスキル向上にお役立てください。</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.consultation-content {
    max-height: 150px;
    overflow-y: auto;
    padding: 10px;
    background-color: #f8f9fa;
    border-left: 3px solid #007bff;
    border-radius: 0 5px 5px 0;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection