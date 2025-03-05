<?php
function ipaToReadable($ipa) {
    // حذف کاراکترهای غیر ضروری و اعداد استرس
    $ipa = preg_replace('/[\/ˈˌ‍0-9]/u', '', $ipa);

    // تبدیل IPA به ARPAbet (ترتیب نزولی برای جلوگیری از تداخل)
    $ipaToArpabet = [
        "oʊ" => "OW", "əʊ" => "OW", "aʊ" => "AW", "aɪ" => "AY", 
        "eɪ" => "EY", "ɔɪ" => "OY", "ɑː" => "AA", "ɑ" => "AA",
        "ɝ" => "ER", "ɚ" => "AXR", "ʊ" => "UH", "ʌ" => "AH", 
        "ɐ" => "AH", "dʒ" => "JH", "tʃ" => "CH", "ʃ" => "SH",
        "ʒ" => "ZH", "ð" => "DH", "θ" => "TH", "ŋ" => "NG",
        "ɫ" => "L", "j" => "Y", "ɡ" => "G", "æ" => "AE",
        "ɛ" => "EH", "iː" => "IY", "uː" => "UW", "ɔ" => "AO",
        "ə" => "AX", "ɪ" => "IH", "ɾ" => "DX", "ʔ" => "Q",
        "h" => "HH", "s" => "S", "z" => "Z", "f" => "F",
        "v" => "V", "m" => "M", "n" => "N", "l" => "L",
        "r" => "R", "w" => "W", "p" => "P", "b" => "B",
        "t" => "T", "d" => "D", "k" => "K", "ɡ" => "G",
        "ɹ" => "r",
    ];

    foreach ($ipaToArpabet as $ipaSymbol => $arpabet) {
        $ipa = str_replace($ipaSymbol, $arpabet, $ipa);
    }

    // تبدیل ARPAbet به متن خوانا
    $arpabetToReadable = [
        "OW" => "oh", "AW" => "ow", "AY" => "ai", "EY" => "ey",
        "OY" => "oy", "AA" => "ah", "ER" => "er", "AXR" => "er",
        "UH" => "u", "AH" => "uh", "JH" => "j", "CH" => "ch",
        "SH" => "sh", "ZH" => "zh", "DH" => "th", "TH" => "th",
        "NG" => "ng", "Y" => "y", "G" => "g", "AE" => "a",
        "EH" => "e", "IY" => "ee", "UW" => "oo", "AO" => "aw",
        "AX" => "uh", "IH" => "i", "DX" => "d", "Q" => "",
        "HH" => "h", "S" => "s", "Z" => "z", "F" => "f",
        "V" => "v", "M" => "m", "N" => "n", "L" => "l",
        "R" => "r", "W" => "w", "P" => "p", "B" => "b",
        "T" => "t", "D" => "d", "K" => "k", "G" => "g",
    ];

    foreach ($arpabetToReadable as $arpabet => $readable) {
        $ipa = str_replace($arpabet, $readable, $ipa);
    }

    // تقسیم هجاها با نقطه (تنها بین حروف صدادار و بیصدا)
    $ipa = preg_replace('/([aeiou]{1,2})([^aeiou·]+)/', '$1·$2', $ipa);
    
    $ipa = str_replace("shu·hn","shn", $ipa);
    $ipa = str_replace("t̬o·h","tow", $ipa);
    $ipa = str_replace("to·h","tow", $ipa);
    
    $ipa = str_replace(".", "·", $ipa);
    
    return trim($ipa, "·");
}

$input = "en_US";

$s = file_get_contents($input.".txt");

$lines = explode("\n", $s);

foreach ($lines as $line) {
    list($word, $ipa) = explode("\t", $line);
    
    if(strpos($word, " ") !== false){
        continue;
    }
    
    $readable = ipaToReadable($ipa);
    
    // echo $word.'<br>';
    // echo $readable.'<br>';
    
    $newline = $word."\t".$readable."\n";
    
    $result .= $newline;
}

file_put_contents($input."_readable.txt", $result);
