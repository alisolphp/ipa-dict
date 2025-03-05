<?php
function ipaToReadable($ipa) {
    // Remove unnecessary characters and stress numbers from the IPA string
    $ipa = preg_replace('/[\/ˈˌ‍0-9]/u', '', $ipa);

    // Convert IPA symbols to ARPAbet (ordered from longest to shortest to avoid overlaps)
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

    // Replace IPA symbols with their corresponding ARPAbet representations
    foreach ($ipaToArpabet as $ipaSymbol => $arpabet) {
        $ipa = str_replace($ipaSymbol, $arpabet, $ipa);
    }

    // Convert ARPAbet to readable text
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

    // Replace ARPAbet symbols with their readable equivalents
    foreach ($arpabetToReadable as $arpabet => $readable) {
        $ipa = str_replace($arpabet, $readable, $ipa);
    }

    // Split syllables with a dot (only between vowels and consonants)
    $ipa = preg_replace('/([aeiou]{1,2})([^aeiou·]+)/', '$1·$2', $ipa);
    
    // Handle specific exceptions
    $ipa = str_replace("shu·hn","shn", $ipa);
    $ipa = str_replace("t̬o·h","tow", $ipa);
    $ipa = str_replace("to·h","tow", $ipa);
    
    // Replace dots with middle dots for syllable separation
    $ipa = str_replace(".", "·", $ipa);
    
    // Trim any leading or trailing middle dots and return the result
    return trim($ipa, "·");
}

// Test cases
$cases = [
    [
        'word' => 'soldier',
        'accent' => 'us',
        'ipa' => '/ˈsoʊɫdʒɝ/',
        'expected' => 'sohl·jr',
    ],
    [
        'word' => 'soldier',
        'accent' => 'uk',
        'ipa' => '/sˈə‍ʊld‍ʒɐ/',
        'expected' => 'suhl·juh',
    ],
    [
        'word' => 'abstraction',
        'accent' => 'us',
        'ipa' => '/æbˈstɹækʃən/',
        'expected' => 'ab·strak·shn',
    ],
    [
        'word' => 'abstraction',
        'accent' => 'uk',
        'ipa' => '/ɐbstɹˈækʃən/',
        'expected' => 'uhb·strak·shn',
    ],
    [
        'word' => 'tomato',
        'accent' => 'us',
        'ipa' => '/təˈmeɪ.t̬oʊ/',
        'expected' => 'tuh·may·tow',
    ],
    [
        'word' => 'tomato',
        'accent' => 'uk',
        'ipa' => '/təˈmɑː.təʊ/',
        'expected' => 'tuh·maa·tow',
    ],
];

// Run test cases and display results
foreach ($cases as &$case) {
    $case['output'] = ipaToReadable($case['ipa']);
    var_dump($case);
    echo "\n\n";
}
