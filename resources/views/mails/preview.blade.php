<div class="w-full" x-data="{ height: '250px' }" x-on:message.window="
    if ($event.source === $refs.preview.contentWindow
        && $event.data?.type === 'mails-iframe-resize'
        && $event.data.mailId === {{ \Illuminate\Support\Js::from((string) $mail->getKey()) }}
        && Number.isFinite(Number($event.data.height))
    ) {
        height = Math.min(Math.max(Number($event.data.height), 250), 100000) + 'px';
    }
">
    <iframe
        x-ref="preview"
        src="{{ route('filament.' . Filament\Facades\Filament::getCurrentPanel()->getId() . '.mails.preview', ['tenant' => Filament\Facades\Filament::getTenant(), 'mail' => $mail->id]) }}"
        sandbox="allow-scripts"
        class="w-full border-none"
        x-bind:style="'height: ' + height">
    </iframe>
</div>
