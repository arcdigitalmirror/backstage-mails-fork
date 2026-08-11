<?php

it('formats HTML source for readability', function () {
    $html = view('mails::mails.html-formatted', [
        'html' => '<div><p>Hello</p></div>',
    ])->render();

    expect($html)->toContain("&lt;div&gt;\n  &lt;p&gt;Hello&lt;/p&gt;\n&lt;/div&gt;");
});
