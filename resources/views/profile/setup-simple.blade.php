@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">プロフィール設定</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>{{ auth()->user()->user_type === 'candidate' ? '受験者' : 'コンサルタント' }}</strong>として登録されました。
                        追加情報を入力してください。
                    </div>

                    <form action="{{ route('setup.profile') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">お名前 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   placeholder="表示される名前を入力してください" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">この名前が他のユーザーに表示されます。</div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">メールアドレス</label>
                            <input type="email" class="form-control" id="email" 
                                   value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">電話番号 <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone') }}" 
                                   placeholder="090-1234-5678" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">自己紹介 <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" name="bio" rows="4" required 
                                      placeholder="自己紹介や目標を入力してください">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(auth()->user()->user_type === 'consultant')
                            <div class="mb-3">
                                <label for="experience_years" class="form-label">実務経験年数 <span class="text-danger">*</span></label>
                                <select class="form-control @error('experience_years') is-invalid @enderror" 
                                        id="experience_years" name="experience_years" required>
                                    <option value="">選択してください</option>
                                    @for($i = 0; $i <= 20; $i++)
                                        <option value="{{ $i }}" {{ old('experience_years') == $i ? 'selected' : '' }}>
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
                                       value="{{ old('certification_number') }}" 
                                       placeholder="例: 21CC12345678" required>
                                @error('certification_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                設定を完了
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
