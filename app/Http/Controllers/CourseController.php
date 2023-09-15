<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin_or_teacher')->only(['store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $response = $this->showData(request());
            return response()->json($response);
        }
        return view('courses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStoreRequest $request)
    {
        $course = new Course();
        $course->name = $request->name;
        $course->code = $request->code;
        $course->description = $request->description;
        $course->active = $request->active == 'on' ? 1 : 0;
        $course->save();
        return response()->json(['status' => 'success', 'message' => 'Course Added Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseUpdateRequest $request, Course $course)
    {
        $course->name = $request->name;
        $course->code = $request->code;
        $course->description = $request->description;
        $course->active = $request->active == 'on' ? 1 : 0;
        $course->save();
        return response()->json(['status' => 'success', 'message' => 'Course Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        $response = ['status' => 'success', 'message' => 'Course Deleted Successful'];
        return response()->json($response, 200);
    }

    private function showData($request)
    {
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'code',
            3 => 'active',
        );
        $total_data = Course::count();
        $total_filtered = $total_data;
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $results = Course::offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $query = Course::where('name', 'LIKE', "%{$search}%")
                ->orWhere('code', 'LIKE', "%{$search}%");
            $results = $query
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();

            $total_filtered = $query->count();
        }

        $data = array();
        if (!empty($results)) {
            foreach ($results as $key => $row) {
                $params = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                $nested_data['id'] = $row->id;
                $nested_data['name'] = $row->name;
                $nested_data['code'] = $row->code;
                $nested_data['active'] = $row->active == 1 ? "Active" : 'In-active';


                $id = $row->id;
                $del_link = route("course.destroy", ["course" => $id]);
                $csrf = csrf_token();

                $user_type = Auth::user()->user_type->name;
                $nested_data['options'] = "";
                if ($user_type == UserTypeEnum::ADMIN || $user_type == UserTypeEnum::TEACHER) {
                    $nested_data['options'] = "
                    <div style='display: flex !important;'>
                        <button
                            title='Edit'
                            class='edit_data mr-2 btn btn-primary btn-sm'
                            style='margin-right:5px !important;'
                            data-params='$params'
                        >
                            <i class='fa fa-pen'></i>
                        </button>
                        <form action='$del_link' method='POST' class='delete_form' data-table-id='table'>
                            <input type='hidden' name='_token' value='$csrf'>
                            <input type='hidden' name='_method' value='delete' />
                            <button type='submit' title='Delete' class='delete_data btn btn-danger btn-sm' data-id='$row->id'>
                            <i class='fa fa-trash'></i>
                            </button>
                        </form>
                    </div>";
                }
                $data[] = $nested_data;
            }
        }
        return array(
            "draw" => (int)$request->input('draw'),
            "recordsTotal" => (int)$total_data,
            "recordsFiltered" => (int)$total_filtered,
            "data" => $data
        );
    }
}
