<?php

function t($startTime): float
{
    return round(microtime(true) - $startTime, 3);
}
