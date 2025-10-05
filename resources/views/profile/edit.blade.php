@extends('layouts.app')

@section('title', 'プロフィール編集')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">プロフィール編集</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">お名前 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">メールアドレス</label>
                            <input type="email" class="form-control" id="email" 
                                   value="{{ $user->email }}" readonly>
                            <div class="form-text">メールアドレスは変更できません。</div>
                        </div>

                        <div class="mb-3">
                            <label for="user_type" class="form-label">ユーザータイプ</label>
                            <input type="text" class="form-control" 
                                   value="{{ $user->user_type === 'candidate' ? '受験者' : 'コンサルタント' }}" readonly>
                            <div class="form-text">ユーザータイプは変更できません。</div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">電話番号 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $user->phone) }}" 
                                   placeholder="090-1234-5678" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">自己紹介 <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4" required 
                                      placeholder="自己紹介や目標を入力してください">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($user->user_type === 'consultant')
                            <div class="mb-3">
                                <label for="experience_years" class="form-label">実務経験年数 <span class="text-danger">*</span></label>
                                <select class="form-control @error('experience_years') is-invalid @enderror" 
                                        id="experience_years" name="experience_years" required>
                                    <option value="">選択してください</option>
                                    @for($i = 0; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ old('experience_years', $user->experience_years) == $i ? 'selected' : '' }}>
                                            {{ $i }}年
                                        </option>
                                    @endfor
                                </select>
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="certification_number" class="form-label">資格番号 <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('certification_number') is-invalid @enderror" 
                                       id="certification_number" name="certification_number" 
                                       value="{{ old('certification_number', $user->certification_number) }}" 
                                       placeholder="例: 21CC12345678" required>
                                @error('certification_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="specialties" class="form-label">専門分野</label>
                                <div class="row">
                                    @php
                                        $specialtyOptions = [
                                            'career_change' => '転職支援',
                                            'career_development' => 'キャリア開発', 
                                            'interview_training' => '面接対策',
                                            'resume_writing' => '履歴書作成',
                                            'self_analysis' => '自己分析',
                                            'industry_research' => '業界研究'
                                        ];
                                        $userSpecialties = is_array($user->specialties) ? $user->specialties : [];
                                    @endphp
                                    @foreach($specialtyOptions as $key => $label)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="specialties[]" value="{{ $key }}" 
                                                       id="specialty_{{ $key }}"
                                                       {{ in_array($key, old('specialties', $userSpecialties)) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="specialty_{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>戻る
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
