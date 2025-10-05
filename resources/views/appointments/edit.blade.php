@extends('layouts.app')

@section('title', '予約編集')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">予約編集</h5>
                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>詳細に戻る
                    </a>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        承認待ちの予約のみ編集できます。
                    </div>

                    <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- 現在の情報表示 -->
                        <div class="mb-4">
                            <h6 class="text-muted">現在の予約情報</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p><strong>現在の日時:</strong> {{ $appointment->appointment_datetime->format('Y年m月d日 H:i') }}</p>
                                    <p><strong>時間:</strong> {{ $appointment->duration_minutes }}分</p>
                                    <p><strong>ステータス:</strong> 
                                        <span class="badge bg-warning">{{ $appointment->status === 'pending' ? '承認待ち' : $appointment->status }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 新しい日時選択 -->
                        <div class="mb-4">
                            <label for="appointment_datetime" class="form-label">新しい日時 <span class="text-danger">*</span></label>
                            <select class="form-control @error('appointment_datetime') is-invalid @enderror" 
                                    id="appointment_datetime" name="appointment_datetime" required>
                                <option value="">日時を選択してください</option>
                                @foreach($weekendOptions as $option)
                                    <option value="{{ $option->format('Y-m-d H:i:s') }}" 
                                            {{ old('appointment_datetime', $appointment->appointment_datetime->format('Y-m-d H:i:s')) === $option->format('Y-m-d H:i:s') ? 'selected' : '' }}>
                                        {{ $option->format('Y年m月d日 H:i') }} ({{ $option->isoFormat('dddd') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('appointment_datetime')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                練習日（土日朝9時）のみ選択可能です。
                            </div>
                        </div>

                        <!-- 相談トピック -->
                        <div class="mb-4">
                            <label for="consultation_topic" class="form-label">相談したいトピック</label>
                            <textarea class="form-control @error('consultation_topic') is-invalid @enderror" 
                                      id="consultation_topic" name="consultation_topic" rows="3"
                                      placeholder="どのようなことを相談したいか具体的に記入してください">{{ old('consultation_topic', $appointment->consultation_topic) }}</textarea>
                            @error('consultation_topic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                例：転職活動の進め方、キャリアプランの相談、面接対策など
                            </div>
                        </div>

                        <!-- 特別な要望 -->
                        <div class="mb-4">
                            <label for="special_requests" class="form-label">特別な要望・注意事項</label>
                            <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                      id="special_requests" name="special_requests" rows="3"
                                      placeholder="コンサルタントに特別にお願いしたいことがあれば記入してください">{{ old('special_requests', $appointment->special_requests) }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                例：初回のため緊張しています、特定の業界について詳しく聞きたいなど
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>キャンセル
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
