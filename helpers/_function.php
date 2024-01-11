<?php
function createHeaders($data)
{
    $keys = [];
    foreach ($data as $key => $value) {
        if (!is_array($value)) {
            if ($key == "name") {
                $keys[] = "first name";
                $keys[] = "last name";
            } elseif ($key == "dob") {
                $keys[] = "dob";
                $keys[] = "calculated age";
            } else {
                $keys[] = $key;
            }
        } else {
            if (array_key_first($value) === 0) {
                if (is_string($value[0])) {
                    $keys = array_merge($keys, [$key]);
                } else {
                    foreach ($value as $item) {
                        $keys = array_merge($keys, createHeaders($item));
                    }
                }
            } else {
                $keys = array_merge($keys, array_keys($value));
            }
        }
    }
    return $keys;
}

function createRows($data){
    $rows=[];
    for($i=0; $i<count($data); $i++){
        $subrow=[];
        foreach($data[$i] as $key=>$value){
            if ($key=='name'){
                $subrow=array_merge($subrow,getFirstAndLastName($value));
            }
            elseif($key=='dob'){
                $subrow=array_merge($subrow,calculateAge($value));
            }
            elseif($key=='subjects'){
                $subrow=array_merge($subrow,getSubjects($value));

            }
            elseif($key=='educations'){
                $subrow=array_merge($subrow,getEducations($value));

            }
            elseif($key=='residence'){
                $subrow=array_merge($subrow,getResidence($value));
            }
        }
        $rows[]=$subrow;

    }
    return $rows;

}

function createHeaders1($data, $mapping){
    $header=[];
    foreach($data as $key => $value){
        if(array_key_exists($key,$mapping)){
            $header=array_merge($header,$mapping[$key]);
        }
        else{
            $header[]=$key;
        }
    }
    return $header;
}

function getFirstAndLastName($name) {
    $name=explode(" ",$name);
    $firstname=$name[0];
    $lastname=$name[count($name)-1];
    return [$firstname,$lastname];
}

function calculateAge($birthDate){
     $birthDateTime = new DateTime($birthDate);
     $currentDate = new DateTime();
     $ageInterval = $currentDate->diff($birthDateTime);
     $age = $ageInterval->y;

     return [$birthDate,$age];
 }

function getSubjects($subjects) {
    $subjectsCount=count($subjects);
    $concatSubjects=implode(' | ',$subjects);
    $formatSubjects = sprintf('%u (%s)',$subjectsCount,$concatSubjects);
    return [$formatSubjects];

}

function getEducations($educations){
    
        $educationList=[];
        foreach($educations[0] as $key=>$value){
            $educationItem= array_column($educations,$key);
            print_r($educationItem);
            $educationList=array_merge($educationList,[$educationItem]);
    }
    $educationitems=array_map('implodeList',$educationList);
    
    return $educationitems;
}

function getResidence($residence){
    return array_values($residence);
}

function implodeList($educationArray){
    return implode(' | ',$educationArray);
}
?>


