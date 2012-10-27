<?php

global $fail_count;

$fail_count = 0;

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

function assert_handler($file, $line, $code)
{
    echo "<hr>Assertion Failed:
        File '$file'<br />
        Line $line<br /><hr />";
    global $fail_count;
    $fail_count++;
}

assert_options(ASSERT_CALLBACK, 'assert_handler');