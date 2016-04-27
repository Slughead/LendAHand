<?php

namespace LendAHand;

return [
    ['GET', '/', ['LendAHand\Controllers\Homepage', 'show']],
    ['GET', '/another-route', function () {
        echo 'This works too';
    }],
];