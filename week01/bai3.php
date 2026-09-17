<?php

$students = [
    [
        "name" => "Nguyen Van An",
        "age" => 20,
        "score" => 8.5
    ],
    [
        "name" => "Tran Thi Binh",
        "age" => 21,
        "score" => 6.5
    ],
    [
        "name" => "Le Van Cuong",
        "age" => 19,
        "score" => 4.5
    ],
    [
        "name" => "Pham Thi Dung",
        "age" => 20,
        "score" => 7.5
    ]
];

function findBestStudent($students)
{
    $bestStudent = $students[0];
    foreach ($students as $student) {
        if ($student["score"] > $bestStudent["score"]) {
            $bestStudent = $student;
        }
    }
    return $bestStudent;
}

function findWorstStudent($students)
{
    $worstStudent = $students[0];
    foreach ($students as $student) {
        if ($student["score"] < $worstStudent["score"]) {
            $worstStudent = $student;
        }
    }
    return $worstStudent;
}

function countPassedStudents($students)
{
    $count = 0;
    foreach ($students as $student) {
        if ($student["score"] >= 5) {
            $count++;
        }
    }
    return $count;
}

function findStudentByName($students, $name)
{
    foreach ($students as $student) {
        if ($student["name"] === $name) {
            return $student;
        }
    }
    return null;
}

// Gọi function tìm sinh viên điểm cao nhất
$bestStudent = findBestStudent($students);
echo "Sinh viên có điểm cao nhất:<br>";
echo "Họ tên: " . $bestStudent["name"] . " ; ";
echo "Điểm: " . $bestStudent["score"] . "<hr>";


// Gọi function tìm sinh viên điểm thấp nhất
$worstStudent = findWorstStudent($students);
echo "Sinh viên có điểm thấp nhất:<br>";
echo "Họ tên: " . $worstStudent["name"] . " ; ";
echo "Điểm: " . $worstStudent["score"] . "<hr>";

// Gọi function đếm sinh viên đạt
$passedCount = countPassedStudents($students);
echo "Số sinh viên đạt (điểm >= 5) : " . $passedCount . "<hr>";

// Gọi function tìm sinh viên theo tên
$student = findStudentByName($students, "Tran Thi Binh");
if ($student !== null) {
    echo "Sinh viên tìm được:<br>";
    echo "Họ tên: " . $student["name"] . " ; ";
    echo "Tuổi: " . $student["age"] . " ; ";
    echo "Điểm: " . $student["score"];
} else {
    echo "Không tìm thấy sinh viên";
}