@props(['events', 'theme' => null])

<div class="event-grid">
    @forelse($events as $event)
        <x-event-card :event="$event" :theme="$theme" />
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-gray-500">現在開催予定のイベントはありません。</p>
        </div>
    @endforelse
</div>