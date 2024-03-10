<?php

namespace Formatters\Json;

function json(array $diff): mixed
{
    return json_encode($diff, JSON_PRETTY_PRINT);
}
