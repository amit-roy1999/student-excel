<?php

namespace App\Http\Controllers;

use App\Models\Marks;
use App\Models\User;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;
use OpenSpout\Common\Entity\Style\Style;

class StudentExcelController extends Controller
{
    public function export()
    {
        $users = User::with('marks')->get();

        $excel = [];
        foreach ($users as $user) {
            $student['name'] = $user->name;
            $student['rool_id'] = $user->rool_id;
            foreach ($user->marks as $marks) {
                $student[$marks->examination_date] = $marks->mark;
            }
            $excel[] = $student;
        }
        $list = collect($excel);

        $header_style = (new Style())->setFontBold();

        $rows_style = (new Style())
            ->setFontSize(15)
            ->setShouldWrapText()
            ->setBackgroundColor("EDEDED");

        return (new FastExcel($list))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->download('file.xlsx');
    }

    function import()
    {
        // $file = public_path('file.xlsx');
        $file = public_path('short.xlsx');
        $students = (new FastExcel)->import($file);
        // dd($students);
        foreach ($students as $student) {
            $sData = User::firstOrCreate(['rool_id' => $student['rool_id']], [
                'name' => $student['name'],
                'rool_id' => $student['rool_id']
            ]);
            // $markes = [];
            foreach (array_slice($student, 2) as $key => $value) {
                Marks::updateOrCreate(
                    [
                        'user_id' => $sData->id,
                        'examination_date' => $key,
                    ],
                    [
                        'user_id' => $sData->id,
                        'examination_date' => $key,
                        'mark' => $value,
                    ]
                );
                // $markes[] = [
                //     'user_id' => $sData->id,
                //     'mark' => $value,
                //     'examination_date' => $key,
                // ];
            }
        }

        return "done";
    }
}
