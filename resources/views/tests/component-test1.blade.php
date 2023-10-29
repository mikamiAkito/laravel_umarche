<x-tests.app>
    <x-slot name="header">ヘッダー1</x-slot>
    1
    <x-tests.card title="タイトル" content="コンテント" :message="$message" />
    <x-tests.card title="タイトル" />
    <x-tests.card title="CSSを変更" class="bg-red-300" />
</x-tests.app>
