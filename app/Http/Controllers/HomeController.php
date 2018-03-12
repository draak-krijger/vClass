<?php

namespace App\Http\Controllers;

use App\Assignment;
use App\User;
use App\Course ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;
use App\Http\Controllers\Auth\RegisterController ;
use Mail ;
use File ;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    // Admin authorized url

    public function index()
    {
    	try
    	{
        	if(Auth::user()->isAdmin)
        		return $this->AdminHome();

        	else if(Auth::user()->isTeacher)
        		return $this->TeacherHome();

        	else if(Auth::user()->isStudent)
        		return $this->StudentHome();
        }

        catch(\Exception $ex)
        {
        	return "Bullshit things occur";
        }
    }

    public function addCourse(Request $req) // must be a admin
    {
        if(!(Auth::user()->isTeacher))
            return redirect()->back();

        $req->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]);

        try
        {
            $ncourse = new Course();

            $ncourse['title'] = $req->title ;
            $ncourse['description'] = $req->description ;
            $ncourse['courseTeacher'] = Auth::User()->_id ;
            $ncourse['students'] = [] ;
            $ncourse['result'] = [] ;
            $ncourse['attendance'] = [] ;
            $ncourse['posts'] = [] ;
            $ncourse['assignments'] = [] ;
            $ncourse['isOpen'] = 0 ;
            $ncourse->save();

            $teacher = User::find($ncourse['courseTeacher']) ;
            $arr = $teacher->courses ;

            $tcourse = NULL ;
            $tcourse['course_id'] = $ncourse['_id'] ;
            $tcourse['status'] = 0 ;
            $tcourse['notification'] = 0 ;
            $tcourse['attendance'] = [] ;

            $teacher->courses = array_prepend($arr,$tcourse); ;
            $teacher->save();

            $student_array = [] ;

            if ($req->hasFile('studentNumber')) {
                $file = $req->file('studentNumber');
                $students = fopen($file, "r");

                while (!feof($students)) {
                    $tp = fgets($students);

                    $flag = 0 ;
                    $ln = strlen($tp) ; // has a bug for \n \r etc
                    $regis = "" ;
                    $mail = "" ;

                    for($i=0 ; $i<$ln ; $i++)
                    {
                        if($tp[$i] == ' ')
                        {
                            $flag = 1 ;
                            continue;
                        }

                        if($flag == 0)
                            $regis .= $tp[$i] ;

                        else if(($tp[$i] >= 'a' && $tp[$i]<='z') || ($tp[$i] >= 'A' && $tp[$i] <= 'Z') || ($tp[$i] >= '0' && $tp[$i]<= '9') || $tp[$i] == '.' || $tp[$i] == '_' || $tp[$i] == '@')
                            $mail .= $tp[$i] ;
                    }

                    if(strlen($regis) == 10 && strlen($mail)>0)
                    {
                        try
                        {
                            $tp = User::where('registrationNumber',$regis)->get();
                            $tp = $tp[0] ;
                            $usr = User::find($tp['_id']);
                            $arr = $usr->courses ;
                            $usr->courses = array_prepend($arr,$tcourse);
                            $usr->save();
                        }

                        catch (\Exception $ex)
                        {
                            $temp = new Request();
                            $temp['name'] = $regis;
                            $temp['email'] = $mail;
                            $temp['isStudent'] = true;
                            $temp['regNum'] = $regis;

                            $temp['password'] = md5(uniqid(rand(), true));

                            $data = array("mail" => $mail, "password" => $temp['password']);

                            Mail::send('mail', $data, function ($message) use ($regis, $mail) {
                                $message->to($mail, $regis)
                                    ->subject('Web Testing Mail');
                                $message->from('aobro.993@gmail.com', 'Enam Sir');
                            });

                            $temp['password_confirmation'] = $temp['password'];
                            $temp['course'] = $tcourse;

                            try
                            {
                                $reg = new RegisterController();
                                $sid = $reg->register($temp);
                                array_push($student_array,$sid);
                            }

                            catch(\Exception $ex)
                            {

                            }
                        }
                    }
                }
                $ncourse['students'] = $student_array ;
                $ncourse->save();
            }
        }

        catch (\Exception $ex)
        {
            return "Ooops !! Something wrong !!";
        }
        return redirect('home');
    }

    public function TeacherAddPage() // must be a admin
    {
        if(!(Auth::user()->isAdmin))
            return redirect()->back();

        return view('TeacherAdd');
    }

    public function TeacherAdd(Request $req) // must be a admin
    {
        if(!(Auth::user()->isAdmin))
            return redirect()->back();

        $req['isTeacher'] = true ;
        //return $req ;
        $reg = new RegisterController();
        $reg->register($req);
        return redirect('home');
    }

    // end admin authorized functions

    public function showCourse($courseId) // complete for student and teacher
    {
        try
        {
            if($tp = Course::find($courseId))
            {
                if(Auth::user()->isTeacher)
                    return $this->showCourseTeacherView($courseId);

                else
                    return $this->showCourseStudentView($courseId);
            }
            else
            {
                throw new \Exception("No Such Course");
            }
        }

        catch (\Exception $ex)
        {
            return "No Such Course" ;
        }
    }

    public function showCourseTeacherView($courseId)
    {
        $course = Course::find($courseId);

        $posts = $course['posts'] ;
        $assignments = [] ;
        $results = $course['result'] ;
        $title = $course['title'] ;
        $isOpen = $course['isOpen'] ;

        foreach($course['assignments'] as $assignment)
        {
            $temp = NULL ;
            $temp['title'] = $assignment['title'] ;
            $temp['id'] = $assignment['id'] ;
            array_push($assignments,$temp);
        }

        return view('showCourseTeacherView',compact('posts','assignments','results','title','isOpen')) ;
    }

    public function showCourseStudentView($courseId)
    {
        $course = Course::find($courseId);

        $posts = $course['posts'] ;
        $assignments = [] ;
        $results = $course['result'] ;
        $title = $course['title'] ;
        $isOpen = $course['isOpen'] ;

        foreach($course['assignments'] as $assignment)
        {
            $temp = NULL ;
            $temp['title'] = $assignment['title'] ;
            $temp['id'] = $assignment['id'] ;
            array_push($assignments,$temp);
        }

        return view('showCourseStudentView',compact('posts','assignments','results','title','isOpen')) ;
    }

    public function submitAttendance(Request $req) // complete for student
    {
        if(!(Auth::user()->isStudent))
            return redirect()->back() ;

        $day = NULL ;

//        try
//        {
            $tp = Course::select('attendance')->where('_id',$req->courseId)->get();
            $attendances = $tp[0] ;
            $tattendances = [] ;
            $isvalid = NULL ;

            foreach($attendances->attendance as $attendance)
            {
                if($isvalid)
                {
                    array_push($tattendances,$attendance);
                    continue;
                }

                foreach($attendance['keyList'] as $key)
                {
                    if($key == $req->key)
                    {
                        $day = $attendance['day'] ;
                        $isvalid = 1 ;
                        break;
                    }
                }

                if($isvalid)
                {
                    $temp = NULL ;
                    $temp['day'] = $attendance['day'] ;
                    $temp['keyList'] = [] ;

                    foreach($attendance['keyList'] as $key)
                    {
                        if($key != $req->key)
                        {
                            array_push($temp['keyList'],$key);
                        }
                    }
                    array_push($tattendances,$temp);
                }

                else
                    array_push($tattendances,$attendance);
            }

            if($day != NULL)
            {
                $user = Auth::user() ;
                $tcourses = [] ;
                $isvalid = NULL ;

                foreach ($user->courses as $course)
                {
                    if($course['course_id'] == $req->courseId)
                    {
                        $couseD = $course ;
                        $ishave = 1 ;

                        foreach ($course['attendance'] as $attend)
                        {
                            if($attend == $day)
                            {
                                $ishave = NULL ;
                                break;
                            }
                        }

                        if($ishave == 1)
                        {
                            $ary = $course['attendance'] ;
                            array_push($ary,$day);
                            $couseD['attendance'] = $ary ;
                            $isvalid = 1 ;
                        }
                        array_push($tcourses,$couseD);
                    }

                    else
                        array_push($tcourses,$course);
                }

                if($isvalid) {
                    $user->courses = $tcourses;
                    $tp = Course::find($req->courseId);

                    $ttmp = $tp->attendance[0] ;
                    $ttmp['keyList'] = $tattendances ;

                    $tp->attendance = $tattendances ;

                    $tp->save();
                    $user->save();
                }
            }
//        }
//
//        catch (\Exception $ex)
//        {
//
//        }

        return "attendance submit process completed successfully" ;
    }

    public function TeacherHomeOther($str)
    {
        try
        {
            //$tp = User::where('_id',$str)->get();
            $tp=User::find($str);
            //$tp = $tp[0] ;

            if(!($tp->isTeacher))
                throw new Exception('Not a Teacher');

            return $tp->_id ;
        }

        catch (\Exception $ex)
        {
            return "No Such User Found" ;
        }
    }

    public function TeacherHome()
    {
        $courses = Auth::user()->courses ;


        $runningCourses = [] ;
        $completedCourses = [] ;

        foreach($courses as $course)
        {
            $id = $course['course_id'] ;
            $temp = NULL ;
            $temp['id'] = $id ;
            $temp['name'] = Course::find($id)->title ;

            if($course['status'] == 0)
                array_push($runningCourses,$temp);

            else
                array_push($completedCourses,$temp);
        }


    	return view('TeacherHomePage',compact('runningCourses','completedCourses')) ;
    }

    public function StudentHome()
    {
        $courses = Auth::user()->courses ;


        $runningCourses = [] ;
        $completedCourses = [] ;

        foreach($courses as $course)
        {
            $id = $course['course_id'] ;
            $temp = NULL ;
            $temp['id'] = $id ;
            $temp['name'] = Course::find($id)->title ;

            if($course['status'] == 0)
                array_push($runningCourses,$temp);

            else
                array_push($completedCourses,$temp);
        }
    	return view('StudentHomePage',compact('runningCourses','completedCourses')) ;
    }

    public function AdminHome()
    {
    	return view('AdminHome') ;
    }

    public function AddNewStudent(Request $data) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return redirect()->back() ;

        $ncourse = Course::find($data->courseId);

        $tcourse = NULL ;
        $tcourse['course_id'] = $ncourse['_id'] ;
        $tcourse['status'] = 0 ;
        $tcourse['notification'] = 0 ;
        $tcourse['attendance'] = [] ;

        $student_array = $ncourse['students'] ;

        $req = $data ;

        if ($req->hasFile('studentList')) {
            $file = $req->file('studentList');
            $students = fopen($file, "r");

            while (!feof($students)) {
                $tp = fgets($students);

                $flag = 0 ;
                $ln = strlen($tp) ; // has a bug for \n \r etc
                $regis = "" ;
                $mail = "" ;

                for($i=0 ; $i<$ln ; $i++)
                {
                    if($tp[$i] == ' ')
                    {
                        $flag = 1 ;
                        continue;
                    }

                    if($flag == 0)
                        $regis .= $tp[$i] ;

                    else if(($tp[$i] >= 'a' && $tp[$i]<='z') || ($tp[$i] >= 'A' && $tp[$i] <= 'Z') || ($tp[$i] >= '0' && $tp[$i]<= '9') || $tp[$i] == '.' || $tp[$i] == '_' || $tp[$i] == '@')
                        $mail .= $tp[$i] ;
                }

                if(strlen($regis) == 10 && strlen($mail)>0)
                {
                    try
                    {
                        $tp = User::where('registrationNumber',$regis)->get();
                        $tp = $tp[0] ;

                        try
                        {
                            $usr = User::where('courses.course_id',$tcourse['course_id'])->get();

                        }
                        catch(\Exception $ex)
                        {
                            $usr = User::where($tp['_id']);
                            $arr = $usr->courses;
                            $usr->courses = array_prepend($arr, $tcourse);
                            $usr->save();
                        }
                    }

                    catch (\Exception $ex)
                    {
                        $temp = new Request();
                        $temp['name'] = $regis;
                        $temp['email'] = $mail;
                        $temp['isStudent'] = true;
                        $temp['regNum'] = $regis;

                        $temp['password'] = md5(uniqid(rand(), true));

                        $data = array("mail" => $mail, "password" => $temp['password']);

                        Mail::send('mail', $data, function ($message) use ($regis, $mail) {
                            $message->to($mail, $regis)
                                ->subject('Web Testing Mail');
                            $message->from('aobro.993@gmail.com', 'Enam Sir');
                        });

                        $temp['password_confirmation'] = $temp['password'];
                        $temp['course'] = $tcourse;

                        try
                        {
                            $reg = new RegisterController();
                            $sid = $reg->register($temp);
                            array_push($student_array,$sid);
                        }

                        catch(\Exception $ex)
                        {

                        }
                    }
                }
            }
            $ncourse['students'] = $student_array ;
            $ncourse->save();
        }
        return ;
    }

    public function CourseClose(Request $req) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return "not granted" ;

        $temp = Course::find($req);
        $temp['isOpen'] = false ;
        $temp->save();
        return "Successfully closed" ;
    }

    public function postInfo(Request $req) // must be a techer
    {
        if(!(Auth::user()->isTeacher))
            return "not granted" ;

        $curs = Course::find($req->courseId);
        $posts = $curs->posts ;

        $tpost = NULL ;
        $tpost['title'] = $req->title ;
        $tpost['description'] = $req->description ;
        array_unshift($posts,$tpost);

        $curs->posts = $posts;
        $curs->save();

        return "post inserted successfully" ;
    }

    public function postAssignment(Request $req) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return "not granted" ;

        $curs = Course::find($req->courseId);

        $assignment = new Assignment();
        $assignment->isOpen = 1 ;
        $assignment->title = $req->title ;
        $assignment->description = $req->description ;
        $assignment->submitted = [] ;
        $assignment->save();

        $tasign = NULL ;

        $tasign['id'] = $assignment->id ;
        $tasign['title'] = $req->title ;

        $arr = $curs->assignments ;
        array_unshift($arr,$tasign);
        $curs->assignments = $arr ;
        $curs->save();

        return "assignment post successfully" ;
    }

    public function postResult(Request $req) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return "not granted" ;

        $curs = Course::find($req->courseId);
        $arr = $curs->result ;

        $temp = NULL ;
        $temp['title'] = $req->title ;
        $temp['link'] = $req->link ;

        array_unshift($arr,$temp);
        $curs->result = $arr ;
        $curs->save();

        return "post result successfully." ;
    }

    public function addNewKey(Request $req) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return "not granted" ;

        $ncourse = Course::find($req->courseId);
        $keys = [] ;

        try {
            if ($req->hasFile('keyList')) {
                $file = $req->file('keyList');
                $students = fopen($file, "r");

                while (!feof($students)) {
                    $str = fgets($students);
                    $ln = strlen($str);

                    $tstr = "";

                    for ($i = 0; $i < $ln; $i++) {
                        if (($str[$i] >= 'a' && $str[$i] <= 'z') || ($str[$i] >= 'A' && $str[$i] <= 'Z') || ($str[$i] >= '0' && $str[$i] <= '9'))
                            $tstr .= $str[$i];
                    }

                    if (strlen($tstr) > 0)
                        array_push($keys, $tstr);
                }

                $temp = $ncourse->attendance;

                $day = 0;

                if (count($temp) > 0)
                    $day = $temp[0]['day'] + 1;

                $tp = NULL;
                $tp['day'] = $day;
                $tp['keyList'] = $keys;
                array_unshift($temp, $tp);

                $ncourse->attendance = $temp;
                $ncourse->save();
            }
        }

        catch (\Exception $ex)
        {

        }

        return "add key successfully" ;
    }

    public function showAssignment($id)
    {
        $data = Assignment::find($id);

        $title = $data->title ;
        $description = $data->description ;
        $isOpen = $data->isOpen ;
        $isTeacher = 0 ;
        $submitted_by = $data->submitted ;
        $path = public_path().'/assignments/' ;

        if(Auth::user()->isTeacher)
            $isTeacher = 1 ;


        return view('showAssignment',compact('title','description','isOpen','isTeacher','submitted_by','path')) ;
    }

    public function downloadFile($fileName,$name) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return redirect()->back() ;

        $file = public_path().'\assignments\\'.$fileName.'\\'.$name ;
        //return $file;
        return response()->download($file);
    }

    public function submitAssignment(Request $req) // must be a student
    {
        if(!(Auth::user()->isStudent))
            return redirect()->back() ;
//        $tp = 0 ;

//        if($req->hasFile('assignment'))
//            $tp = 1 ;
//
//        //$tmp = Assignment::find($req->assignmentId);
        try
        {
            $path = public_path() . '/assignments/' . $req->assignmentId;

            if (!File::exists($path)) {
                File::makeDirectory($path);
            }

            if ($req->hasFile('assignment')) {
                $id = Auth::user()->registrationNumber;
                $reg = $id;
                $file = $req->file('assignment');
                $id .= '.';
                $id .= $file->getClientOriginalExtension();

                $assignment = Assignment::find($req->assignmentId);
                $submitted = $assignment->submitted;
                $tsubmitted = [];

                $tmp = NULL;
                $tmp['registrationNum'] = $reg;
                $tmp['extention'] = $file->getClientOriginalExtension();

                array_push($tsubmitted, $tmp);

                foreach ($submitted as $submit) {
                    if ($submit['registrationNum'] != $reg)
                        array_push($tsubmitted, $submit);
                }

                $assignment->submitted = $tsubmitted;
                $assignment->save();
                $file->move($path, $id);
                return "submitted successfully";
            }
        }

        catch (\Exception $ex)
        {

        }

        return "something not right" ;
    }

    public function closeAssignment(Request $req) // must be a teacher
    {
        if(!(Auth::user()->isTeacher))
            return "not granted" ;

        $assignment = Assignment::find($req->assignmentId);

        $assignment->isOpen = 0 ;
        $assignment->save();

        return "closed successfully" ;
    }

    public function getAjax()
    {
        $temp = User::select('courses')->where('courses.course_id','5a6baf5ffb94871cdc006940')->where('isTeacher',true)->get();
        return $temp ;
        $persons = [] ;
        return view('AjaxReq',compact('persons'));
    }

    public function postAjax(Request $req)
    {
        $tp = $req ;

//        if($req->hasFile('studentList'))
//            $tp = 1 ;

        return $tp ;

        return response()->json(['success'=>'Got Simple Ajax Request.' , 'id'=>$tp]);
    }
}