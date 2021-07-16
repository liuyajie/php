<?php

class Persons
{
    public static $name = 'lyj';
    public static $age = 30;

    public static function setData(Student $student)
    {
        return Closure::bind(function () use ($student) {
            $student->name = Persons::$name;
            $student->age  = Persons::$age;
        }, null, Student::class);
    }
}

class Student
{
    private $name = '';
    private $age = 0;
    public function printInfo()
    {
        echo $this->name . '-' . $this->age;
    }
}

$student = new Student();
call_user_func(Persons::setData($student));
$student->printInfo();