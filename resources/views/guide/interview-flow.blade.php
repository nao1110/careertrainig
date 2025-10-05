@extends('layouts.app')

@section('title', '面談の流れとフィードバックについて')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- ヘッダー -->
            <div class="text-center mb-5">
                <h1 class="display-6 fw-bold text-primary mb-3">
                    <i class="fas fa-route me-2"></i>
                    面談の流れとフィードバック
                </h1>
                <p class="lead text-muted">キャリトレ・モーニングでの面談プロセスと評価システムについて</p>
            </div>

            <!-- 面談の流れ -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        面談の流れ
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- ステップ1 -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fw-bold fs-4">1</span>
                                </div>
                                <h5>予約申請</h5>
                                <p class="small text-muted">
                                    練習日（土日朝9時）から希望日時を選択し、相談内容を記入して予約申請
                                </p>
                            </div>
                        </div>

                        <!-- ステップ2 -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center">
                                <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fw-bold fs-4">2</span>
                                </div>
                                <h5>講師承認</h5>
                                <p class="small text-muted">
                                    キャリアコンサルタントが予約を確認し、承認。Google MeetのURLが自動生成
                                </p>
                            </div>
                        </div>

                        <!-- ステップ3 -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center">
                                <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fw-bold fs-4">3</span>
                                </div>
                                <h5>オンライン面談</h5>
                                <p class="small text-muted">
                                    Google Meetで45分間のキャリアコンサルティング面談を実施
                                </p>
                            </div>
                        </div>

                        <!-- ステップ4 -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="text-center">
                                <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                    <span class="fw-bold fs-4">4</span>
                                </div>
                                <h5>フィードバック</h5>
                                <p class="small text-muted">
                                    講師から詳細な評価とアドバイスをフィードバック形式で受け取り
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- フィードバックシステム -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-star me-2"></i>
                        フィードバックシステム
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <h5><i class="fas fa-chart-line text-warning me-2"></i>評価項目</h5>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-star text-warning me-2"></i>
                                    <strong>総合評価</strong>：5段階評価（1-5）
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-thumbs-up text-success me-2"></i>
                                    <strong>良かった点・強み</strong>：具体的な長所
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-arrow-up text-primary me-2"></i>
                                    <strong>改善点・課題</strong>：成長のポイント
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-lightbulb text-info me-2"></i>
                                    <strong>具体的なアドバイス</strong>：次回に向けた提案
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <h5><i class="fas fa-eye text-primary me-2"></i>フィードバック確認方法</h5>
                            <div class="alert alert-info">
                                <p class="mb-2"><strong>受験者の方へ：</strong></p>
                                <ul class="mb-0">
                                    <li>ダッシュボードの「最近の練習履歴」から確認</li>
                                    <li>面談後24時間以内にフィードバックが届きます</li>
                                    <li>評価は匿名性を保ちながら公正に行われます</li>
                                </ul>
                            </div>
                            <div class="alert alert-warning">
                                <p class="mb-2"><strong>講師の方へ：</strong></p>
                                <ul class="mb-0">
                                    <li>面談完了後、「フィードバック作成」ボタンからアクセス</li>
                                    <li>建設的で具体的なアドバイスをお願いします</li>
                                    <li>受験者の成長を支援する内容を心がけてください</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Google Meet使用方法 -->
            <div class="card border-0 shadow-sm mb-5">
                <div class="card-header bg-info text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-video me-2"></i>
                        Google Meet利用方法
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <h5>事前準備</h5>
                            <ul>
                                <li>安定したインターネット環境の確保</li>
                                <li>カメラ・マイクの動作確認</li>
                                <li>静かな環境での参加</li>
                                <li>面談資料があれば事前準備</li>
                            </ul>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <h5>参加方法</h5>
                            <ol>
                                <li>ダッシュボードの「Meet」ボタンをクリック</li>
                                <li>Googleアカウントでのログインが必要な場合があります</li>
                                <li>開始時刻の5分前には待機してください</li>
                                <li>面談時間は45分間です</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- よくある質問 -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        よくある質問
                    </h3>
                </div>
                <div class="card-body">
                    <div class="accordion" id="faqAccordion">
                        <!-- FAQ 1 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                    面談をキャンセルしたい場合はどうすればよいですか？
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    承認待ちの状態であれば、ダッシュボードからキャンセルが可能です。承認後のキャンセルは講師に迷惑をかけるため、やむを得ない場合のみお願いします。
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                    面談中に技術的な問題が発生した場合は？
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Google Meetの接続に問題がある場合は、ページを再読み込みするか、別のブラウザでお試しください。それでも解決しない場合は、講師とメールで連絡を取り合ってください。
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                    フィードバックはいつ確認できますか？
                                </button>
                            </h2>
                            <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    面談終了後、講師によって面談が「完了」状態になった後、フィードバックが作成されます。通常24時間以内にダッシュボードで確認できるようになります。
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 戻るボタン -->
            <div class="text-center mt-4">
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>
                    ダッシュボードに戻る
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
