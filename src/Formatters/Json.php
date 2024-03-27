<?php

namespace Formatters\Json;

function getJson(array $diff): string
{
    return (string) json_encode($diff, JSON_PRETTY_PRINT);
}
