<?php

namespace Backstage\Mails\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class MailPreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $mailModel = Config::get('mails.models.mail');

        $mail = $mailModel::findOrFail($request->route('mail'));
        $nonce = base64_encode(random_bytes(16));
        $mailId = json_encode((string) $mail->getKey(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        $targetOrigin = json_encode($request->getSchemeAndHttpHost(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        $resizeScript = <<<HTML
        <script nonce="{$nonce}">
            function postMailsPreviewHeight() {
                const height = Math.max(document.documentElement.scrollHeight, document.body?.scrollHeight ?? 0);
                window.parent.postMessage({ type: 'mails-iframe-resize', mailId: {$mailId}, height }, {$targetOrigin});
            }
            window.addEventListener('load', postMailsPreviewHeight);
            window.addEventListener('resize', postMailsPreviewHeight);
            new MutationObserver(postMailsPreviewHeight).observe(document.documentElement, { childList: true, subtree: true });
        </script>
        HTML;

        return response($mail->html . $resizeScript, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "frame-ancestors 'self'; sandbox allow-scripts; script-src 'nonce-{$nonce}'",
        ]);
    }
}
