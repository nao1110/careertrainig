# キャリトレ・モーニング (Career Training Morning)

キャリアコンサルタント国家資格受験者向けの面接練習システム

## 🎯 概要

**キャリトレ・モーニング**は、キャリアコンサルタント国家資格の面接試験対策を効率化するWebアプリケーションです。受験者とキャリアコンサルタントをマッチングし、Google Meetを使った面接練習セッションを提供します。

## ✨ 主要機能

### 🔐 認証システム
- **Google OAuth認証**: 簡単ログイン・登録
- **ユーザータイプ**: 受験者・キャリアコンサルタント

### 📅 予約システム
- **シンプル予約**: 日時選択のみの簡単予約
- **自動承認**: コンサルタントによる予約承認
- **Google Meet自動生成**: 予約承認時に自動でGoogle Meet URLを生成

### 👥 ペルソナ機能
- **実践的練習**: 実際の相談事例に基づいたペルソナ
- **2つの事例**: 山崎玲奈（転職相談）、野口大輔（キャリアチェンジ）
- **直接表示**: ページ遷移なしで素早く確認

### 📝 フィードバックシステム
- **詳細評価**: 傾聴力、質問力、提案力の3段階評価
- **文字フィードバック**: 具体的なアドバイス
- **成長記録**: 継続的な成長をサポート

### 📊 ダッシュボード
- **受験者用**: 予約状況・フィードバック確認
- **コンサルタント用**: 承認待ち・完了済みセッション管理

## 🛠 技術スタック

- **フレームワーク**: Laravel 12.32.5
- **言語**: PHP 8.2.4
- **データベース**: MySQL
- **フロントエンド**: Bootstrap 5, Blade Templates
- **認証**: Google OAuth 2.0
- **API統合**: Google Calendar API, Google Meet

## 📋 システム要件

- PHP 8.2.4以上
- MySQL 5.7以上
- Composer
- Node.js & npm（オプション）

## 🚀 インストール手順

### 1. プロジェクトクローン
```bash
git clone https://github.com/nao1110/careertrainig.git
cd careertrainig
```

### 2. 依存関係インストール
```bash
composer install
```

### 3. 環境設定
```bash
cp .env.example .env
php artisan key:generate
```

### 4. データベース設定
```bash
# .envファイルでデータベース接続情報を設定
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=career_training
DB_USERNAME=root
DB_PASSWORD=
```

### 5. データベースマイグレーション
```bash
php artisan migrate
```

### 6. Google OAuth設定
Google Cloud Consoleで以下を設定：
1. プロジェクト作成
2. Google Calendar API有効化
3. OAuth 2.0認証情報作成
4. `.env`ファイルに設定

```bash
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

### 7. アプリケーション起動
```bash
php artisan serve
```

アプリケーションは `http://127.0.0.1:8000` でアクセス可能です。

## 🔒 セキュリティ

- 機密情報は`.env`で管理
- Google Service Accountキーは`.gitignore`で除外
- CSRF保護有効
- セッション管理

## 👨‍💻 開発者

- **Naoko Sato** - [GitHub](https://github.com/nao1110)

## 📞 サポート

質問やバグ報告は[Issues](https://github.com/nao1110/careertrainig/issues)をご利用ください。

---

**キャリトレ・モーニング** - キャリアコンサルタント国家資格合格への第一歩 🌅
