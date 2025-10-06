<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リダイレクト中...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card">
                    <div class="card-body">
                        <div class="spinner-border text-primary mb-3" role="status">
                            <span class="visually-hidden">読み込み中...</span>
                        </div>
                        <h4>{{ $message }}</h4>
                        <p class="text-muted">自動的にリダイレクトされない場合は、<a href="{{ $redirectUrl }}" id="manual-link">こちらをクリック</a>してください。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // セッション情報をコンソールに出力（デバッグ用）
        console.log('Auth check:', {{ auth()->check() ? 'true' : 'false' }});
        @if(auth()->check())
        console.log('User:', {{ json_encode(auth()->user()->name) }});
        console.log('User Type:', {{ json_encode(auth()->user()->user_type) }});
        @endif
        console.log('Redirect URL:', {{ json_encode($redirectUrl) }});
        
        // 2秒後にリダイレクト
        setTimeout(function() {
            console.log('Redirecting to dashboard...');
            window.location.href = {{ json_encode($redirectUrl) }};
        }, 2000);
        
        // 手動リンクのクリックイベント
        document.getElementById('manual-link').addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Manual redirect clicked');
            window.location.href = {{ json_encode($redirectUrl) }};
        });
    </script>
</body>
</html>