<?php


function add($num1, $num2) {
    return $num1 + $num2;
}


function subtract($num1, $num2) {
    return var_dump($num1 - $num2);
}


function multiply($num1, $num2) {
    return $num1 * $num2;
}


function divide($num1, $num2) {
    
        return $num1 / $num2;
}

$number1 = 10;
$number2 = 5;

echo "Sum: " . add($number1, $number2) . "\n";
echo "Difference: " . subtract($number1, $number2) . "\n";
echo "Product: " . multiply($number1, $number2) . "\n";
echo "Quotient: " . divide($number1, $number2) . "\n";

?>
