<?php

namespace App\Http\Controllers\Api;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentForUserResource;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Department::orderBy('created_at', 'DESC')->paginate(50);
        // return Department::with('users')->orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getDepartmentsNoPaginate()
    {
        return Department::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $department = new Department;
        $department->name = $request->department_name;
        $department->save();

        return Department::orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return Department::findOrFail($department->id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $department = Department::findOrFail($department->id);
        $department->update(['name' => $request->name]);

        return Department::orderBy('created_at', 'DESC')->paginate(50);
    }

    public function getDepartmentForUser()
    {
        return Department::all();
        //return DepartmentForUserResource::collection(Department::all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        //
    }
}
