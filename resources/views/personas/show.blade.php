@extends('layouts.app')

@section('title', $persona->name . ' - ペルソナ詳細')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-user text-primary me-2"></i>
                    {{ $persona->name }}
                </h1>
                <div>
                    <a href="{{ route('personas.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i>一覧に戻る
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-home me-1"></i>ダッシュボード
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 基本情報 -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card me-2"></i>基本情報
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>年齢:</strong></div>
                        <div class="col-sm-7">{{ $persona->age }}歳</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>性別:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge 
                                @if($persona->gender === '女性') bg-pink
                                @else bg-blue
                                @endif
                                text-white">
                                {{ $persona->gender }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>職業:</strong></div>
                        <div class="col-sm-7">{{ $persona->occupation }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>キャリア年数:</strong></div>
                        <div class="col-sm-7">{{ $persona->career_years }}年</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-5"><strong>難易度:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge 
                                @if($persona->difficulty_level === '初級') bg-success
                                @elseif($persona->difficulty_level === '中級') bg-warning text-dark
                                @else bg-danger
                                @endif
                            ">
                                {{ $persona->difficulty_level }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-0">
                        <div class="col-sm-5"><strong>相談カテゴリ:</strong></div>
                        <div class="col-sm-7">
                            <span class="badge bg-secondary">{{ $persona->concern_category }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 背景・経歴 -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>背景・経歴
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $persona->background }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 相談内容 -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>相談内容
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">具体的な悩み</h6>
                        <p>{{ $persona->specific_concern }}</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-muted">望む結果</h6>
                        <p class="mb-0">{{ $persona->desired_outcome }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 性格・特徴 -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-heart me-2"></i>性格・特徴
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">性格的特徴</h6>
                        <p>{{ $persona->personality_traits }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted">コミュニケーションスタイル</h6>
                        <p>{{ $persona->communication_style }}</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-muted">動機・要因</h6>
                        <p class="mb-0">{{ $persona->motivation_factors }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 面談での振る舞い -->
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>面談での振る舞い・対応方法
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">冒頭での発言</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0 fst-italic">"{{ $persona->opening_statement }}"</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">重要な開示ポイント</h6>
                            <p>{{ $persona->key_points_to_reveal }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">感情的反応</h6>
                            <p>{{ $persona->emotional_responses }}</p>
                        </div>
                        <div class="col-md-6 mb-0">
                            <h6 class="text-muted">抵抗ポイント</h6>
                            <p class="mb-0">{{ $persona->resistance_points }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 学習目標・使用上の注意 -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-target me-2"></i>学習目標
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $persona->learning_objectives }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-orange text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>使用上の注意
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $persona->usage_notes }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-pink {
    background-color: #e91e63 !important;
}
.bg-blue {
    background-color: #2196f3 !important;
}
.bg-purple {
    background-color: #9c27b0 !important;
}
.bg-orange {
    background-color: #ff9800 !important;
}
</style>
@endsection