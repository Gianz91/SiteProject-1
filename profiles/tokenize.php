<?php 
    function tokenize($str, $token_symbols) {
    $word = strtok($str, $token_symbols);
    $queryfinal.= "WHERE id = '$word'";
    $word= strtok($token_symbols);
    while (false !== $word) {
        $queryfinal.=" OR id ='$word'";
        $word = strtok($token_symbols);
    }
    $queryfinal .=";";
    return $queryfinal;
}
function tokenizenames($str, $token_symbols) {
    $word = strtok($str, $token_symbols);
    $queryfinal.= "WHERE name = '$word'";
    $word= strtok($token_symbols);
    while (false !== $word) {
        $queryfinal.=" OR name ='$word'";
        $word = strtok($token_symbols);
    }
    $queryfinal .=";";
    return $queryfinal;
}
?>
