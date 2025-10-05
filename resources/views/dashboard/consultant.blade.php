@extends('layouts.app')

@section('title', 'コンサルタントダッシュボード - キャリトレ・モーニング')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                    コンサルタントダッシュボード
                </h1>
                <div>
                    <span class="badge bg-success">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Meet使用方法の説明 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info border-0 shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-1 text-center">
                        <i class="fas fa-video fa-2x text-info"></i>
                    </div>
                    <div class="col-md-11">
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-info-circle me-2"></i>Google Meet面談について
                        </h6>
                        <p class="mb-2">
                            <strong>面談開始方法：</strong>予約確定後、「<i class="fas fa-video mx-1"></i>面談開始（Google Meet）」ボタンをクリックしてGoogle Meetに参加してください。
                        </p>
                        <p class="mb-0">
                            <strong>参加時間：</strong>面談開始時刻の5分前から参加可能です。受験者の到着をお待ちください。
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 概要カード -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-hourglass-half fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $pendingRequests->count() }}</h4>
                            <small>新しい依頼</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $upcomingAppointments->count() }}</h4>
                            <small>今後の予約</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">{{ $completedAppointments->count() }}</h4>
                            <small>完了した指導</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-yen-sign fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">¥{{ number_format($completedAppointments->count() * 1500) }}</h4>
                            <small>今月の収入</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 新しい依頼 -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        面接練習依頼 ({{ $pendingRequests->count() }}件)
                    </h5>
                </div>
                <div class="card-body">
                    @if($pendingRequests->count() > 0)
                        @foreach($pendingRequests->take(5) as $request)
                            <div class="border-start border-3 border-warning ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $request->appointment_datetime->format('n月j日(D) G:i') }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $request->candidate->name }}
                                        </small>
                                    </div>
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('appointments.approve', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check me-1"></i>承認
                                            </button>
                                        </form>
                                        <form action="{{ route('appointments.reject', $request) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('この依頼を不可にしますか？')">
                                                <i class="fas fa-times me-1"></i>不可
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-check fa-3x mb-3"></i>
                            <p>面接練習の依頼はありません</p>
                            <small>受験生からの新しい予約をお待ちください</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 今後の予約 -->
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        今後の予約
                    </h5>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->count() > 0)
                        @foreach($upcomingAppointments as $appointment)
                            <div class="border-start border-3 border-primary ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $appointment->appointment_datetime->format('n月j日(D) G:i') }}</h6>
                                        <div>
                                            <strong class="text-dark">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $appointment->candidate->name }}
                                            </strong>
                                        </div>
                                    </div>
                                    <div>
                                        @if($appointment->google_meet_url && in_array($appointment->status, ['approved', 'matched']))
                                            <a href="{{ $appointment->google_meet_url }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-success me-1"
                                               title="面談開始（Google Meet）">
                                                <i class="fas fa-video me-1"></i>面談開始（Google Meet）
                                            </a>
                                        @else
                                            <span class="badge bg-secondary me-1">
                                                <i class="fas fa-clock me-1"></i>Meet URL準備中
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>今後の予約はありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ペルソナサンプルと指導ガイド -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        練習用ペルソナ事例
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">面接練習で使用する相談者事例</p>
                    
                    <!-- ペルソナ1：山崎玲奈 -->
                    <div class="border-start border-3 border-primary ps-3 mb-3">
                        <h6 class="text-primary mb-1">
                            <i class="fas fa-graduation-cap me-1"></i>
                            <a href="{{ route('personas.show', 1) }}" class="text-primary text-decoration-none">
                                山崎 玲奈（22歳・大学生）
                            </a>
                        </h6>
                        <small class="text-muted">総合商社への憧れと既存内定先との比較で悩み中</small>
                    </div>

                    <!-- ペルソナ2：野口大輔 -->
                    <div class="border-start border-3 border-success ps-3 mb-3">
                        <h6 class="text-success mb-1">
                            <i class="fas fa-briefcase me-1"></i>
                            <a href="{{ route('personas.show', 2) }}" class="text-success text-decoration-none">
                                野口 大輔（35歳・出版社）
                            </a>
                        </h6>
                        <small class="text-muted">編集から営業異動、交際相手の転勤で将来に悩み</small>
                    </div>

                    <div class="alert alert-info py-2 mb-0">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            面接練習時にこれらの設定で相談者役を演じてもらいます
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book me-2"></i>
                        指導ガイド
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">効果的な面談のためのポイント</p>
                    <ul class="list-unstyled mb-3">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>相談者の背景を理解する</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>適切な質問で深掘りする</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>具体的なアドバイスを提供</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>フィードバックで成長支援</li>
                    </ul>
                    <div class="d-grid">
                        <a href="{{ route('guide.interview-flow') }}" class="btn btn-secondary">
                            <i class="fas fa-route me-2"></i>
                            面談の流れを確認
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 完了した指導履歴 -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        完了した指導履歴
                    </h5>
                </div>
                <div class="card-body">
                    @if($completedAppointments->count() > 0)
                        <div class="row">
                            @foreach($completedAppointments as $appointment)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-success">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $appointment->appointment_datetime->format('n月j日(D)') }}</h6>
                                            <p class="card-text">
                                                <strong class="text-dark">
                                                    <i class="fas fa-user me-1"></i>
                                                    {{ $appointment->candidate->name }}
                                                </strong>
                                            </p>
                                            @if(!$appointment->feedback)
                                                <a href="{{ route('feedback.create', $appointment) }}" class="btn btn-sm btn-outline-warning">
                                                    フィードバック入力
                                                </a>
                                            @else
                                                <div class="d-flex gap-2">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        完了
                                                    </span>
                                                    <a href="{{ route('feedback.show', $appointment->feedback) }}" class="btn btn-sm btn-outline-info">
                                                        <i class="fas fa-eye me-1"></i>
                                                        フィードバック確認
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <p>指導履歴はまだありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
