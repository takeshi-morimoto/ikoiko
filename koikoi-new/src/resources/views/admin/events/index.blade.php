<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>イベント管理 - KOIKOI Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- ヘッダー -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <h1 class="text-2xl font-bold text-gray-900">KOIKOI 管理画面</h1>
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-500">
                        サイトに戻る
                    </a>
                </div>
            </div>
        </header>

        <!-- メインコンテンツ -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- 統計情報 -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">全イベント</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">開催予定</p>
                                <p class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['upcoming'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">開催済み</p>
                                <p class="mt-1 text-3xl font-semibold text-gray-400">{{ $stats['past'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">公開中</p>
                                <p class="mt-1 text-3xl font-semibold text-blue-600">{{ $stats['published'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500">下書き</p>
                                <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $stats['draft'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- フィルター -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">フィルター</h2>
                    <form method="GET" action="{{ route('admin.events.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">イベントタイプ</label>
                            <select name="event_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">すべて</option>
                                @foreach($eventTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('event_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">都道府県</label>
                            <select name="prefecture_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">すべて</option>
                                @foreach($prefectures as $prefecture)
                                    <option value="{{ $prefecture->id }}" {{ request('prefecture_id') == $prefecture->id ? 'selected' : '' }}>
                                        {{ $prefecture->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ステータス</label>
                            <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">すべて</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>公開</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>下書き</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">検索</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="タイトル、コード、会場名"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">開催日（開始）</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">開催日（終了）</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div class="sm:col-span-2 lg:col-span-4 flex gap-2">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                フィルター適用
                            </button>
                            <a href="{{ route('admin.events.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400">
                                リセット
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- イベント一覧 -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                イベント情報
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                開催日時
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                参加状況
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ステータス
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($events as $event)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $event->title }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $event->event_code }} | {{ $event->eventType->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $event->area->prefecture->name }} {{ $event->area->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $event->event_date->format('Y/m/d') }} ({{ $event->day_of_week }})
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $event->start_time ? $event->start_time->format('H:i') : '' }} -
                                        {{ $event->end_time ? $event->end_time->format('H:i') : '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm">
                                        <div>男性: {{ $event->registered_male }}/{{ $event->capacity_male }}</div>
                                        <div>女性: {{ $event->registered_female }}/{{ $event->capacity_female }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($event->status === 'published')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            公開中
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            下書き
                                        </span>
                                    @endif
                                    
                                    @if($event->event_date < now())
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            終了
                                        </span>
                                    @elseif(!$event->is_accepting_male && !$event->is_accepting_female)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            受付終了
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- ページネーション -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $events->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>