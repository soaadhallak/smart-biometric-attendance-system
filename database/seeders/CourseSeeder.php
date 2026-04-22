<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Course;
use App\Models\Major;
use Illuminate\Support\Str;


class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $software = Major::firstOrCreate(['name' => 'هندسة برمجيات']);
        $networks = Major::firstOrCreate(['name' => 'شبكات حاسوبية']);
        $maintenance = Major::firstOrCreate(['name' => 'صيانة']);

        $teachers = [];

        $getTeacher = function ($name) use (&$teachers) {

            if (!isset($teachers[$name])) {
                $user = User::firstOrCreate(
                    ['name' => $name],
                    [
                        'password' => bcrypt('password'),
                        'email' => Str::slug($name, '.') . '@example.com'
                    ]
                );

                if (!$user->hasRole('teacher')) {
                    $user->assignRole('teacher');
                }

                $teachers[$name] = $user;
            }

            return $teachers[$name];
        };

        $courses = [
            // هندسة برمجيات
            ['وسائط متعددة 2 نظري', ['نجوى'], $software],
            ['وسائط متعددة 2 عملي', ['نجوى'], $software],
            ['برمجة', ['نوران'], $software],
            ['تصميم مواقع', ['منال'], $software],
            ['نظام تشغيل عملي', ['نسرين'], $software],
            ['تقانة الانترنت عملي', ['نسرين'], $software],
            ['تقانة الانترنت نظري', ['نجوى'], $software],
            ['أمن معلومات نظري', ['مها'], $software],
            ['أمن معلومات عملي', ['مها'], $software],
            ['مخدمات ويب نظري', ['نوران'], $software],
            ['مخدمات ويب عملي', ['نوران'], $software],
            ['صيانة نظري', ['مهند'], $software],
            ['صيانة عملي', ['آية'], $software],
            ['مهارات عملي', ['لينا'], $software],
            ['مهارات نظري', ['نجوى'], $software],
            ['رياضيات', ['ايات'], $software],
            ['لغة', ['سلاف'], $software],

            // شبكات
            ['شبكات واسعة', ['نور كنعان'], $networks],
            ['تركيب شبكات', ['نور كنعان'], $networks],
            ['تصميم شبكات', ['ميرنا استانبولية'], $networks],
            ['بروتوكولات', ['اسماء'], $networks],
            ['نظم تشغيل شبكية 2', ['هشام سبحان'], $networks],
            ['مخدمات  نظري', ['ياسمين اسعد'], $networks],
            ['مخدمات ويب عملي', ['نوران'], $networks],
            ['بنية تجهيزات', ['ياسمين اسعد'], $networks],
            ['اسس اتصالات', ['اميرة دوبك'], $networks],
            ['ادراة', ['لينا هارون'], $networks],
            ['مهارات', ['سلوى'], $networks],
            ['رياضيات', ['رهف ابو عريضة'], $networks],
            ['لغة', ['شادي درويش'], $networks],
            ['بنية حاسوبية', ['اميرة'], $networks],
            ['برمجة متقدمة', ['ياسمين'], $networks],
            ['تصميم 1 نظري', ['نور كنعان'], $networks],
            ['تصميم 1 عملي', ['ميرنا استانبولية'], $networks],
            ['حماية', ['هشام سبحان'], $networks],
            ['امن نظري', ['ميرنا استانبولية'], $networks],
            ['امن عملي', ['ميرنا استانبولية'], $networks],
            ['اتصالات حديثة نظري', ['نور كنعان'], $networks],
            ['اتصالات حديثة عملي', ['نور كنعان'], $networks],
            ['تقانة الانترنت عملي', ['هديل عجم'], $networks],
            ['تقانة الانترنت نظري', ['هديل عجم'], $networks],
            ['صيانة حاسوب', ['ياسمين اسعد'], $networks],
            ['مهارات حاسوبية', ['لينا هارون'], $networks],
            ['عربي', ['اسراء قمري'], $networks],
            ['نظم تشغيل', ['اميرة دويك'], $networks],
            ['لغة', ['شادي'], $networks],
            ['رياضيات', ['رهف ابو عريضة'], $networks],



            // صيانة
            ['صيانة نظري', ['رنيم'], $maintenance],
            ['صيانة عملي', ['رنيم'], $maintenance],
            ['صيانة تجهيزات مكتبية', ['هشام'], $maintenance],
            ['PLC عملي', ['ميسة'], $maintenance],
            ['PLC نظري', ['ميسة'], $maintenance],
            ['برمجة متقدمة عملي', ['اسماء'], $maintenance],
            ['مهارات', ['سلوى'], $maintenance],
            ['رياضيات', ['ايات'], $maintenance],
            ['لغة', ['سلاف'], $maintenance],
            ['بنية حاسوبية', ['اميرة'], $maintenance],
            ['برمجة متقدمة نظري', ['عفراء'], $maintenance],
            ['ربط حاسوبي عملي', ['عفراء'], $maintenance],
            ['ربط حاسوبي نظري', ['عفراء'], $maintenance],
            ['لغة', ['شادي'], $maintenance],
            ['رياضيات', ['رهف'], $maintenance],

        ];

        foreach ($courses as [$name, $teacherNames, $major]) {

            $teacher = $getTeacher($teacherNames[0]);

            Course::firstOrCreate(
                [
                    'name'       => trim($name),
                    'major_id'   => $major->id,
                    'teacher_id' => $teacher->id,
                ]
            );
        }
    }
}
