<?php

class Student{
    public $name;
    public $age;
    public $score;

    public function __construct($name, $age, $score){
        $this->name = $name;
        $this->age = $age;
        $this->score = $score;
    }
    public function getRank()
    {
        if ($this->score >= 8) {
            return "Giỏi";
        } elseif ($this->score >= 6.5) {
            return "Khá";
        } elseif ($this->score >= 5) {
            return "Trung bình";
        } else {
            return "Yếu";
        }
    }
    public function isPassed()
    {
        return $this->score >= 5;
    }
    public function display()
    {
        echo "Họ tên: " . $this->name . " ; ";
        echo "Tuổi: " . $this->age . " ; ";
        echo "Điểm: " . $this->score . " ; ";
        echo "Xếp loại: " . $this->getRank() . " ; ";
        echo "Kết quả: " . ($this->isPassed() ? "Đạt" : "Không đạt") . "<br>";
        echo "<hr>";
    }
}

$student1 = new Student("Nguyen Van An", 20, 8.5);
$student2 = new Student("Tran Thi Binh", 21, 6.5);
$student3 = new Student("Le Van Cuong", 19, 4.5);
$student4 = new Student("Pham Thi Dung", 20, 7.5);

$students = [
    $student1,
    $student2,
    $student3,
    $student4
];

foreach ($students as $student) {
    $student->display();
}
