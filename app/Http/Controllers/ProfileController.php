<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // ミドルウェアはルートで適用済み（web.phpで定義）
    
    /**
     * プロフィール設定画面を表示
     */
    public function setup()
    {
        $user = Auth::user();
        return view('profile.setup-simple', compact('user'));
    }

    /**
     * プロフィール設定を保存
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_bio' => 'nullable|string|max:1000',
        ];

        // コンサルタントの場合は追加のバリデーション
        if ($user->isConsultant()) {
            $rules['experience_years'] = 'nullable|integer|min:0|max:50';
            $rules['certification_number'] = 'nullable|string|max:50';
            $rules['qualification_number'] = 'nullable|string|max:50';
            $rules['qualification_date'] = 'nullable|date';
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return redirect('/dashboard')->with('success', 'プロフィールが設定されました！');
    }

    /**
     * プロフィール編集画面を表示
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * プロフィール更新
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_bio' => 'nullable|string|max:1000',
        ];

        // コンサルタントの場合は追加のバリデーション
        if ($user->isConsultant()) {
            $rules['experience_years'] = 'nullable|integer|min:0|max:50';
            $rules['certification_number'] = 'nullable|string|max:50';
            $rules['qualification_number'] = 'nullable|string|max:50';
            $rules['qualification_date'] = 'nullable|date';
        }

        $validated = $request->validate($rules);

        $user->update($validated);

        return redirect('/profile/edit')->with('success', 'プロフィールが更新されました！');
    }
}
