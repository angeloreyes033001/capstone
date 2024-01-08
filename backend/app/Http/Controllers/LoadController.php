<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Load;
use App\Models\Professor;
use App\Models\Schedule;
use App\Models\sections;
use App\Models\Subject;
use App\Models\YearLevel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LoadController extends Controller
{
    // protected function read_load_professor($tokens){
    //     try{

    //         $department = $this->tokenDepartment($tokens);

    //         $professors = Professor::where(['professorDepartment'=>$department,'professorSoftDelete'=>0])
    //         ->join("ranks","professorRank","=","rankID")
    //         ->select("professorID", "professorFullname as fullname","rankID","rankName as rank","rankHour as hour", "professorDesignation as designated")
    //         ->get();

    //         $renewals = array();

    //         foreach($professors as $professor){
    //             if($professor['designated'] != 'none'){
    //                 $designation = Designation::where(['designationPosition'=>$professor['designated']])
    //                 ->select('designationHour as hour')
    //                 ->first();

    //                 array_push($renewals,[
    //                     "professorID"=>$professor['professorID'],
    //                     "fullname"=>$professor['fullname'],
    //                     "rankID"=>$professor['rankID'],
    //                     "rank"=>$professor['rank'],
    //                     "hour"=>$designation['hour'],
    //                     "designated"=>$professor['designated']
    //                 ]);
    //             }
    //             else{
    //                 array_push( $renewals,$professor);
    //             }
    //         }

    //         $output = array();
    //         foreach($renewals as $renew){
    //             $load = Load::where(["LoadProfessor"=>$renew['professorID'],"loadDepartment"=>$department,"loadSoftDelete"=>0])
    //             ->select("loadID as id","loadProfessor as professor", "loadSubject as subject","loadHour as hour")
    //             ->get();
    //             array_push($output,['info'=>$renew,"loads"=>$load]);
    //         }

    //         return response()->json($output);

    //     }
    //     catch(Exception $e){
    //         Log::error($e->getMessage());
    //     }
    // }

    // protected function read_all_load(Request $request){
    //     try{
    //         $validator = Validator::make($request->all(),[
    //             'year'=>"required",
    //             "semester"=>"required",
    //             "professor"=>"required",
    //             "tokens"=>"required"
    //         ]);

    //         $department = $this->tokenDepartment($request->input('tokens'));

    //         $totalSection = sections::where([
    //             'sectionYearlevel'=>$request->input('year'),
    //             'sectionSemester'=>$request->input('semester'),
    //             'sectionDepartment'=>$department,
    //         ])
    //         ->select("sectionID as id","sectionSlot as total")
    //         ->first();

    //         $subjects = Subject::where([
    //             'subjectYearlevel'=>$request->input('year'),
    //             'subjectSemester'=>$request->input('semester'),
    //             'subjectDepartment'=>$department
    //             ])
    //         ->select("subjectCode as code","subjectName as subject","subjectLecHour as lec","subjectLabHour as lab")
    //         ->get();

    //         $totalProfessorHours = Load::join("professors","professorID","=","loadProfessor")
    //         ->join('ranks',"professorRank","=","rankID")
    //         ->where(["loadProfessor"=>$request->input('professor'),"loadDepartment"=>$department,"loadSoftDelete"=>0])
    //         ->select("loadHour as hour")
    //         ->get();

    //         $professorCurrentHour = 0;
    //         foreach($totalProfessorHours as $total){
    //             $professorCurrentHour += $total['hour'];
    //         }

    //         $output = array();

    //         foreach($subjects as $subject){

    //             $totalSubjectHours = Load::where(["loadSubject"=>$subject['code'],"loadDepartment"=>$department,"loadSoftDelete"=>0])
    //             ->select("loadHour as hour")
    //             ->get();
                
    //             $subjectCurrentHour = 0;
    //             foreach($totalSubjectHours as $total){
    //                 $subjectCurrentHour = $subjectCurrentHour + $total['hour'];
    //             }

    //             $subjectMaxHour = ($subject['lec'] + $subject['lab']) * $totalSection['total'];

                

    //             $checkDesignated = Professor::join("ranks","professorRank","=","rankID")
    //             ->where(["professorID"=>$request->input('professor')])
    //             ->select('professorDesignation as designated', "rankHour as hour")
    //             ->first();

    //             $designatedHour = 0;
    //             if($checkDesignated->designated != 'none'){
    //                 $hours = Designation::where(['designationPosition'=>$checkDesignated->designated])
    //                 ->select("designationHour as hour")->first();
    //                 $designatedHour = $hours->hour;
    //             }
    //             else{
    //                 $designatedHour = $checkDesignated['hour'];
    //             }

    //             $loads = Load::join("professors","professorID","=","loadProfessor")
    //                 ->join('ranks',"professorRank","=","rankID")
    //                 ->where(['loadSubject'=>$subject['code'],"loadDepartment"=>$department,"loadSoftDelete"=>0])
    //                 ->select("loadID as id","loadHour as hour","professorID as professor","professorFullname as fullname")
    //                 ->get();

    //                 array_push($output,[
    //                     "code"=>$subject['code'],
    //                     "subject"=>$subject['subject'],
    //                     "professorCurrentHour"=>$professorCurrentHour,
    //                     "professorMaxHour"=>$designatedHour,
    //                     "subjectCurrentHour"=>$subjectCurrentHour,
    //                     "subjectMaxHour"=>$subjectMaxHour,
    //                     "loads"=>$loads,
    //                 ]);
    //             array_push($output,);
    //         }

    //         return response()->json($output);
    //     }
    //     catch(Exception $e){
    //         Log::error($e->getMessage());
    //     }
    // }

    protected function read_professor_details ($professor){
        try{

            $position = "";
            $hour = 0;

            $details = Professor::
            where(["professorID"=>$professor])
            ->join("ranks","professorRank","=","rankID")
            ->first();

            if($details['professorDesignation'] != "none"){
                $desig = Designation::where(['designationPosition'=>$details->professorDesignation])->first();
                $position = $desig->designationPosition;
                $hour = $desig->designationHour;
            }
            else{
                $position = $details->professorDesignation;
                $hour = $details->rankHour;
            }

            return response()->json([
                "professorID"=>$details->professorID,
                "fullname"=>$details->professorFullname,
                "rank"=>$details->rankName,
                "designated"=>$position,
                "hour"=>$hour
            ]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function create_load(Request $request){
        try{
            $validator = Validator::make($request->all(),[
                "year"=>"required",
                "semester"=>"required|in:1st semester,2nd semester",
                "professor"=>"required",
                "subject"=>"required",
                "occupied"=>"required",
                "tokens"=>"required",
            ]);

            if($validator->fails()){
                if($validator->errors()->has('occupied')){
                    $errorMesage = $validator->errors()->first('occupied');
                    return response(["status"=>false, "error"=>"occupied", "msg"=>$errorMesage]);
                }
            }
            $department = $this->tokenDepartment($request->input('tokens'));

            //check professor
            $check_professor = Load::where([
                "loadProfessor"=>$request->input('professor'),
                "loadSubject"=>$request->input('subject'),
                "loadSoftDelete"=>0
            ])->count();

            if($check_professor > 0){
                return response()->json(["status"=>false,"msg"=>"This professor is already have ".$request->input('subject')." subject"]);
            }
            
            $professorHour = 0;
            $details = Professor::
            where(["professorID"=>$request->input('professor')])
            ->join("ranks","professorRank","=","rankID")
            ->first();

            if($details['professorDesignation'] != "none"){
                $desig = Designation::where(['designationPosition'=>$details->professorDesignation])->first();
                $professorHour = $desig->designationHour;
            }
            else{
                $professorHour = $details->rankHour;
            }

            $loads = Load::where(["loadProfessor"=>$request->input('professor'),"loadSoftDelete"=>0])
            ->get();
            $usedLoadHour = 0;
            foreach($loads as $load){
                $usedLoadHour += $load['loadHour'];
            }

            if($usedLoadHour <= $professorHour){
                $totalSection = sections::where([
                    'sectionYearlevel'=>$request->input('year'),
                    'sectionSemester'=>$request->input('semester'),
                    'sectionDepartment'=>$department,
                ])
                ->select("sectionID as id","sectionSlot as total")
                ->first();
    
                $subjectHour = Subject::where(['subjectCode'=>$request->input('subject')])
                ->select("subjectLecHour as lec","subjectLabHour as lab")
                ->first();
    
                $totalSubjectHours = Load::where(["loadSubject"=>$request->input('subject'),"loadDepartment"=>$department,"loadSoftDelete"=>0])
                    ->select("loadHour as hour")
                    ->get();
                
                $myOccupied = 0;
                foreach($totalSubjectHours as $subject){
                    $myOccupied += $subject['hour'];
                }

                $needOccupied = ($subjectHour['lec'] + $subjectHour['lab']) * $totalSection['total'] ;

                $test = $myOccupied+($request->input('occupied')*($subjectHour['lec'] + $subjectHour['lab']));

                if($myOccupied < $needOccupied){
                    
                    if($myOccupied+($request->input('occupied')*($subjectHour['lec'] + $subjectHour['lab'])) <= $needOccupied){

                        $save = new Load();
                        $save->loadProfessor = $request->input('professor');
                        $save->loadSubject = $request->input('subject');
                        $save->loadHour =  ($subjectHour['lec']+$subjectHour['lab']) * $request->input('occupied');
                        $save->loadDepartment = $department;
                        $save->loadSoftDelete = 0;
                        $save->save();

                        return response()->json(["status"=>true,"msg"=>"Successfully"]);
                    }
                    else{
                        return response()->json(["status"=>false,"msg"=>"Please check the occupied in left side.","test"=>$test]);
                    }
                }
                else{
                    return response()->json(["status"=>false,"msg"=>"You reach maximum."]); 
                }
            }
            else{
                return response()->json(["status"=>false,"msg"=>"Professor reach maximum hour."]);
            }
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function delete_load($id){
        try{
            Load::where(['loadID'=>$id,"loadSoftDelete"=>0])
            ->update(['loadSoftDelete'=>1]);

            return response()->json(['status'=>true,"msg"=>"Successsfully Deleted"]);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function read_all_load(Request $request){
        try{
            $department = $this->tokenDepartment($request->input('tokens'));
            $year = $request->input('year');
            $semester = $request->input('semester');
            //department//year//semester

            $open_section = sections::where(['sectionSemester'=>$semester,"sectionYearlevel"=>$year,"sectionDepartment"=>$department,"sectionSoftDelete"=>0])
            ->select("sectionSlot as total_section")
            ->first();
           
            $subjects = Subject::where(["subjectSemester"=>$semester,'subjectDepartment'=>$department,"subjectSoftDelete"=>0])
            ->select("subjectCode as code", "subjectName as subject","subjectLecHour as lec","subjectLabHour as lab")
            ->get();

            $output = array();
            foreach($subjects as $subject){

                $subjectHours = ($subject['lec'] + $subject['lab']);

                $loads = Load::where(['loadSubject'=>$subject['code'],"loadSoftDelete"=>0])
                ->join('professors',"loadProfessor","=","professorID")
                ->select("loadID as id","professorFullname as professor","loadHour as hour")
                ->get();

                $getUsedTime = 0;
                if(count($loads)> 0){
                    foreach($loads as $load){
                        $getUsedTime += $load['hour'];
                    }
                }

                $loads_output = array();
                if(count($loads)> 0){
                    foreach($loads as $load){
                        $totalSection = ($load['hour']/$subjectHours);
                        array_push($loads_output,["id"=>$load['id'],"professor"=>$load['professor'],"section"=>$totalSection]);
                    }
                }

                $totalHours = ($getUsedTime/$subjectHours);
                array_push($output,[
                    "code"=>$subject['code'],
                    "subject"=>$subject['subject'],
                    "open_section"=>$open_section['total_section'],
                    "occupied"=>$totalHours,
                    "usedTime"=>$getUsedTime,
                    "loads"=>$loads_output]);
            }
            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function summary(Request $request){
        try{
            //tokens/semester
            $department = $this->tokenDepartment($request->input('tokens'));
            $years = YearLevel::where(['yearlevelDepartment'=>$department])
            ->select("yearlevelID as id","yearlevel as year")
            ->get();

            $output = array();
            if(count($years)>0){
                foreach($years as $year){
                    $total_section = sections::where([
                        'sectionSemester'=>$request->input('semester'),
                        "sectionYearlevel"=>$year['id'],
                        "sectionSoftDelete"=>0
                    ])
                    ->select("sectionSlot as slot")
                    ->first();

                    $subjects = Subject::where([
                        'subjectYearlevel'=>$year['id'],
                        "subjectSemester"=>$request->input('semester'),
                        "subjectDepartment"=>$department,
                        "subjectSoftDelete"=>0])
                        ->select('subjectCode as code',"subjectLecHour as lec","subjectLabHour as lab")
                        ->get();
                    
                    $data = array();
                    foreach($subjects as $subject){
                        $total_hours = ($subject['lec'] + $subject['lab']) * $total_section->slot;

                        $nameList = array();
                        $loads = Load::where(['loadSubject'=>$subject['code'],"loadSoftDelete"=>0])
                        ->join("professors","loadProfessor","=","professorID")
                        ->select("professorFullname as professor","loadHour as hour")
                        ->get();
                        foreach($loads as $load){
                            $details = $load['professor']."(".$load['hour'].")";
                            array_push($nameList,$details);
                        }

                        array_push($data,["code"=>$subject['code'],'total_section'=>$total_section->slot,"subject_hour"=>$total_hours,"namelist"=>$nameList]);
                    }

                    array_push($output,["year"=>$year['year'],"subjects"=>$data]);
                }
            }

            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }

    protected function professor_summary(Request $request){
        try{
            //tokens/semester
            $department = $this->tokenDepartment($request->input('tokens'));
            $professors = Professor::where(['professorDepartment'=>$department,"professorSoftDelete"=>0])
            ->get();

            $output = array();
            foreach($professors as $professor){

                $professorHour = 0;
                $details = Professor::
                where(["professorID"=>$professor['professorID']])
                ->join("ranks","professorRank","=","rankID")
                ->first();

                if($details['professorDesignation'] != "none"){
                    $desig = Designation::where(['designationPosition'=>$details->professorDesignation])->first();
                    $professorHour = $desig->designationHour;
                }
                else{
                    $professorHour = $details->rankHour;
                }

                $loads = Load::
                join("subjects","loadSubject","=","subjectCode")
                ->where(['loadProfessor'=>$professor['professorID'],"loadSoftDelete"=>0,"subjectSemester"=>$request->input('semester')])
                ->get();

                $arr_load = array();
                $total_load_hour = 0;
                if(count($loads) > 0){
                    foreach($loads as $load){

                        $subject = $load['loadSubject'];
                        $hour = $load['loadHour'];

                        $total_load_hour += $hour;

                        $subject = Subject::where(['subjectCode'=>$subject,"subjectSoftDelete"=>0])
                        ->select("subjectLecHour as lec","subjectLabHour as lab")
                        ->first();

                        $total_subject_hour = $subject->lec + $subject->lab;
                        $section = $hour/$total_subject_hour;

                        array_push($arr_load,[ "code"=>$load['loadSubject'], "loadHour"=>$hour,"givenSection"=>$section]);
                    }
                }
                array_push($output,["professor"=>$professor['professorFullname'],"rankHour"=>$professorHour,"total_hour"=>$total_load_hour,"subjects"=>$arr_load]);
            }

            return response()->json($output);
        }
        catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
