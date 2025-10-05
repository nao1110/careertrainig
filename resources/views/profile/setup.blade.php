@extends('layouts.app')

@section('title', 'プロフィール設定 - キャリトレ・モーニング')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-cog me-2"></i>
                        プロフィール設定
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>{{ $user->isCandidate() ? '受験者' : 'コンサルタント' }}</strong>として登録されています。
                        サービスをご利用いただくために、追加情報をご入力ください。
                    </div>

                    <form action="{{ route('setup.profile') }}" method="POST">
                        @csrf
                        
                        <!-- 基本情報 -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">基本情報</h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">お名前 <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       readonly>
                                <div class="form-text">Googleアカウントの名前が使用されます</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">メールアドレス</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       value="{{ $user->email }}" 
                                       readonly>
                                <div class="form-text">Googleアカウントのメールアドレスが使用されます</div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">電話番号 <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}" 
                                       placeholder="例: 090-1234-5678"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">自己紹介 <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" 
                                          name="bio" 
                                          rows="4" 
                                          placeholder="{{ $user->isCandidate() ? '試験に向けた意気込みや目標などをお書きください' : 'コンサルタントとしての経験や得意分野をお書きください' }}"
                                          required>{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        @if($user->isConsultant())
                        <!-- コンサルタント専用情報 -->
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">コンサルタント情報</h5>
                            
                            <div class="mb-3">
                                <label for="experience_years" class="form-label">実務経験年数 <span class="text-danger">*</span></label>
                                <select class="form-control @error('experience_years') is-invalid @enderror" 
                                        id="experience_years" 
                                        name="experience_years" 
                                        required>
                                    <option value="">選択してください</option>
                                    @for($i = 0; $i <= 30; $i++)
                                        <option value="{{ $i }}" {{ old('experience_years', $user->experience_years) == $i ? 'selected' : '' }}>
                                            {{ $i }}年
                                        </option>
                                    @endfor
                                    <option value="31" {{ old('experience_years', $user->experience_years) >= 31 ? 'selected' : '' }}>
                                        30年以上
                                    </option>
                                </select>
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="certification_number" class="form-label">キャリアコンサルタント登録番号 <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('certification_number') is-invalid @enderror" 
                                       id="certification_number" 
                                       name="certification_number" 
                                       value="{{ old('certification_number', $user->certification_number) }}" 
                                       placeholder="例: 12345678"
                                       required>
                                @error('certification_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">専門分野（複数選択可）</label>
                                <div class="row">
                                    @php
                                        $specialties = [
                                            'career_change' => '転職支援',
                                            'career_development' => 'キャリア開発',
                                            'job_hunting' => '就職活動',
                                            'work_life_balance' => 'ワークライフバランス',
                                            'leadership' => 'リーダーシップ',
                                            'skill_development' => 'スキル開発',
                                            'industry_knowledge' => '業界知識',
                                            'entrepreneurship' => '起業・独立',
                                        ];
                                        $userSpecialties = old('specialties', $user->specialties ?? []);
                                    @endphp
                                    @foreach($specialties as $key => $label)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="specialties[]" 
                                                       value="{{ $key }}" 
                                                       id="specialty_{{ $key }}"
                                                       {{ in_array($key, $userSpecialties) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="specialty_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                プロフィールを保存してダッシュボードへ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
