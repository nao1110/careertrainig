@extends('layouts.app')

@section('title', 'フィードバック作成')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">フィードバック作成</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">アポイントメント情報</h6>
                            <p><strong>受験者:</strong> {{ $appointment->candidate->name }}</p>
                            <p><strong>日時:</strong> {{ $appointment->appointment_datetime ? $appointment->appointment_datetime->format('Y年m月d日 H:i') : '未設定' }}</p>
                            <p><strong>選択ペルソナ:</strong> {{ $appointment->persona ? $appointment->persona->name : '未選択' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('feedback.store', $appointment) }}">
                        @csrf

                        <!-- 総合評price -->
                        <div class="mb-4">
                            <label for="overall_rating" class="form-label">総合評価 <span class="text-danger">*</span></label>
                            <div class="rating-input">
                                @for($i = 1; $i <= 5; $i++)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="overall_rating" 
                                               id="rating{{ $i }}" value="{{ $i }}" 
                                               {{ old('overall_rating') == $i ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rating{{ $i }}">
                                            {{ $i }}
                                            @for($j = 1; $j <= $i; $j++)
                                                <i class="fas fa-star text-warning"></i>
                                            @endfor
                                        </label>
                                    </div>
                                @endfor
                            </div>
                            @error('overall_rating')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- 詳細評価セクション -->
                        <div class="mb-4">
                            <h6 class="mb-3">詳細評価項目</h6>
                            
                            <!-- 傾聴力 -->
                            <div class="mb-3">
                                <label class="form-label">傾聴力</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="listening_skills" 
                                                   id="listening{{ $i }}" value="{{ $i }}" 
                                                   {{ old('listening_skills') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="listening{{ $i }}">
                                                {{ $i }}
                                                @for($j = 1; $j <= $i; $j++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- 質問力 -->
                            <div class="mb-3">
                                <label class="form-label">質問力</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="questioning_skills" 
                                                   id="questioning{{ $i }}" value="{{ $i }}" 
                                                   {{ old('questioning_skills') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="questioning{{ $i }}">
                                                {{ $i }}
                                                @for($j = 1; $j <= $i; $j++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- 共感力 -->
                            <div class="mb-3">
                                <label class="form-label">共感力</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="empathy_skills" 
                                                   id="empathy{{ $i }}" value="{{ $i }}" 
                                                   {{ old('empathy_skills') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="empathy{{ $i }}">
                                                {{ $i }}
                                                @for($j = 1; $j <= $i; $j++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- 目標設定力 -->
                            <div class="mb-3">
                                <label class="form-label">目標設定力</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="goal_setting_skills" 
                                                   id="goal_setting{{ $i }}" value="{{ $i }}" 
                                                   {{ old('goal_setting_skills') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="goal_setting{{ $i }}">
                                                {{ $i }}
                                                @for($j = 1; $j <= $i; $j++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- 課題解決力 -->
                            <div class="mb-3">
                                <label class="form-label">課題解決力</label>
                                <div class="rating-input">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="solution_skills" 
                                                   id="solution{{ $i }}" value="{{ $i }}" 
                                                   {{ old('solution_skills') == $i ? 'checked' : '' }}>
                                            <label class="form-check-label" for="solution{{ $i }}">
                                                {{ $i }}
                                                @for($j = 1; $j <= $i; $j++)
                                                    <i class="fas fa-star text-warning"></i>
                                                @endfor
                                            </label>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <!-- フリー記述欄 -->
                        <div class="mb-4">
                            <h6 class="mb-3">フィードバック詳細</h6>
                            
                            <!-- 良かった点 -->
                            <div class="mb-3">
                                <label for="strengths" class="form-label">良かった点</label>
                                <textarea class="form-control" id="strengths" name="strengths" rows="3" 
                                          placeholder="良かった点を記入してください">{{ old('strengths') }}</textarea>
                                @error('strengths')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 改善点 -->
                            <div class="mb-3">
                                <label for="improvements" class="form-label">改善点</label>
                                <textarea class="form-control" id="improvements" name="improvements" rows="3" 
                                          placeholder="改善すべき点を記入してください">{{ old('improvements') }}</textarea>
                                @error('improvements')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 具体的なアドバイス -->
                            <div class="mb-3">
                                <label for="specific_advice" class="form-label">具体的なアドバイス</label>
                                <textarea class="form-control" id="specific_advice" name="specific_advice" rows="3" 
                                          placeholder="具体的なアドバイスを記入してください">{{ old('specific_advice') }}</textarea>
                                @error('specific_advice')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- 試験対策アドバイス -->
                            <div class="mb-3">
                                <label for="exam_tips" class="form-label">試験対策アドバイス</label>
                                <textarea class="form-control" id="exam_tips" name="exam_tips" rows="3" 
                                          placeholder="試験対策に関するアドバイスを記入してください">{{ old('exam_tips') }}</textarea>
                                @error('exam_tips')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- コンサルタント追加コメント -->
                            <div class="mb-3">
                                <label for="consultant_comments" class="form-label">その他コメント</label>
                                <textarea class="form-control" id="consultant_comments" name="consultant_comments" rows="3" 
                                          placeholder="その他コメントがあれば記入してください">{{ old('consultant_comments') }}</textarea>
                                @error('consultant_comments')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>戻る
                                </a>
                            </div>
                            <div class="col-md-6 text-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>フィードバックを保存
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input .form-check {
    margin-bottom: 10px;
}
.rating-input .form-check-label {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}
</style>
@endsection
