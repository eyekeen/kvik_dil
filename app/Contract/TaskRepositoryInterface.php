<?php

namespace App\Contract;

use Illuminate\Database\Query\Builder;

interface TaskRepositoryInterface
{
    public function filter(array $values = []): \Illuminate\Support\Collection;
}