# Google Calendar API設定方法

## 概要
このシステムでは、Google Calendar APIを使用してGoogle Meetリンクを自動生成します。

## 設定手順

### 1. Google Cloud Consoleでの設定

1. [Google Cloud Console](https://console.cloud.google.com/) にアクセス
2. 新しいプロジェクトを作成または既存プロジェクトを選択
3. **API とサービス** → **ライブラリ** から以下のAPIを有効化：
   - Google Calendar API
   - Google Meet API（利用可能な場合）

### 2. サービスアカウントの作成

1. **API とサービス** → **認証情報** へ移動
2. **認証情報を作成** → **サービス アカウント** を選択
3. サービスアカウント名を入力（例：career-training-calendar）
4. 役割は「Project」→「編集者」を設定
5. **キーを作成** → **JSON** を選択してダウンロード

### 3. アプリケーション設定

1. ダウンロードしたJSONファイルを `storage/app/google-service-account.json` に配置
2. `.env` ファイルに以下を追加：

```bash
# Google Calendar API設定
GOOGLE_SERVICE_ACCOUNT_PATH=storage/app/google-service-account.json
GOOGLE_CALENDAR_ID=primary
```

### 4. カレンダーの共有設定

1. Googleカレンダーを開く
2. 左側の「マイカレンダー」から使用するカレンダーを選択
3. 設定画面で「特定のユーザーとの共有」にサービスアカウントのメールアドレスを追加
4. 権限は「予定の変更権限を付与」を選択

## 動作確認

設定完了後、以下の手順で動作確認：

1. コンサルタントとしてログイン
2. 面談申し込みを承認
3. Google Meet URLが自動生成されることを確認
4. Googleカレンダーにイベントが作成されていることを確認

## トラブルシューティング

### フォールバック機能
Google Calendar APIが利用できない場合、システムは自動的にフォールバック用のMeet URLを生成します。

### ログの確認
`storage/logs/laravel.log` でGoogle Calendar API関連のログを確認できます。

### よくある問題

1. **認証エラー**
   - サービスアカウントキーファイルのパスが正しいか確認
   - サービスアカウントがカレンダーに適切な権限を持っているか確認

2. **API無効化エラー**
   - Google Cloud ConsoleでCalendar APIが有効になっているか確認

3. **Meet URLが生成されない**
   - サービスアカウントがGoogle Workspace（旧G Suite）ドメインに属している必要がある場合があります
   - 個人のGoogleアカウントでは一部機能が制限される場合があります

## 本番環境での注意事項

- サービスアカウントキーファイルは機密情報です。適切にセキュリティを管理してください
- 定期的にキーのローテーションを行うことを推奨します
- API使用量制限に注意し、必要に応じてクォータ拡張を申請してください
