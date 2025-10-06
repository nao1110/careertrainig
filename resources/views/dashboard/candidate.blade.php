@extends('layouts.app')

@section('title', '受験者ダッシュボード - キャリトレ・モーニング')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>
                    <i class="fas fa-user-graduate text-primary me-2"></i>
                    受験者ダッシュボード
                </h1>
                <div>
                    <span class="badge bg-primary">受験者</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Meet使用方法の説明 -->
    @if($upcomingAppointments->where('google_meet_url')->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-info">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x"></i>
                    </div>
                    <div>
                        <h6 class="alert-heading mb-2">
                            <i class="fas fa-video me-2"></i>Google Meet面談について
                        </h6>
                        <p class="mb-1">
                            <strong>面談開始方法：</strong>予約確定後、「<i class="fas fa-video mx-1"></i>面談開始」ボタンをクリックしてGoogle Meetに参加してください。
                        </p>
                        <p class="mb-0">
                            <small class="text-muted">※ 面談時間の5分前からアクセス可能です。カメラとマイクの準備をお願いします。</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif



    <div class="row">
        <!-- 新規予約作成 -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        面接練習予約
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">キャリアコンサルタント面接の練習セッション</p>
                    <div class="text-center mb-3">
                        <i class="fas fa-clock fa-2x text-primary mb-2"></i>
                        <p class="mb-1"><strong>45分間</strong></p>
                        <small class="text-muted">練習日朝9時開始</small>
                    </div>
                    <div class="d-grid gap-2">
                        @foreach($nextWeekends->take(2) as $weekend)
                            <a href="{{ route('appointments.create', ['datetime' => $weekend->format('Y-m-d H:i:s')]) }}" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-calendar-plus me-2"></i>
                                {{ $weekend->format('n月j日(D)') }}
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('appointments.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-calendar-alt me-2"></i>
                            日程を選んで予約
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- フィードバック一覧 -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        フィードバック
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">面談後のコンサルタントからの評価とアドバイス</p>
                    <div class="text-center mb-3">
                        <i class="fas fa-star fa-2x text-info mb-2"></i>
                        <p class="mb-1"><strong>評価・改善点</strong></p>
                        <small class="text-muted">詳細なレポート</small>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('feedback.index') }}" class="btn btn-info w-100">
                            <i class="fas fa-list me-2"></i>
                            フィードバック一覧
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ペルソナ一覧 -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        練習用ペルソナ
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">面談練習用のクライアント設定</p>
                    <div class="text-center mb-3">
                        <i class="fas fa-user-friends fa-2x text-warning mb-2"></i>
                        <p class="mb-1"><strong>様々なケース</strong></p>
                        <small class="text-muted">難易度別</small>
                    </div>
                    <div class="d-grid">
                        <a href="{{ route('personas.index') }}" class="btn btn-warning text-dark w-100">
                            <i class="fas fa-search me-2"></i>
                            ペルソナ一覧
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 今後の予約 -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        今後の予約
                    </h5>
                </div>
                <div class="card-body">
                    @if($upcomingAppointments->count() > 0)
                        @foreach($upcomingAppointments as $appointment)
                            <div class="border-start border-3 border-{{ in_array($appointment->status, ['approved', 'matched']) ? 'success' : ($appointment->status == 'pending' ? 'warning' : 'secondary') }} ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $appointment->appointment_datetime->format('n月j日(D) G:i') }}</h6>
                                        @if($appointment->consultant)
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $appointment->consultant->name }}
                                            </small>
                                        @elseif($appointment->status == 'pending')
                                            <small class="text-warning">
                                                <i class="fas fa-hourglass-half me-1"></i>
                                                コンサルタント承認待ち
                                            </small>
                                        @else
                                            <small class="text-muted">
                                                <i class="fas fa-search me-1"></i>
                                                コンサルタント検索中
                                            </small>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        @if($appointment->status == 'pending')
                                            <span class="badge bg-warning d-block mb-1">
                                                <i class="fas fa-clock me-1"></i>承認待ち
                                            </span>
                                        @elseif(in_array($appointment->status, ['approved', 'matched']))
                                            <span class="badge bg-success d-block mb-1">
                                                <i class="fas fa-check me-1"></i>承認済み
                                            </span>
                                            @if($appointment->google_meet_url)
                                                <small class="text-success d-block mb-1">
                                                    <i class="fas fa-video me-1"></i>Meet準備完了
                                                </small>
                                            @endif
                                        @endif
                                        
                                        <div class="d-flex gap-1 justify-content-end">
                                            @if($appointment->google_meet_url && in_array($appointment->status, ['approved', 'matched']))
                                                <a href="{{ $appointment->google_meet_url }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-success"
                                                   title="面談開始（Google Meet）">
                                                    <i class="fas fa-video me-1"></i>面談開始
                                                </a>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="fas fa-clock me-1"></i>承認待ち
                                                </span>
                                            @endif
                                        </div>
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

        <!-- 練習用ペルソナ -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>
                        練習用ペルソナ事例
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">面接練習で使用する相談者のサンプル事例です</p>
                    
                    <div class="row">
                        <!-- ペルソナ1：山崎玲奈 -->
                        <div class="col-md-6 mb-3">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-graduation-cap me-1"></i>
                                        <a href="{{ route('personas.show', 1) }}" class="text-white text-decoration-none">
                                            山崎 玲奈（22歳）
                                        </a>
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <small class="text-muted d-block"><strong>背景:</strong> 四年制大学在学中（国際教養学部4年生）</small>
                                        <small class="text-muted d-block"><strong>家族:</strong> 父（64歳）、母（52歳）、一人暮らし</small>
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-primary">相談内容:</strong>
                                        <p class="small mb-0">総合商社に勤める従姉の影響で総合商社への就職を希望するようになったが、現在3社から内定を得ており、どの会社を選ぶべきか悩んでいる。就職活動をやり直すべきか相談したい。</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ペルソナ2：野口大輔 -->
                        <div class="col-md-6 mb-3">
                            <div class="card border-success h-100">
                                <div class="card-header bg-success text-white py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-briefcase me-1"></i>
                                        <a href="{{ route('personas.show', 2) }}" class="text-white text-decoration-none">
                                            野口 大輔（35歳）
                                        </a>
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <small class="text-muted d-block"><strong>背景:</strong> 出版社勤務13年目、独身一人暮らし</small>
                                        <small class="text-muted d-block"><strong>職歴:</strong> 文学部国文学科卒、編集職から営業職へ異動</small>
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-success">相談内容:</strong>
                                        <p class="small mb-0">編集職から営業職への異動でモチベーションが低下。さらに交際相手の転勤の可能性もあり、今後の働き方について悩んでいる。どうすべきか相談したい。</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>
                            面接練習では、これらの相談者になりきって練習を行います
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- 最近の練習履歴 -->
        <div class="col-lg-12 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        最近の練習履歴
                    </h5>
                </div>
                <div class="card-body">
                    @if($pastAppointments->count() > 0)
                        @foreach($pastAppointments as $appointment)
                            <div class="border-start border-3 border-{{ $appointment->feedback ? 'success' : 'secondary' }} ps-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">{{ $appointment->appointment_datetime->format('n月j日(D)') }}</h6>
                                        @if($appointment->consultant)
                                            <small class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $appointment->consultant->name }}
                                            </small>
                                        @endif
                                        @if($appointment->feedback)
                                            <div class="mt-1">
                                                <span class="badge bg-success mb-1">
                                                    <i class="fas fa-check me-1"></i>レポート完成
                                                </span>
                                                <br><small class="text-warning">
                                                    <i class="fas fa-star me-1"></i>
                                                    総合評価: {{ $appointment->feedback->overall_rating }}/5
                                                </small>
                                            </div>
                                        @else
                                            <div class="mt-1">
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock me-1"></i>レポート作成中
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        @if($appointment->feedback)
                                            <a href="{{ route('feedback.show', $appointment->feedback) }}" class="btn btn-sm btn-outline-success">
                                                <i class="fas fa-file-alt me-1"></i>
                                                レポート確認
                                            </a>
                                        @else
                                            <small class="text-muted">レポート待ち</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                            <p>練習履歴はまだありません</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- フィードバック詳細モーダル -->
@foreach($pastAppointments->whereNotNull('feedback') as $appointment)
<div class="modal fade" id="feedback{{ $appointment->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    フィードバック詳細 - {{ $appointment->appointment_datetime->format('n月j日') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($appointment->feedback)
                    <div class="mb-3">
                        <h6>総合評価</h6>
                        <div class="d-flex align-items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $appointment->feedback->overall_rating ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-2">{{ $appointment->feedback->overall_rating }}/5</span>
                        </div>
                    </div>
                    
                    @if($appointment->feedback->strengths)
                        <div class="mb-3">
                            <h6>良かった点</h6>
                            <p class="text-muted">{{ $appointment->feedback->strengths }}</p>
                        </div>
                    @endif
                    
                    @if($appointment->feedback->improvements)
                        <div class="mb-3">
                            <h6>改善点</h6>
                            <p class="text-muted">{{ $appointment->feedback->improvements }}</p>
                        </div>
                    @endif
                    
                    @if($appointment->feedback->specific_advice)
                        <div class="mb-3">
                            <h6>具体的なアドバイス</h6>
                            <p class="text-muted">{{ $appointment->feedback->specific_advice }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
