<?php

namespace App\Formatters\Json;

function json(array $diff): mixed
{
    return json_encode($diff, JSON_PRETTY_PRINT);
}
