@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('breadcrumb')
<x-admin.breadcrumb :items="[
    ['title' => 'ダッシュボード', 'icon' => 'fas fa-tachometer-alt']
]" />
@endsection

@section('content')
<div class="row">
    <!-- 統計カード -->
    <x-admin.stats-card 
        title="総イベント数"
        :value="$stats['total_events'] ?? 0"
        icon="fas fa-calendar-alt"
        color="primary" />
    
    <x-admin.stats-card 
        title="総参加者数"
        :value="$stats['total_participants'] ?? 0"
        icon="fas fa-users"
        color="success" />
    
    <x-admin.stats-card 
        title="総売上"
        :value="$stats['total_revenue'] ?? 0"
        icon="fas fa-yen-sign"
        color="warning"
        formatter="currency" />
    
    <x-admin.stats-card 
        title="アクティブスタッフ"
        :value="$stats['active_staff'] ?? 0"
        icon="fas fa-user-tie"
        color="purple" />
</div>

<div class="row">
    <!-- 今日の予定 -->
    <div class="col-lg-6 mb-4">
        <x-admin.card 
            title="今日の予定" 
            icon="fas fa-calendar-day"
            :badge="($todayEvents->count() ?? 0) . '件'"
            badge-color="primary">
            
            @if(isset($todayEvents) && $todayEvents->count() > 0)
                @foreach($todayEvents as $event)
                    <x-admin.event-item :event="$event" />
                @endforeach
            @else
                <x-admin.empty-state 
                    title="今日の予定はありません"
                    icon="fas fa-calendar-times" />
            @endif
        </x-admin.card>
    </div>
    
    <!-- 最近のアクティビティ -->
    <div class="col-lg-6 mb-4">
        <x-admin.card 
            title="最近のアクティビティ" 
            icon="fas fa-bell"
            badge-color="info">
            
            <x-admin.timeline :items="$recentActivities" />
        </x-admin.card>
    </div>
</div>

<div class="row">
    <!-- 売上推移グラフ -->
    <div class="col-lg-8 mb-4">
        <x-admin.chart-card 
            title="月別売上推移"
            icon="fas fa-chart-line text-success"
            chart-id="revenueChart">
            
            <x-slot name="headerActions">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        2025年
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">2025年</a></li>
                        <li><a class="dropdown-item" href="#">2024年</a></li>
                    </ul>
                </div>
            </x-slot>
        </x-admin.chart-card>
    </div>
    
    <!-- 人気エリア -->
    <div class="col-lg-4 mb-4">
        <x-admin.card 
            title="人気エリア" 
            icon="fas fa-map-marked-alt"
            badge-color="warning">
            
            @if(isset($popularAreas) && $popularAreas->count() > 0)
                @foreach($popularAreas as $area)
                    <x-admin.progress-item
                        :title="$area->name"
                        :subtitle="$area->events_count . '件のイベント'"
                        :value="$area->events_count"
                        :max="$popularAreas->first()->events_count ?? 1"
                        color="warning" />
                @endforeach
            @else
                <x-admin.empty-state 
                    title="データなし"
                    icon="fas fa-map" />
            @endif
        </x-admin.card>
    </div>
</div>

<!-- クイックアクション -->
<div class="row">
    <div class="col-12">
        <x-admin.card 
            title="クイックアクション" 
            icon="fas fa-lightning-bolt"
            badge-color="primary">
            
            <div class="row">
                <x-admin.quick-action 
                    title="新規イベント作成"
                    icon="fas fa-plus-circle"
                    color="primary"
                    :href="route('admin.events.index')" />
                
                <x-admin.quick-action 
                    title="シフト管理"
                    icon="fas fa-calendar-week"
                    color="success"
                    :href="route('admin.staff.shifts')" />
                
                <x-admin.quick-action 
                    title="分析レポート"
                    icon="fas fa-chart-bar"
                    color="info"
                    :href="route('admin.analytics.dashboard')" />
                
                <x-admin.quick-action 
                    title="データエクスポート"
                    icon="fas fa-download"
                    color="warning"
                    onclick="exportData()" />
            </div>
        </x-admin.card>
    </div>
</div>
@endsection


