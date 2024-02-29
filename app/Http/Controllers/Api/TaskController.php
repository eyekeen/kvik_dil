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

    public function __construct(private TaskRepositoryInterface $repository){}

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
    public function store(StoreTaskRequest $request)
    {
        $request->validated();

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

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
                'message' => 'Record not found.'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::where('id', $id);

        try {
            $task->update($request->all());
            return "updated";
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($task = Task::find($id)) {
            $task->delete();

            return response()->json([
                'msg' => 'task deleted',
            ]);
        } else {
            return response()->json([
                'msg' => 'task not found',
            ]);
        }
    }
}
