@extends('layouts.admin')

@section('title', 'イベント管理')

@section('breadcrumb')
<x-admin.breadcrumb :items="[
    ['title' => 'ダッシュボード', 'href' => route('admin.dashboard'), 'icon' => 'fas fa-tachometer-alt'],
    ['title' => 'イベント管理', 'icon' => 'fas fa-calendar-alt']
]" />
@endsection

@section('content')
<div class="row">
    <div class="col-lg-3">
        <!-- カレンダーフィルタ -->
        <h6 class="mb-3">
            <i class="fas fa-calendar-alt me-2"></i>日付で絞り込み
        </h6>
        <x-calendar-sidebar 
            :events="$events ?? []"
            :selected-date="request('filter_date')"
            :show-months="2" />
        
        <!-- フィルタサイドバー -->
        <x-admin.card title="その他のフィルタ" icon="fas fa-filter">
            <!-- イベントタイプフィルタ -->
            <div class="filter-section">
                <h6 class="mb-3">イベントタイプ</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="anime" id="filterAnime" 
                           {{ in_array('anime', explode(',', request('types', ''))) || !request('types') ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterAnime">
                        アニメコン
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="machi" id="filterMachi"
                           {{ in_array('machi', explode(',', request('types', ''))) || !request('types') ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterMachi">
                        街コン
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="nazo" id="filterNazo"
                           {{ in_array('nazo', explode(',', request('types', ''))) || !request('types') ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterNazo">
                        謎解き
                    </label>
                </div>
            </div>
            
            <div class="filter-section mt-4">
                <h6 class="mb-3">ステータス</h6>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="published" id="filterPublished"
                           {{ in_array('published', explode(',', request('statuses', 'published'))) ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterPublished">
                        公開中
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="draft" id="filterDraft"
                           {{ in_array('draft', explode(',', request('statuses', ''))) ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterDraft">
                        下書き
                    </label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="cancelled" id="filterCancelled"
                           {{ in_array('cancelled', explode(',', request('statuses', ''))) ? 'checked' : '' }}>
                    <label class="form-check-label" for="filterCancelled">
                        キャンセル
                    </label>
                </div>
            </div>
            
            <div class="mt-4">
                <button class="btn btn-primary btn-sm w-100" onclick="applyFilters()">
                    <i class="fas fa-search me-2"></i>フィルタを適用
                </button>
                <button class="btn btn-outline-secondary btn-sm w-100 mt-2" onclick="resetFilters()">
                    <i class="fas fa-undo me-2"></i>リセット
                </button>
            </div>
        </x-admin.card>
    </div>
    
    <div class="col-lg-9">
        <!-- イベントリスト -->
        <x-admin.card 
            title="イベント一覧" 
            icon="fas fa-calendar-alt"
            :badge="($events->total() ?? 0) . '件'">
            
            <x-slot name="headerActions">
                <a href="{{ route('admin.events.create') ?? '#' }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-2"></i>新規作成
                </a>
            </x-slot>
            
            <!-- 検索バー -->
            <div class="mb-4">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="イベント名で検索..." 
                           id="eventSearch" value="{{ request('search') }}">
                </div>
            </div>
            
            @if(request('filter_date'))
                <div class="alert alert-info mb-3">
                    <i class="fas fa-calendar-check me-2"></i>
                    {{ Carbon\Carbon::parse(request('filter_date'))->format('Y年n月j日') }}のイベントを表示中
                    <a href="{{ route('admin.events.index') }}" class="float-end">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            @endif
            
            <!-- イベントテーブル -->
            @if(isset($events) && $events->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>イベント名</th>
                                <th>日時</th>
                                <th>場所</th>
                                <th>参加者数</th>
                                <th>ステータス</th>
                                <th width="120">アクション</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $event->title }}</div>
                                        <small class="text-muted">{{ $event->eventType->name ?? 'その他' }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $event->event_date->format('Y/m/d') }}</div>
                                        <small class="text-muted">{{ $event->start_time }} - {{ $event->end_time }}</small>
                                    </td>
                                    <td>{{ $event->area->name ?? '未設定' }}</td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            @php
                                                $capacity = $event->capacity ?? 100;
                                                $participants = $event->customers_count ?? 0;
                                                $percentage = $capacity > 0 ? ($participants / $capacity) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" style="width: {{ $percentage }}%">
                                                {{ $participants }}/{{ $capacity }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'published' => 'success',
                                                'draft' => 'secondary',
                                                'cancelled' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'published' => '公開中',
                                                'draft' => '下書き',
                                                'cancelled' => 'キャンセル'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$event->status] ?? 'secondary' }}">
                                            {{ $statusLabels[$event->status] ?? $event->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.events.edit', $event) ?? '#' }}" 
                                               class="btn btn-outline-primary"
                                               title="編集">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.operations.index', $event) ?? '#' }}" 
                                               class="btn btn-outline-info"
                                               title="運営管理">
                                                <i class="fas fa-cogs"></i>
                                            </a>
                                            <button class="btn btn-outline-danger"
                                                    onclick="deleteEvent({{ $event->id }})"
                                                    title="削除">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- ページネーション -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            @else
                <x-admin.empty-state 
                    title="イベントがありません"
                    description="新しいイベントを作成してください"
                    icon="fas fa-calendar-times"
                    action-text="イベントを作成"
                    :action-href="route('admin.events.create') ?? '#'" />
            @endif
        </x-admin.card>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // フィルタを適用
    function applyFilters() {
        const url = new URL(window.location);
        
        // 既存の日付フィルタは保持
        const currentDate = url.searchParams.get('filter_date');
        
        // イベントタイプ
        const types = [];
        if (document.getElementById('filterAnime').checked) types.push('anime');
        if (document.getElementById('filterMachi').checked) types.push('machi');
        if (document.getElementById('filterNazo').checked) types.push('nazo');
        if (types.length > 0) {
            url.searchParams.set('types', types.join(','));
        } else {
            url.searchParams.delete('types');
        }
        
        // ステータス
        const statuses = [];
        if (document.getElementById('filterPublished').checked) statuses.push('published');
        if (document.getElementById('filterDraft').checked) statuses.push('draft');
        if (document.getElementById('filterCancelled').checked) statuses.push('cancelled');
        if (statuses.length > 0) {
            url.searchParams.set('statuses', statuses.join(','));
        } else {
            url.searchParams.delete('statuses');
        }
        
        window.location.href = url.toString();
    }
    
    // フィルタをリセット
    function resetFilters() {
        const url = new URL(window.location);
        url.searchParams.delete('filter_date');
        url.searchParams.delete('types');
        url.searchParams.delete('statuses');
        url.searchParams.delete('search');
        window.location.href = url.toString();
    }
    
    // イベント削除
    function deleteEvent(id) {
        if (confirm('このイベントを削除してもよろしいですか？')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/events/${id}`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    
    // 検索機能
    document.getElementById('eventSearch')?.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const url = new URL(window.location);
            if (this.value) {
                url.searchParams.set('search', this.value);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }
    });
</script>
@endpush