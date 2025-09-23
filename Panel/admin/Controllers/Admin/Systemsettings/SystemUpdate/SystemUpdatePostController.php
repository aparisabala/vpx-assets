<?php

namespace App\Http\Controllers\Admin\Systemsettings\SystemUpdate;

use App\Http\Controllers\Controller;
use App\Traits\BaseTrait;
use App\Traits\Mig\BaseMigration;
use Illuminate\Http\Request;

class SystemUpdatePostController extends Controller
{
    use BaseTrait, BaseMigration;
    public function __construct()
    {
    }

    public function updateSystem(Request $request)
    {
        $data = [
            "hasTable" => "no",
            "nextTable" => "no",
            "updateCount" => 0,
        ];
        switch ($request->table) {
            case 'institute':
                $this->UpdateInstitute();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "examresult",
                    "updateCount" => 1,
                ];
                break;
            case 'examresult':
                $this->UpdateExamResult();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "examresultcodes",
                    "updateCount" => 2
                ];
                break;
            case 'examresultcodes':
                $this->UpdateExamResultCodes();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "globuser",
                    "updateCount" => 3
                ];
                break;
            case 'globuser':
                $this->UpdateGlobuser();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "class",
                    "updateCount" => 4
                ];
                break;
            case 'class':
                $this->UpdateClass();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "classdata",
                    "updateCount" => 5
                ];
                break;
            case 'classdata':
                $this->UpdateClassData();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "session",
                    "updateCount" => 6
                ];
                break;
            case 'session':
                $this->UpdateSession();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "teacher",
                    "updateCount" => 7
                ];
                break;
            case 'teacher':
                $this->UpdateTeacher();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "student",
                    "updateCount" => 8
                ];
                break;
            case 'student':
                $this->UpdateStudent();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "guardian",
                    "updateCount" => 9
                ];
                break;
            case 'guardian':
                $this->UpdateGuardian();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "dynamic",
                    "updateCount" => 10
                ];
                break;
            case 'dynamic':
                $this->UpdateDynamic();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "exam",
                    "updateCount" => 11
                ];
                break;
            case 'exam':
                $this->UpdateExam();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "examsubject",
                    "updateCount" => 12
                ];
                break;
            case 'examsubject':
                $this->UpdateExamSubject();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "examsubjectcodes",
                    "updateCount" => 13
                ];
                break;
            case 'examsubjectcodes':
                $this->UpdateExamSubjectCodes();
                $data = [
                    "hasTable" => "yes",
                    "nextTable" => "examsetup",
                    "updateCount" => 14
                ];
                break;
            case 'examsetup':
                $this->UpdateExamSetup();
                $data = [
                    "hasTable" => "no",
                    "nextTable" => "no",
                    "updateCount" => 15
                ];
                break;

            default:
                # code...
                break;
        }
        return $this->response(['type' => 'success', 'data' => $data]);
    }
}
