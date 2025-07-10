<?php
function createHeaders($data)
{
    $header = [];
    foreach ($data as $key => $value) {
        if (array_key_exists($key, MAPPING)) {
            $header = array_merge($header, MAPPING[$key]);
        } else {
            $header[] = $key;
        }
    }
    return $header;
}

function createRows($data)
{
    $rows = [];
    for ($i = 0; $i < count($data); $i++) {
        $subrow = [];
        foreach ($data[$i] as $key => $value) {
            if ($key == 'name') {
                $subrow = array_merge($subrow, getFirstAndLastName($value));
            } elseif ($key == 'dob') {
                $subrow = array_merge($subrow, calculateAge($value));
            } elseif ($key == 'subjects') {
                $subrow = array_merge($subrow, getSubjects($value));
            } elseif ($key == 'educations') {
                $subrow = array_merge($subrow, getEducations($value));
            } elseif ($key == 'residence') {
                $subrow = array_merge($subrow, getResidence($value));
            }
        }
        $rows[] = $subrow;
    }
    return $rows;
}



function getFirstAndLastName($name)
{
    $name_parts = explode(" ", $name);
    $first_name = $name_parts[0];
    $last_name = $name_parts[count($name_parts) - 1];
    return [$first_name, $last_name];
}

function calculateAge($birthDate)
{
    $birth_date_time = new DateTime($birthDate);
    $current_date = new DateTime();
    $age_interval = $current_date->diff($birth_date_time);
    $age = $age_interval->y;
    return [$birthDate, $age];
}

function getSubjects($subjects)
{
    $subjects_count = count($subjects);
    $concat_subjects = implode(' | ', $subjects);
    $format_subjects = sprintf('%u (%s)', $subjects_count, $concat_subjects);
    return [$format_subjects];
}

function getEducations($educations)
{
    $education_list = [];
    foreach ($educations[0] as $key => $value) {
        $education_item = array_column($educations, $key);
        print_r($education_item);
        $education_list = array_merge($education_list, [$education_item]);
    }
    $education_items = array_map('implodeList', $education_list);
    return $education_items;
}

function getResidence($residence)
{
    return array_values($residence);
}

function implodeList($educationArray)
{
    return implode(' | ', $educationArray);
}
?>
