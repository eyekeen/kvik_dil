<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TaskCollection;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Contract\TaskRepositoryInterface;

class TaskController extends Controller
{

    public function __construct(private TaskRepositoryInterface $repository)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->get('start_date') || $request->get('end_date') || $request->get('status')) {
            return $this->repository->filter($request->all());
        }

        return new TaskCollection(Task::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    // TODO: validation not work
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->all());

        return [
            'msg' => 'task created',
            'task' => $task,
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            return new TaskResource(Task::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Задача не найдена.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
        try {
            $task = Task::findOrFail($id);
            $task->update($request->all());

            return response()->json([
                'msg' => 'Задача Обновлена',
                'task' => $task,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'msg' => 'Задача не найдена',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
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
            ]);
        }
    }
}
