<?php

////////////////////////////////////////////////////Get Value//////////////////////////////////////////////////////////////////

	$pil=$_POST["pilihan"];
	$text=$_POST["inputt"];
	$med=$_POST["pilihan2"];
	$kunci=$_POST["inputttt"];

////////////////////////////////////////////////Caesar Chiper//////////////////////////////////////////////////////////////////

function Ciphercaesar($ch,$key1)
{
	if (!ctype_alpha($ch))
		return $ch;

	$offset = ord(ctype_upper($ch) ? 'A' : 'a');
	if ($key1<0){
		$key1 = abs($key1+26);
	}
	return chr(fmod(((ord($ch) + $key1) - $offset), 26) + $offset);
}

function Enciphercaesar($input,$key1)
{
	$output = "";

	$inputArr = str_split($input);
	foreach ($inputArr as $ch)
		$output .= Ciphercaesar($ch, $key1);

	return $output;
}

function Deciphercaesar($input,$key1)
{
	return Enciphercaesar($input, 26 - $key1);
}

////////////////////////////////////////////////////poly chiper////////////////////////////////////////////////////////////////

									
function Modpoly($a, $b)
{
	return ($a % $b + $b) % $b;
}

function Cipherpoly($input, $key, $encipher)
{
	$keyLen = strlen($key);

	for ($i = 0; $i < $keyLen; ++$i)
		if (!ctype_alpha($key[$i]))
			return ""; // Error

	$output = "";
	$nonAlphaCharCount = 0;
	$inputLen = strlen($input);

	for ($i = 0; $i < $inputLen; ++$i)
	{
		if (ctype_alpha($input[$i]))
		{
			$cIsUpper = ctype_upper($input[$i]);
			$offset = ord($cIsUpper ? 'A' : 'a');
			$keyIndex = ($i - $nonAlphaCharCount) % $keyLen;
			$k = ord($cIsUpper ? strtoupper($key[$keyIndex]) : strtolower($key[$keyIndex])) - $offset;
			$k = $encipher ? $k : -$k;
			$ch = chr((Modpoly(((ord($input[$i]) + $k) - $offset), 26)) + $offset);
			$output .= $ch;
		}
		else
		{
			$output .= $input[$i];
			++$nonAlphaCharCount;
		}
	}

	return $output;
}

function Encipherpoly($input, $key)
{
	return Cipherpoly($input, $key, true);
}

function Decipherpoly($input, $key)
{
	return Cipherpoly($input, $key, false);
}
?>
////////////////////////////////////////////////playfair chiper////////////////////////////////////////////////////////////////

