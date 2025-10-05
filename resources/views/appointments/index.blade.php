@extends('layouts.app')

@section('title', '予約一覧')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">予約一覧</h5>
                    @if(auth()->user()->isCandidate())
                        <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i>新しい予約
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($appointments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>日時</th>
                                        @if(auth()->user()->isCandidate())
                                            <th>コンサルタント</th>
                                        @else
                                            <th>受験者</th>
                                        @endif
                                        <th>ステータス</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $appointment)
                                        <tr>
                                            <td>
                                                {{ $appointment->appointment_datetime->format('Y年m月d日 H:i') }}
                                                <br>
                                                <small class="text-muted">{{ $appointment->duration_minutes }}分</small>
                                            </td>
                                            <td>
                                                @if(auth()->user()->isCandidate())
                                                    {{ $appointment->consultant ? $appointment->consultant->name : '未定' }}
                                                @else
                                                    {{ $appointment->candidate->name }}
                                                @endif
                                            </td>
                                            <td>
                                                @switch($appointment->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">承認待ち</span>
                                                        @break
                                                    @case('matched')
                                                        <span class="badge bg-success">マッチング済み</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-primary">完了</span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-secondary">キャンセル</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('appointments.show', $appointment) }}" 
                                                       class="btn btn-outline-primary btn-sm">詳細</a>
                                                    
                                                    @if(auth()->user()->isCandidate() && $appointment->status === 'pending')
                                                        <a href="{{ route('appointments.edit', $appointment) }}" 
                                                           class="btn btn-outline-secondary btn-sm">編集</a>
                                                        <form action="{{ route('appointments.destroy', $appointment) }}" 
                                                              method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                    onclick="return confirm('本当にキャンセルしますか？')">
                                                                キャンセル
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(auth()->user()->isConsultant() && $appointment->status === 'pending')
                                                        <form action="{{ route('appointments.approve', $appointment) }}" 
                                                              method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">
                                                                承認
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(auth()->user()->isConsultant() && $appointment->status === 'matched')
                                                        <form action="{{ route('appointments.complete', $appointment) }}" 
                                                              method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-primary btn-sm">
                                                                完了
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(auth()->user()->isConsultant() && $appointment->status === 'completed' && !$appointment->feedback)
                                                        <a href="{{ route('feedback.create', $appointment) }}" 
                                                           class="btn btn-warning btn-sm">フィードバック</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $appointments->links() }}
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">予約がありません</h5>
                            @if(auth()->user()->isCandidate())
                                <p class="text-muted">新しい予約を作成してみましょう。</p>
                                <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>予約を作成
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
