@props([
    "title" => "タイトルなし",
    "message" => "初期値です",
    "content" => "初期",
])

<div {{ $attributes->merge([
        "class" => "border-2 shadow-md w-1/4 p-2"
    ]) }}>
    <div>{{ $title }}</div>
    <div>画像</div>
    <div>{{ $content }}</div>
    <div>{{ $message }}</div>
</div>