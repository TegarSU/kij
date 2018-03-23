<html>
<head>
    <title>Classical Encrypted Algorithm</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Oleo+Script:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Teko:400,700" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <section id="contact">
        <div class="section-content">
            <h1 class="section-header">Playfair Cipher</h1>
        </div>
        <div class="contact-section">
            <div class="container">
                <div class="col-md-8">
                    Ciphertext or Plaintext:
                    <input class="form-control" type="string" id="pf_plaininput"><br>
                    Key:
                    <input class="form-control" type="string" id="pf_keyinput"><br>
                    OR
                    Input Text File:
                    <input type="file" id="fileinput" /><br>
                    <br><br>
                    <button class="btn btn-default submit" onclick="encrypt_playfair(document.getElementById('pf_plaininput').value,document.getElementById('pf_keyinput').value)">Encrypt</button>
                    <button class="btn btn-default submit" onclick="decrypt_playfair(document.getElementById('pf_plaininput').value,document.getElementById('pf_keyinput').value)">Decrypt</button>
                    <div id="PlayfairResult"></div>
                    <br><br><br>
                </div>
            </div>
        </div>
                    
    </section>
</body>
</html>
<script type="text/javascript">
    function readSingleFile(evt) {
        //Retrieve the first (and only!) File from the FileList object
        var f = evt.target.files[0];

        if (f) {
            var r = new FileReader();
            r.onload = function (e) {
                var contents = e.target.result;
                var buff = contents.split("\n");
                var ptext = buff[0];
                var keytext = buff[1];
            }
            r.readAsText(f);
        } else {
            alert("Failed to load file");
        }
    }

 

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
        document.getElementById('PlayfairResult').innerHTML = "<br><h3>"+"Ciphertext: "+ciphertext+"</h3><br>";
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
        document.getElementById('PlayfairResult').innerHTML = "<br><h3>"+"Plaintext = "+plaintext+"</h3><br>";
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

    document.getElementById('fileinput').addEventListener('change', readSingleFile, false);
 </script>
