<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Contract\TaskRepositoryInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskController extends Controller
{

    public function __construct(private TaskRepositoryInterface $repository)
    {
    }


    public function index(Request $request): JsonResource
    {
        if ($request->get('start_date') || $request->get('end_date') || $request->get('status')) {
            $tasks = $this->repository->filter($request->all());
        } else {
            $tasks = Task::all();
        }

        return TaskResource::collection($tasks);
    }


    // TODO: validation not work
    public function store(StoreTaskRequest $request): JsonResponse
    {

        // $request->validated();

        $task = Task::create($request->all());

        return response()->json([
            'msg' => 'Задача создана',
            'task' => $task
        ], 200);

    }


    public function show(string $id): JsonResource|JsonResponse
    {
        try {
            $task = Task::findOrFail($id);

            return new TaskResource($task);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Задача не найдена',
            ], 404);
        }
    }


    public function update(Request $request, string $id): JsonResponse
    {

        try {
            $task = Task::findOrFail($id);

            if (!$task->update($request->all())) {
                return response()->json([
                    'msg' => 'Ошибка на сервере',
                ], 500);
            }

            return response()->json([
                'msg' => 'Задача обновлена',
                'task' => $task,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Задача не найдена',
            ], 404);
        }
    }


    public function destroy(string $id): JsonResponse
    {
        try {
            $task = Task::findOrFail($id);
            $task->delete();

            return response()->json([
                'msg' => 'Задача удалена',
                'task' => $task,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Задача не найдена',
            ], 404);
        } catch (\LogicException $e) {
            return response()->json([
                'msg' => 'Ошибка на сервере',
            ], 500);
        }
    }
}