<script type="text/javascript">
	function encrypt_playfair(plaintext,key){
        var key_matrix = playfair_key(key);
        var ciphertext = "";
        
        for(var i=0; i<plaintext.length; i+=2)
        {
            if(plaintext[i] == ' '){
                i-=1;
                continue;
            }
            var ch1 = plaintext[i];
            if(i+1 == plaintext.length){
                plaintext += 'x';
            }
            if(plaintext[i+1] == ' '){
                i+=1;
                var ch2 = plaintext[i+1];
            }
            else{
                var ch2 = plaintext[i+1];
            }

            if(ch1==ch2){
                ch2 = 'x';
                i-=1;
            } 
            

            for(var j=0; j<5; j++)
            {
                for(var k=0; k<5; k++)
                {
                    if(key_matrix[j][k] == ch1)
                    {
                        var ch1_x = k;
                        var ch1_y = j;
                    }

                    if(key_matrix[j][k] == ch2)
                    {
                        var ch2_x = k;
                        var ch2_y = j;
                    }
                }
            }

            if(ch1_x == ch2_x)
            {
                ciphertext += key_matrix[(ch1_y+1)%5][ch1_x];
                ciphertext += key_matrix[(ch2_y+1)%5][ch2_x];
            }
            else if(ch1_y == ch2_y)
            {
                ciphertext += key_matrix[ch1_y][(ch1_x+1)%5];
                ciphertext += key_matrix[ch2_y][(ch2_x+1)%5];
            }
            else
            {
                 ciphertext += key_matrix[ch1_y][ch2_x];
                 ciphertext += key_matrix[ch2_y][ch1_x];
            }

        }
        document.getElementById('PlayfairResult').innerHTML = "<br>"+"Ciphertext: "+ciphertext+"<br>";
    }

    function decrypt_playfair(ciphertext, key){
        var key_matrix = playfair_key(key);
        var plaintext = "";
        
        for(var i=0; i<ciphertext.length; i+=2)
        {
            if(ciphertext[i] == ' '){
                i-=1;
                continue;
            }
            var ch1 = ciphertext[i];
            if(ciphertext[i+1] == ' '){
                i+=1;
                var ch2 = ciphertext[i+1];
            }
            else{
                var ch2 = ciphertext[i+1];
            }
            

            for(var j=0; j<5; j++)
            {
                for(var k=0; k<5; k++)
                {
                    if(key_matrix[j][k] == ch1)
                    {
                        var ch1_x = k;
                        var ch1_y = j;
                    }

                    if(key_matrix[j][k] == ch2)
                    {
                        var ch2_x = k;
                        var ch2_y = j;
                    }
                }
            }

            if(ch1_x == ch2_x)
            {
                var pl1 = key_matrix[(ch1_y-1)%5][ch1_x];
                var pl2 = key_matrix[(ch2_y-1)%5][ch2_x];
                if(pl1 != 'x'){
                    plaintext += pl1;
                }
                if(pl2 != 'x'){
                    plaintext += pl2;
                }
            }
            else if(ch1_y == ch2_y)
            {
                var pl1 = key_matrix[ch1_y][(ch1_x-1)%5];
                var pl2 = key_matrix[ch2_y][(ch2_x-1)%5];
                if(pl1 != 'x'){
                    plaintext += pl1;
                }
                if(pl2 != 'x'){
                    plaintext += pl2;
                }
            }
            else
            {
                var pl1 = key_matrix[ch1_y][ch2_x];
                var pl2 = key_matrix[ch2_y][ch1_x];
                if(pl1 != 'x'){
                    plaintext += pl1;
                }
                if(pl2 != 'x'){
                    plaintext += pl2;
                }
            }

        }
        document.getElementById('PlayfairResult').innerHTML = "<br>"+"Plaintext = "+plaintext+"<br>";
    }

    function playfair_key(key){
        var key_matrix = [];
        for(var i=0; i<5; i++) {
            key_matrix[i] = [];
            for(var j=0; j<5; j++) {
                key_matrix[i][j] = undefined;
            }
        }

        if(key.length > 25)
        {
            key = key.substring(0,25);
        }
        var breakMatrix = false;
        var filler = "abcdefghiklmnopqrstuvwxyz"

        for(var i = 0; i < key.length; i++)
        {
            for(var j = 0; j < 5; j++)
            {
                for(var k = 0; k < 5; k++)
                {
                    if(key[i] == " ")
                    {
                        breakMatrix = true;
                        break;
                    }
                    if(key[i] == key_matrix[j][k])
                    {
                        breakMatrix = true;
                        break;
                    }
                    if(key_matrix[j][k] == undefined)
                    {
                        key_matrix[j][k] = key[i];
                        breakMatrix = true;
                        break;
                    }
                }
                if(breakMatrix)
                {
                    breakMatrix = false;
                    break;
                }
            }
        }

        for(var i = 0; i < filler.length; i++)
        {
            for(var j = 0; j < 5; j++)
            {
                for(var k = 0; k < 5; k++)
                {
                    if(filler[i] == " ")
                    {
                        breakMatrix = true;
                        break;
                    }
                    if(filler[i] == key_matrix[j][k])
                    {
                        breakMatrix = true;
                        break;
                    }
                    if(key_matrix[j][k] == undefined)
                    {
                        key_matrix[j][k] = filler[i];
                        breakMatrix = true;
                        break;
                    }
                    if(key_matrix[j][k] == 'j' && filler=='i')
                    {
                        breakMatrix = true;
                        break;
                    }
                }
                if(breakMatrix)
                {
                    breakMatrix = false;
                    break;
                }
            }
        }

        return key_matrix;

    }
</script>


////////////////////////////////////////////////transpositon Chiper///////////////////////////////////////////////////////////
	
<?php										
class KeyValuePair
{
	public $Key;
	public $Value;
}

function compare($first, $second) {
	return strcmp($first->Value, $second->Value);
}

function GetShiftIndexes($key)
{
	$keyLength = strlen($key);
	$indexes = array();
	$sortedKey = array();
	$i;

	for ($i = 0; $i < $keyLength; ++$i) {
		$pair = new KeyValuePair();
		$pair->Key = $i;
		$pair->Value = $key[$i];
		$sortedKey[] = $pair;
	}

	usort($sortedKey, 'compare');
	$i = 0;

	for ($i = 0; $i < $keyLength; ++$i)
		$indexes[$sortedKey[$i]->Key] = $i;

	return $indexes;
}

function Enciphertrans($input, $key, $padChar)
{
	$output = "";
	$totalChars = strlen($input);
	$keyLength = strlen($key);
	$input = ($totalChars % $keyLength == 0) ? $input : str_pad($input, $totalChars - ($totalChars % $keyLength) + $keyLength, $padChar, STR_PAD_RIGHT);
	$totalChars = strlen($input);
	$totalColumns = $keyLength;
	$totalRows = ceil($totalChars / $totalColumns);
	$rowChars = array(array());
	$colChars = array(array());
	$sortedColChars = array(array());
	$currentRow = 0; $currentColumn = 0; $i = 0; $j = 0;
	$shiftIndexes = GetShiftIndexes($key);

	for ($i = 0; $i < $totalChars; ++$i)
	{
		$currentRow = $i / $totalColumns;
		$currentColumn = $i % $totalColumns;
		$rowChars[$currentRow][$currentColumn] = $input[$i];
	}

	for ($i = 0; $i < $totalRows; ++$i)
		for ($j = 0; $j < $totalColumns; ++$j)
			$colChars[$j][$i] = $rowChars[$i][$j];

	for ($i = 0; $i < $totalColumns; ++$i)
		for ($j = 0; $j < $totalRows; ++$j)
			$sortedColChars[$shiftIndexes[$i]][$j] = $colChars[$i][$j];

	for ($i = 0; $i < $totalChars; ++$i)
	{
		$currentRow = $i / $totalRows;
		$currentColumn = $i % $totalRows;
		$output .= $sortedColChars[$currentRow][$currentColumn];
	}

	return $output;
}

