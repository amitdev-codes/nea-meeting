<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Traits\HandlesExceptions;
use App\DataTables\UsersDataTable;
use App\Traits\BulkDeletableTrait;
use Spatie\Permission\Models\Role;
use Modules\Master\Models\Component;
use App\Http\Requests\StoreUserRequest;
use Modules\Master\Models\SubComponent;
use Spatie\Activitylog\Models\Activity;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Controllers\BaseAdminController;

class UserController extends BaseAdminController
{
    use BulkDeletableTrait;
    use HandlesExceptions;

    protected $model = User::class;
    protected string $resourcePermission = 'users';
    protected string $resourceName = 'users';
    protected string $formView = 'pages.users.usersForm';

    public function __construct(ResponseService $responseService)
    {
        parent::__construct($responseService);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        // $this->authorizeResource('view-users');
        return $dataTable->render('pages.resources.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::get(['id', 'name','name_np'])->where('id','!=',1);
        return $this->renderForm($this->formView, null,['roles' => $roles]);
    }

    public function show(User $user)
    {
        // $this->authorizeResource('view-users');
        // $user->load('addresses.province', 'addresses.district', 'addresses.localLevel','component','subComponent');

        $activities = Activity::where('subject_id', $user->id)
            ->orWhere('causer_id', $user->id)
            ->latest()
            ->paginate(5);

        return view('pages.users.show', ['resource' => $user, 'activities' => $activities]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        return $this->handleRequest($request, function () use ($request) {
            $user = User::create($request->validated());
            if ($request->has('save_and_add_more')) {
                return redirect()->route('admin.users.create')
                    ->with('success', 'User created successfully. Add another one.');
            }
            $user->assignRole($request->input('rolename'));
            if ($request->has('clusters')) {
                $user->clusters = $request->input('clusters');
                $user->save();
            }
            // $user->addresses()->create($request->only(['province_id','district_id','localLevel_id','ward_no','street_name']));
        }, 'admin.users.index','User Created successfully.', 'Failed to create the User.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::get(['id', 'name','name_np']);
        return $this->renderForm($this->formView, $user,['roles' => $roles]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        return $this->handleRequest($request, function () use ($request, $user) {
            $userData = $request->validated();
            if ($request->filled('password')) {
                $userData['password'] = bcrypt($request->password);
            }

            $user->update($userData);
            $user->syncRoles($request->rolename);
        }, 'admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user)
    {
        // $this->authorizeResource('delete-users');

        return $this->handleRequest($request, function () use ($user) {
            $user->delete();
        }, 'admin.users.index','User deleted successfully.', 'Failed to delete the User.');
    }
}
