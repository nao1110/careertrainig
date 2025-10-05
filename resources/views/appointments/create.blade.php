@extends('layouts.app')

@section('title', '新規予約作成 - キャリトレ・モーニング')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-plus me-2"></i>
                        新規予約作成
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-primary">
                        <i class="fas fa-user-tie me-2"></i>
                        <strong>キャリアコンサルタント面接練習</strong><br>
                        練習日朝9時開始の45分間セッションです
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        
                        <!-- 日時選択 -->
                        <div class="mb-4">
                            @if($selectedDatetime)
                                <div class="alert alert-success">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    <strong>{{ $selectedDatetime->format('Y年n月j日(D) 9:00-9:45') }}</strong>
                                </div>
                                <input type="hidden" name="appointment_datetime" value="{{ $selectedDatetime->format('Y-m-d H:i:s') }}">
                            @else
                                <div class="mb-3">
                                    <label for="appointment_datetime" class="form-label"><strong>練習日を選択してください</strong></label>
                                    <select class="form-control form-control-lg @error('appointment_datetime') is-invalid @enderror" 
                                            id="appointment_datetime" 
                                            name="appointment_datetime" 
                                            required>
                                        <option value="">-- 練習日を選んでください --</option>
                                        @forelse($weekendOptions as $option)
                                            <option value="{{ $option->format('Y-m-d H:i:s') }}" 
                                                    {{ old('appointment_datetime') == $option->format('Y-m-d H:i:s') ? 'selected' : '' }}>
                                                {{ $option->format('n月j日(D) 9:00-9:45') }}
                                            </option>
                                        @empty
                                            <option value="" disabled>利用可能な日時がありません</option>
                                        @endforelse
                                    </select>
                                    @error('appointment_datetime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <!-- 隠しフィールドで面接練習を固定 -->
                        <input type="hidden" name="consultation_topic" value="キャリアコンサルタント面接練習">
                        <input type="hidden" name="consultation_type" value="面接練習">
                        <input type="hidden" name="special_requests" value="">

                        <!-- 練習の流れ -->
                        <div class="mb-4">
                            <div class="card border-info">
                                <div class="card-body">
                                    <h6 class="text-info mb-3">
                                        <i class="fas fa-route me-2"></i>
                                        練習の流れ
                                    </h6>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <i class="fas fa-calendar-plus fa-2x text-primary mb-2"></i>
                                            <small>1. 予約</small>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <small>2. 承認</small>
                                        </div>
                                        <div class="col-4">
                                            <i class="fas fa-video fa-2x text-warning mb-2"></i>
                                            <small>3. 面接練習</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus me-2"></i>
                                面接練習を予約する
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                ダッシュボードに戻る
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
