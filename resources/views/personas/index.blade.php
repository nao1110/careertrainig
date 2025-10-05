@extends('layouts.app')

@section('title', '面接練習用ペルソナ一覧')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-users text-primary me-2"></i>
                    面接練習用ペルソナ一覧
                </h1>
                <div>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>ダッシュボードに戻る
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 使い方説明 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <i class="fas fa-info-circle fa-2x text-info"></i>
                    </div>
                    <div class="col-md-11">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-lightbulb me-2"></i>ペルソナ活用方法
                        </h6>
                        <p class="mb-2">
                            <strong>目的：</strong>各ペルソナは実際の面談で遭遇する可能性の高いクライアント像を再現しています。
                        </p>
                        <p class="mb-0">
                            <strong>活用方法：</strong>難易度を確認し、受験者のレベルに応じたペルソナを選択してください。詳細を確認することで、より効果的な面談練習を提供できます。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 難易度別ペルソナ表示 -->
    @if($groupedPersonas->isNotEmpty())
        @foreach(['初級', '中級', '上級'] as $level)
            @if($groupedPersonas->has($level))
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header 
                                @if($level === '初級') bg-success text-white
                                @elseif($level === '中級') bg-warning text-dark
                                @else bg-danger text-white
                                @endif
                            ">
                                <h5 class="mb-0">
                                    @if($level === '初級')
                                        <i class="fas fa-star me-2"></i>初級レベル
                                    @elseif($level === '中級')
                                        <i class="fas fa-star me-2"></i><i class="fas fa-star me-2"></i>中級レベル
                                    @else
                                        <i class="fas fa-star me-2"></i><i class="fas fa-star me-2"></i><i class="fas fa-star me-2"></i>上級レベル
                                    @endif
                                    <span class="badge bg-light text-dark ms-2">{{ $groupedPersonas[$level]->count() }}名</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($groupedPersonas[$level] as $persona)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="card h-100 border">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <h6 class="card-title mb-0">{{ $persona->name }}</h6>
                                                        <span class="badge 
                                                            @if($persona->gender === '女性') bg-pink
                                                            @else bg-blue
                                                            @endif
                                                            text-white small">
                                                            {{ $persona->gender }}
                                                        </span>
                                                    </div>
                                                    
                                                    <p class="text-muted small mb-2">
                                                        {{ $persona->getShortDescription() }}
                                                    </p>
                                                    
                                                    <div class="mb-2">
                                                        <span class="badge bg-secondary small">{{ $persona->concern_category }}</span>
                                                    </div>
                                                    
                                                    <p class="card-text small text-truncate" style="height: 2.4em;">
                                                        {{ $persona->specific_concern }}
                                                    </p>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                                        <small class="text-muted">
                                                            <i class="fas fa-briefcase me-1"></i>{{ $persona->career_years }}年
                                                        </small>
                                                        <a href="{{ route('personas.show', $persona) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye me-1"></i>詳細
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @else
        <!-- サンプルペルソナを表示 -->
        <div class="row">
            <div class="col-12">
                <div class="alert alert-info text-center mb-4">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>サンプルペルソナ</h5>
                    <p class="mb-0">現在はサンプルデータを表示しています。実際の面談練習でよく使用されるペルソナの例です。</p>
                </div>
            </div>
        </div>

        <!-- サンプルペルソナカード -->
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">初級レベル</h6>
                            <span class="badge bg-light text-primary">サンプル</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">山崎 玲奈（22歳）</h5>
                        <p class="card-text text-muted">大学生・就職活動中</p>
                        <p class="card-text small">「総合商社への憧れと現在の内定先で悩んでいる」</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-graduation-cap me-1"></i>大学4年生
                            </small>
                            <a href="{{ route('personas.samples') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>詳細
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-warning">
                    <div class="card-header bg-warning text-dark">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">中級レベル</h6>
                            <span class="badge bg-light text-warning">サンプル</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">野口 大輔（35歳）</h5>
                        <p class="card-text text-muted">編集者・異動による悩み</p>
                        <p class="card-text small">「営業への異動でモチベーション低下、転勤話も」</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-briefcase me-1"></i>13年目
                            </small>
                            <a href="{{ route('personas.samples') }}" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-eye me-1"></i>詳細
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-header bg-success text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">上級レベル</h6>
                            <span class="badge bg-light text-success">サンプル</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">中谷 則幸（62歳）</h5>
                        <p class="card-text text-muted">定年退職者・再就職悩み</p>
                        <p class="card-text small">「再雇用を断った後悔と再就職活動の困難」</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-user-tie me-1"></i>元営業本部長
                            </small>
                            <a href="{{ route('personas.samples') }}" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-eye me-1"></i>詳細
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="text-info mb-3">
                            <i class="fas fa-users me-2"></i>
                            さらに詳細なペルソナ事例
                        </h6>
                        <p class="mb-3">5つの詳細なペルソナ事例をご確認いただけます</p>
                        <a href="{{ route('personas.samples') }}" class="btn btn-info">
                            <i class="fas fa-arrow-right me-2"></i>
                            5つのペルソナ事例を見る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.bg-pink {
    background-color: #e91e63 !important;
}
.bg-blue {
    background-color: #2196f3 !important;
}
</style>
@endsection