<?php
include "helpers/_function.php";
include "config/_mapping.php";
$data = [
    [
        "name" => "Ali Abbas", // first name and last name
        "dob" => "1986-08-23", // dob and Calculated Age
        "subjects" => ["English", "Urdu"], // Subjects 3 (English | Urdu)
        "educations" => [
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "BS in IT",
            ],
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "MS in IT",
            ],
        ], // Educations (Unversity Name, University Location)
        // AX | BX , Lahore | Karachi,  BS in IT| MS in IT
        "residence" => [
            "city" => "Lahore",
            "address" => "Harbhanspura",
        ], // Residence City , Residence Address
    ],
    [
        "name" => "Umer Ghaffar",
        "dob" => "1998-12-10",
        "subjects" => ["English", "Urdu", "Mathematics"],
        "educations" => [
            [
                "university" => "IUB",
                "location" => "Bahawalnagar",
                "degree" => "BSCS",
            ],
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "BS in IT",
            ],
        ],
        "residence" => [
            "city" => "Bahawalnagar",
            "address" => "khadimabad colony",
        ],
    ],
    [
        "name" => "Mehar Ali Ghaffar",
        "dob" => "2000-12-10",
        "subjects" => ["Computer Science", "Mathematics", "Urdu"],
        "educations" => [
            [
                "university" => "Comsats",
                "location" => "Lahore",
                "degree" => "BSCS",
            ],
            [
                "university" => "AX",
                "location" => "Islamabad",
                "degree" => "BS in IT",
            ],
        ],
        "residence" => [
            "city" => "Bahawalnagar",
            "address" => "Khadimabad colony",
        ],
    ],
    [
        "name" => "Taha Munawar", // if any name same then last index of same name should be in csv
        "dob" => "2001-12-10",
        "subjects" => ["Computer Science", "Mathematics"],
        "educations" => [
            [
                "university" => "Okara",
                "location" => "Okara",
                "degree" => "BBA",
            ],
            [
                "university" => "BX",
                "location" => "Lodhran",
                "degree" => "BS in Science",
            ],
        ],
        "residence" => [
            "city" => "Okara",
            "address" => "Shadab colony",
        ],
    ],
    [
        "name" => "Taha Munawar",
        "dob" => "2001-12-10",
        "subjects" => ["Computer Science", "Mathematics"],
        "educations" => [
            [
                "university" => "Okara",
                "location" => "Okara",
                "degree" => "BBA",
            ],
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "MS in IT",
            ],
        ],
        "residence" => [
            "city" => "Okara",
            "address" => "Shadab colony",
        ],
    ],
    [
        "name" => "Abu Huraira",
        "dob" => "1998-06-05",
        "subjects" => ["Urdu", "Mathematics"],
        "educations" => [
            [
                "university" => "LUMS",
                "location" => "Lahore",
                "degree" => "BSCS",
            ],
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "MS in IT",
            ],
        ],
        "residence" => [
            "city" => "Faisalabad",
            "address" => "Butt colony",
        ],
    ],
    [
        "name" => "Ahmad Nisar",
        "dob" => "1999-02-01",
        "subjects" => ["Urdu", "Mathematics", "Arts"],
        "educations" => [
            [
                "university" => "UCP",
                "location" => "Lahore",
                "degree" => "BSCS",
            ],
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "MS in IT",
            ],
        ],
        "residence" => [
            "city" => "Okara",
            "address" => "Deepalpur",
        ],
    ],
    [
        "name" => "Kamran Ali",
        "dob" => "2000-12-01",
        "subjects" => ["Urdu", "Arts"],
        "educations" => [
            ["university" => "Leads",
            "location" => "Lahore",
            "degree" => "BSSE",]
        ],
        "residence" => [
            "city" => "Okara",
            "address" => "Deepalpur",
        ],
    ],
    [
        "name" => "Ali Ahmad",
        "dob" => "1999-02-01",
        "subjects" => ["Urdu", "Mathematics" , "Arts"],
        "educations" => [
            [
                "university" => "USA",
                "location" => "Lahore",
                "degree" => "BSCS",
            ],
        ],
        "residence" => [
            "city" => "Okara",
            "address" => "Lodhran",
        ],
    ],
    [
        "name" => "Ali Shan",
        "dob" => "1999-02-01",
        "subjects" => ["Urdu", "Mathematics", "Arts"],
        "educations" => [
            [
                "university" => "UCP",
                "location" => "Lahore",
                "degree" => "BSCS",
            ],
            [
                "university" => "AX",
                "location" => "Lahore",
                "degree" => "MS in IT",
            ],
        ],
        "residence" => [
            "city" => "Okara",
            "address" => "Shah Colony",
        ],
    ],
];
$csv_header = createHeaders1($data[0]);
$csv_rows = createRows($data);
$array_2d=array_merge([$csv_header],$csv_rows);
$fp = fopen("persons4.csv", "w");
print_r($array_2d);
foreach ($array_2d as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
?>





