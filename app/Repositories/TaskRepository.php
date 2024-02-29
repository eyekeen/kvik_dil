<?php

namespace App\Repositories;

use App\Contract\TaskRepositoryInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TaskRepository implements TaskRepositoryInterface
{
    public function filter(array $values = []): \Illuminate\Support\Collection
    {

        $tasks = DB::table('tasks')->where(function (Builder $query) use ($values) {
            foreach ($values as $key => $value) {
                if ($value == '') {
                    continue;
                } else {
                    $query->where($key, $value)->get();
                }
            }
        });

        return $tasks->get();
    }
}