function Deciphertrans($input, $key)
{
	$output = "";
	$keyLength = strlen($key);
	$totalChars = strlen($input);
	$totalColumns = ceil($totalChars / $keyLength);
	$totalRows = $keyLength;
	$rowChars = array(array());
	$colChars = array(array());
	$unsortedColChars = array(array());
	$currentRow = 0; $currentColumn = 0; $i = 0; $j = 0;
	$shiftIndexes = GetShiftIndexes($key);

	for ($i = 0; $i < $totalChars; ++$i)
	{
		$currentRow = $i / $totalColumns;
		$currentColumn = $i % $totalColumns;
		$rowChars[$currentRow][$currentColumn] = $input[$i];
	}

	for ($i = 0; $i < $totalRows; ++$i)
		for ($j = 0; $j < $totalColumns; ++$j)
			$colChars[$j][$i] = $rowChars[$i][$j];

	for ($i = 0; $i < $totalColumns; ++$i)
		for ($j = 0; $j < $totalRows; ++$j)
			$unsortedColChars[$i][$j] = $colChars[$i][$shiftIndexes[$j]];

	for ($i = 0; $i < $totalChars; ++$i)
	{
		$currentRow = $i / $totalRows;
		$currentColumn = $i % $totalRows;
		$output .= $unsortedColChars[$currentRow][$currentColumn];
	}

	return $output;
}

////////////////////////////////////////////////////Mono Chiper////////////////////////////////////////////////////////////////
									
function Ciphermono($input, $oldAlphabet, $newAlphabet, &$output)
{
	$output = "";
	$inputLen = strlen($input);

	if (strlen($oldAlphabet) != strlen($newAlphabet))
		return false;

	for ($i = 0; $i < $inputLen; ++$i)
	{
		$oldCharIndex = strpos($oldAlphabet, strtolower($input[$i]));

		if ($oldCharIndex !== false)
			$output .= ctype_upper($input[$i]) ? strtoupper($newAlphabet[$oldCharIndex]) : $newAlphabet[$oldCharIndex];
		else
			$output .= $input[$i];
	}

	return true;
}

function Enciphermono($input, $cipherAlphabet, &$output)
{
	$plainAlphabet = "abcdefghijklmnopqrstuvwxyz";
	//echo $input;
	//echo $cipherAlphabet;
	return Ciphermono($input, $plainAlphabet, $cipherAlphabet, $output);
}

function Deciphermono($input, $cipherAlphabet, &$output)
{
	$plainAlphabet = "abcdefghijklmnopqrstuvwxyz";
	return Ciphermono($input, $cipherAlphabet, $plainAlphabet, $output);
}
		


///////////////////////////////////////////////////////logic///////////////////////////////////////////////////////////////////

	if($med=='enk')
	{
		if($pil=="Caesar")
		{
			$enc=Enciphercaesar($text,$kunci);
			echo $enc;
		}
		elseif($pil=="Mono")
		{
			//$key="cyaezndfvpghjmbxoqsukwirtl";
			$chipertext;
			Enciphermono($text, $kunci, $chipertext);
			echo $chipertext;

			//echo $enc;
		}
		elseif($pil=="Playfair")
		{
			enplayfair('playfair.php', $kunci, $text);
		}
		elseif($pil=="Poly")
		{
			$enc = Encipherpoly($text, $kunci);
			echo $enc;
		}
		elseif($pil=="Transpositioni")
		{
			//$key="karimun";
			$enc = Enciphertrans($text, $kunci, '-');
			echo $enc;
		}
	}

	else
	{
		if($pil=="Caesar")
		{
			$dec=Deciphercaesar($text,$kunci);
			echo $dec;
		}
		elseif($pil=="Mono")
		{
			//$key="cyaezndfvpghjmbxoqsukwirtl";
			$plaintext;
			Deciphermono($text, $kunci, $plaintext);
			echo $plaintext;
		}
		elseif($pil=="Playfair")
		{
			deplayfair('unplayfair.php', $kunci, $text);
		}
		elseif($pil=="Poly")
		{
			$dec = Decipherpoly($text, $kunci);
			echo $dec;
		}
		elseif($pil=="Transpositioni")
		{
			//$key="karimun";
			$dec = Deciphertrans($text, $kunci);
			echo $dec;
		}
	}
?>