<b>{{ $task->name }}</b>

@if($task->deadline)
@if($task->type === \App\Enums\TaskTypeEnum::DAILY)
Ежедневно в {{ $task->deadline }}
@else
Дедлайн: {{ \Carbon\Carbon::parse($task->deadline)->locale('ru')->diffForHumans() }}
@endif
@endif

Статус: @if($task->completed) Выполнено ✅ @else Не выполнено ❌  @endif

@if($task->description)
Описание:
<b>{{ $task->description }}</b>
@endif
