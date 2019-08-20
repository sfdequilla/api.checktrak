<?php

namespace App\Http\Controllers;

use App\User;
use App\Group;
use App\Action;
use App\Branch;
use App\Module;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $module;

    public function __construct()
    {
        $this->module = Module::where('code', 'usr')->first();
    }

    public function index()
    {
        $this->authorize('module', $this->module);

        return User::get();
    }

    public function store(Request $request)
    {
        $this->authorize('module', $this->module);

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users',
            'group_id' => 'required|integer|exists:groups,id',
        ]);

        $request['password'] = bcrypt(config('app.default_pass'));

        User::create($request->all());

        return ['message' => 'User successfully recorded.'];
    }

    public function show(User $user)
    {
        $this->authorize('module', $this->module);

        return $user->access();
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('module', $this->module);

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return ['message' => 'User successfully updated.'];
    }

    public function destroy(User $user)
    {
        abort(403);
    }

    public function access(Request $request, User $user)
    {
        $this->authorize('module', $this->module);

        $request->validate(['group_id' => 'required|integer|exists:groups,id']);

        $group = Group::find($request->get('group_id'));

        $group->users()->save($user);

        $actions = ! $group->action ? Action::whereIn('code', $request->get('actions'))->get() : [];
        $branches = ! $group->branch ? Branch::whereIn('code', $request->get('branches'))->get() : [];
        $modules = ! $group->module ? Module::whereIn('code', $request->get('modules'))->get() : [];

        $user->actions()->sync($actions);
        $user->branches()->sync($branches);
        $user->modules()->sync($modules);

        return $user->access();
    }
}
