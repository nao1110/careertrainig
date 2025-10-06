<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new GoogleClient();
        
        // 設定値を取得
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');
        
        // デバッグ情報をログに出力
        \Log::info('Google OAuth Config', [
            'client_id' => $clientId ? 'Set' : 'Not set',
            'client_secret' => $clientSecret ? 'Set' : 'Not set',
            'redirect_uri' => $redirectUri
        ]);
        
        if (!$clientId || !$clientSecret || !$redirectUri) {
            \Log::error('Google OAuth configuration missing', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret ? 'Set' : 'Not set',
                'redirect_uri' => $redirectUri
            ]);
            throw new \Exception('Google OAuth configuration is incomplete');
        }
        
        $this->client->setClientId($clientId);
        $this->client->setClientSecret($clientSecret);
        $this->client->setRedirectUri($redirectUri);
        $this->client->addScope(['email', 'profile']);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
    }

    /**
     * Google認証画面にリダイレクト
     */
    public function redirectToGoogle(Request $request)
    {
        // ユーザータイプをセッションに保存
        if ($request->has('type')) {
            session(['user_type' => $request->get('type')]);
        }
        
        // デバッグ情報をログに出力
        \Log::info('Google OAuth redirect configuration', [
            'client_id' => substr(env('GOOGLE_CLIENT_ID'), 0, 20) . '...',
            'redirect_uri' => env('GOOGLE_REDIRECT_URI'),
            'current_url' => url()->current(),
            'app_url' => env('APP_URL')
        ]);
        
        $authUrl = $this->client->createAuthUrl();
        \Log::info('Generated auth URL', ['auth_url' => $authUrl]);
        
        return redirect($authUrl);
    }

    /**
     * Googleからのコールバック処理
     */
    public function handleGoogleCallback(Request $request)
    {
        \Log::info('Google callback started', ['request' => $request->all()]);
        
        if ($request->has('code')) {
            try {
                // 認証コードからアクセストークンを取得
                $token = $this->client->fetchAccessTokenWithAuthCode($request->get('code'));
                \Log::info('Google token response', ['token' => $token]);
                
                if (isset($token['error'])) {
                    \Log::error('Google token error', ['error' => $token['error']]);
                    return redirect('/login')->with('error', 'Google認証に失敗しました。');
                }

                $this->client->setAccessToken($token);

                // ユーザー情報を取得
                $googleService = new \Google\Service\Oauth2($this->client);
                $googleUser = $googleService->userinfo->get();
                \Log::info('Google user info', [
                    'id' => $googleUser->id,
                    'email' => $googleUser->email,
                    'name' => $googleUser->name,
                    'picture' => $googleUser->picture
                ]);

                // データベースでユーザーを検索または作成
                $user = User::where('google_id', $googleUser->id)
                    ->orWhere('email', $googleUser->email)
                    ->first();

                if (!$user) {
                    // セッションからユーザータイプを取得
                    $userType = session('user_type', 'candidate'); // デフォルトは受験者
                    \Log::info('Creating new user', ['user_type' => $userType]);
                    
                    // 新しいユーザーを作成
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_avatar' => $googleUser->picture,
                        'password' => Hash::make(Str::random(32)), // ランダムパスワード
                        'email_verified_at' => now(),
                        'user_type' => $userType,
                        'is_active' => true,
                    ]);
                    
                    // セッションをクリア
                    session()->forget('user_type');
                } else {
                    \Log::info('Updating existing user', ['user_id' => $user->id]);
                    
                    // セッションからユーザータイプを取得
                    $requestedUserType = session('user_type');
                    
                    // 既存ユーザーの情報を更新
                    $updateData = [
                        'google_id' => $googleUser->id,
                        'google_avatar' => $googleUser->picture,
                    ];
                    
                    // ユーザータイプが指定されている場合は更新
                    if ($requestedUserType && in_array($requestedUserType, ['candidate', 'consultant'])) {
                        $updateData['user_type'] = $requestedUserType;
                        \Log::info('Updating user type', ['from' => $user->user_type, 'to' => $requestedUserType]);
                    }
                    
                    $user->update($updateData);
                    
                    // セッションをクリア
                    session()->forget('user_type');
                }

                // ユーザーをログイン
                Auth::login($user);
                
                \Log::info('User logged in', [
                    'user_id' => $user->id,
                    'user_type' => $user->user_type,
                    'name' => $user->name,
                    'email' => $user->email,
                    'auth_check_after_login' => Auth::check(),
                    'session_id' => session()->getId()
                ]);

                \Log::info('Redirecting to dashboard');
                return redirect()->route('dashboard');

            } catch (\Exception $e) {
                \Log::error('Google OAuth Error: ' . $e->getMessage(), ['exception' => $e]);
                return redirect('/login')->with('error', 'Google認証中にエラーが発生しました。');
            }
        }

        \Log::warning('No code in callback request');
        return redirect('/login')->with('error', '認証がキャンセルされました。');
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    /**
     * デモログイン（開発用）
     */
    public function demoLogin(Request $request)
    {
        $userType = $request->get('type', 'candidate');
        
        // デモユーザーを作成またはログイン
        $email = $userType === 'consultant' ? 'demo-consultant@example.com' : 'demo-candidate@example.com';
        $name = $userType === 'consultant' ? 'デモ講師' : 'デモ受験者';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('demo123'),
                'email_verified_at' => now(),
                'user_type' => $userType,
                'is_active' => true,
            ]);
        }
        
        Auth::login($user);
        
        return redirect()->route('dashboard')->with('success', 'デモアカウントでログインしました。');
    }

    /**
     * プロフィール設定が必要かチェック
     */
    private function needsProfileSetup($user)
    {
        if (!$user->user_type) {
            return true;
        }

        // 受験者の場合の必須項目チェック
        if ($user->isCandidate()) {
            return empty($user->phone) || empty($user->bio);
        }

        // コンサルタントの場合の必須項目チェック
        if ($user->isConsultant()) {
            return empty($user->phone) || 
                   empty($user->bio) || 
                   empty($user->experience_years) || 
                   empty($user->certification_number);
        }

        return false;
    }
}
