@extends('layouts.app')

@section('title', 'キャリトレ - キャリアコンサルタント実技練習')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <!-- メインタイトル -->
            <h1 class="display-5 fw-bold text-primary mb-4">
                <i class="fas fa-graduation-cap text-warning me-2"></i>
                キャリトレ
            </h1>
            
            <p class="lead mb-4">
                キャリアコンサルタント実技試験1ヶ月前の土日限定<br>
                午前9:00からの面接練習サービス<br>
                <span class="text-primary fw-bold">「実践経験」</span>で効率的に練習しましょう
            </p>

            @guest
                <!-- ロール選択 -->
                <div class="mb-5">
                    <h3 class="mb-4">どちらで利用しますか？</h3>
                    <div class="row g-3 justify-content-center">
                        <!-- 実技練習（受験者） -->
                        <div class="col-md-5">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-user-graduate text-primary mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="card-title">実技（面談練習）</h5>
                                    <p class="text-muted small mb-3">
                                        キャリアコンサルタント試験の実技練習をしたい方
                                    </p>
                                    <!-- Google認証ボタン -->
                                    <a href="{{ route('google.auth', ['type' => 'candidate']) }}" class="btn btn-primary btn-lg w-100 mb-2">
                                        <i class="fab fa-google me-2"></i>
                                        Googleでログイン
                                    </a>
                                    <!-- デモボタン -->
                                    <form action="{{ route('demo.login') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="type" value="candidate">
                                        <button type="submit" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-play me-2"></i>
                                            デモで体験
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- キャリアコンサルタント -->
                        <div class="col-md-5">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-user-tie text-success mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="card-title">キャリアコンサルタント</h5>
                                    <p class="text-muted small mb-3">
                                        面談練習をサポートしてくださる講師の方
                                    </p>
                                    <!-- Google認証ボタン -->
                                    <a href="{{ route('google.auth', ['type' => 'consultant']) }}" class="btn btn-success btn-lg w-100 mb-2">
                                        <i class="fab fa-google me-2"></i>
                                        Googleでログイン
                                    </a>
                                    <!-- デモボタン -->
                                    <form action="{{ route('demo.login') }}" method="POST" class="mt-2">
                                        @csrf
                                        <input type="hidden" name="type" value="consultant">
                                        <button type="submit" class="btn btn-outline-success w-100">
                                            <i class="fas fa-play me-2"></i>
                                            デモで体験
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Google認証またはデモアカウントでご利用いただけます。<br>
                        <small>デモアカウントは開発・テスト用です。本番利用にはGoogle認証をお使いください。</small>
                    </div>
                </div>
            @else
                <!-- ダッシュボードボタン -->
                <div class="mb-5">
                    <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg px-4 py-3">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        ダッシュボードへ
                    </a>
                </div>
            @endguest

            <!-- 特徴説明 -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-clock text-primary mb-3" style="font-size: 2.5rem;"></i>
                            <h5>練習日限定</h5>
                            <p class="text-muted small">朝9時〜の集中練習</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users text-success mb-3" style="font-size: 2.5rem;"></i>
                            <h5>マッチング</h5>
                            <p class="text-muted small">経験豊富なコンサルタント</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-star text-warning mb-3" style="font-size: 2.5rem;"></i>
                            <h5>フィードバック</h5>
                            <p class="text-muted small">詳細な評価とアドバイス</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 利用フロー -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <h3 class="mb-4">利用の流れ</h3>
                    <div class="row g-3">
                        <div class="col-6 col-md-3 text-center">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                <span class="fw-bold">1</span>
                            </div>
                            <h6>登録</h6>
                            <small class="text-muted">Google認証</small>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                <span class="fw-bold">2</span>
                            </div>
                            <h6>設定</h6>
                            <small class="text-muted">プロフィール</small>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                <span class="fw-bold">3</span>
                            </div>
                            <h6>予約</h6>
                            <small class="text-muted">日時選択</small>
                        </div>
                        <div class="col-6 col-md-3 text-center">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                <span class="fw-bold">4</span>
                            </div>
                            <h6>練習</h6>
                            <small class="text-muted">オンライン実技</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
