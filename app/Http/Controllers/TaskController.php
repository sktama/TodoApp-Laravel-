<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Folder;
use App\Task;
use App\Http\Requests\CreateTask;

class TaskController extends Controller
{
  public function index(int $id)
  {
    // 全てのフォルダーを取得する
    $folders = Folder::all();

    // 選択されたフォルダーを取得する
    $current_folder = Folder::find($id);

    // 選択されたフォルダーに紐付くタスクを取得する
    $tasks = $current_folder->tasks()->get();

    // 一覧を描画する際に値を一緒に送信
    return view('tasks/index', [
      'folders' => $folders,
      'current_folder_id' => $id,
      'tasks' => $tasks,
    ]);
  }

  /*
   * GET /folders/{id}/tasks/create
   */
  public function showCreateForm(int $id)
  {
    return view('tasks/create', [
      'folder_id' => $id
    ]);
  }

  public function create(int $id, CreateTask $request)
  {
    $current_folder = Folder::find($id);

    $task = new Task();
    $task->title = $request->title;
    $task->due_date = $request->due_date;

    $current_folder->tasks()->save($task);

    return redirect()->route('tasks.index', [
      'id' => $current_folder->id,
    ]);
  }

  /**
   * GET /folders/{id}/tasks/{task_id}/edit
   */
  public function showEditForm(int $id, int $task_id)
  {
      $task = Task::find($task_id);

      return view('tasks/edit', [
          'task' => $task,
      ]);
  }
  public function edit(int $id, int $task_id, EditTask $request)
  {
      // 1
      $task = Task::find($task_id);

      // 2
      $task->title = $request->title;
      $task->status = $request->status;
      $task->due_date = $request->due_date;
      $task->save();

      // 3
      return redirect()->route('tasks.index', [
          'id' => $task->folder_id,
      ]);
  }
}
