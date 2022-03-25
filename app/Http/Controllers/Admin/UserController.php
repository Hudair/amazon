<?php

namespace App\Http\Controllers\Admin;

use App\Common\Authorizable;
use App\Events\Profile\PasswordUpdated;
use App\Events\User\UserCreated;
use App\Events\User\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Validations\AdminUserUpdatePasswordRequest as UpdatePasswordRequest;
use App\Http\Requests\Validations\CreateUserRequest;
use App\Http\Requests\Validations\UpdateUserRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use Authorizable;

    private $model_name;

    private $user;

    /**
     * construct
     */
    public function __construct(UserRepository $user)
    {
        parent::__construct();
        $this->model_name = trans('app.model.user');
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->all();

        $trashes = $this->user->trashOnly();

        return view('admin.user.index', compact('users', 'trashes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check if the merchant can add more user in the team
        if (Auth::user()->isFromPlatform() || Auth::user()->shop->canAddMoreUser()) {
            return view('admin.user._create');
        }

        return view('admin.partials._max_user_limit_notice');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = $this->user->store($request);

        event(new UserCreated($user, Auth::user()->getName(), $request->get('password')));

        return back()->with('success', trans('messages.created', ['model' => $this->model_name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->user->find($id);

        return view('admin.user._show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->user->find($id);

        return view('admin.user._edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.users', 3)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $user = $this->user->update($request, $id);

        event(new UserUpdated($user));

        return back()->with('success', trans('messages.updated', ['model' => $this->model_name]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showChangePasswordForm(Request $request, $id)
    {
        $user = $this->user->find($id);

        return view('admin.user._change_password', compact('user'));
    }

    /**
     * Update login password only.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.users', 3)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $user = $this->user->update($request, $id);

        event(new PasswordUpdated($user));

        return back()->with('success', trans('messages.password_updated'));
    }

    /**
     * Trash the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function trash(Request $request, $id)
    {
        if (config('app.demo') == true && $id <= config('system.demo.users', 3)) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $user = $this->user->trash($id);

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
    }

    /**
     * Restore the specified resource from soft delete.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        $this->user->restore($id);

        return back()->with('success', trans('messages.restored', ['model' => $this->model_name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->user->destroy($id);

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massTrash(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->user->massTrash($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.trashed', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.trashed', ['model' => $this->model_name]));
    }

    /**
     * Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(Request $request)
    {
        if (config('app.demo') == true) {
            return back()->with('warning', trans('messages.demo_restriction'));
        }

        $this->user->massDestroy($request->ids);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }

    /**
     * Empty the Trash the mass resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyTrash(Request $request)
    {
        $this->user->emptyTrash($request);

        if ($request->ajax()) {
            return response()->json(['success' => trans('messages.deleted', ['model' => $this->model_name])]);
        }

        return back()->with('success', trans('messages.deleted', ['model' => $this->model_name]));
    }
}
