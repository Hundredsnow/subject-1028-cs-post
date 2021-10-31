<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <title>PHP TEST</title>
</head>
<body>
<h3 class="alert alert-primary">掲示板</h3>
<div id="base" class="m-4">
    <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
        <input type="text" name="personal_name"><br><br>
        <textarea name="contents" rows="8" cols="40">
        </textarea><br><br>
        <input type="submit" name="btn1" value="投稿する">
    </form>
</div>
<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    writeData();
}

readData();

function readData(){
    $keijban_file = 'keijiban.txt';

    $fp = fopen($keijban_file, 'rb');

    if ($fp){
        if (flock($fp, LOCK_SH)){
            while (!feof($fp)) {
                $buffer = fgets($fp);
                print($buffer);
            }

            flock($fp, LOCK_UN);
        }
        else {
            print('ファイルロックに失敗しました');
        }
    }

    fclose($fp);
}

function writeData(){
    $personal_name = $_POST['personal_name'];
    $contents = $_POST['contents'];
    $contents = nl2br($contents);
    $now = date("Y/m/d H:i:s");

    $data = <<< DATA
<hr>
<p>投稿者:{$personal_name} : {$now}</p>
<p>内容:</p>
<p>{$contents}</p>
DATA;

    $keijban_file = 'keijiban.txt';

    $fp = fopen($keijban_file, 'ab');

    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp,  $data) === FALSE){
                print('ファイル書き込みに失敗しました');
            }

            flock($fp, LOCK_UN);
        }
        else {
            print('ファイルロックに失敗しました');
        }
    }

    fclose($fp);
}

?>
</body>
</html>
