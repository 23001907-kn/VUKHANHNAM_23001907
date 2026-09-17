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

function calculateAverageScore($students)
{
    $total_score = 0;

    foreach ($students as $student) {
        $total_score += $student["score"];
    }

    return $total_score / count($students);
}

function getRank($score)
{
    if ($score >= 8) {
        return "Giỏi";
    } elseif ($score >= 6.5) {
        return "Khá";
    } elseif ($score >= 5) {
        return "Trung bình";
    } else {
        return "Yếu";
    }
}

function displayStudent($student)
{
    echo "Họ tên: " . $student["name"] . " ; ";
    echo "Tuổi: " . $student["age"] . " ; ";
    echo "Điểm: " . $student["score"] . " ; ";
    echo "Xếp loại: " . getRank($student["score"]) . "<br>";
    echo "<hr>";
}


foreach ($students as $student) {
    displayStudent($student);
}
