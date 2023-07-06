<?php
    $lang = ["en", "zh"];
    $resp = [["thanks for your message", "感谢留言"], ["sorry, message send failure", "抱歉，留言失败"]];
    $love_menyi_forever = ["Love Menyi Forever!", "永远爱门弋！"];
    $menyi_is_the_love_of_my_life = ["Menyi is the Love of My Life!", "门弋是我一生的挚爱！"];
    $leave_a_message = ["leave a message", "留言"];
    $the_other_link = ["链接", "link"];
    $remote_domain = ["门弋.我爱你", "menyi.love"];
    $message_str = ["message", "留言"];
    $cancel_str = ["cancel", "返回"];
    $send_str = ["send", "发送"];
    $hostname_str = ["menyi.love", "门弋.我爱你"];
    if($_SERVER['HTTP_HOST'] == "menyi.love") $index = 0;
    else if($_SERVER['HTTP_HOST'] == "xn--s0t343j.xn--6qq986b3xl") $index = 1;

    if(isset($_POST['msg'])){
        $message = is_numeric($_POST['msg']) ? $_POST['msg'] : str_replace("@", "#", get_str($_POST['msg']));
        $date_insert = date('Y-m-d H:i:s', time());

        class MyDB extends SQLite3
        {
           function __construct()
           {
              $this->open('../sqlite/msg.db');
           }
        }
        $db = new MyDB();
        if(!$db){
           echo "<script>console.log(\""."连接数据库失败。"."\")</script>";
           echo "<script>console.log(\"".$db->lastErrorMsg()."\")</script>";
        } else {
           echo "<script>console.log(\""."连接数据库成功。<br>"."\")</script>";
        }

        $checkTable_sql = <<<EOF
            select count(*) as uds_count from sqlite_master where type='table' and name = 'msg';
        EOF;

        $check_result = $db->query($checkTable_sql);
        if(!$check_result){
            echo "<script>console.log(\""."表格查询失败。<br>"."\")</script>";
            echo "<script>console.log(\"".$db->lastErrorMsg()."\")</script>";
        }else{
            $count = 0;
            while($row = $check_result->fetchArray(SQLITE3_ASSOC) ){
                $count = $row['uds_count'];
            }
            if($count>0){
                echo "<script>console.log(\""."表格已经创建。<br>"."\")</script>";
            }else{
                $create_sql =<<<EOF
                    create table if not exists msg(
                        id          integer primary key unique,
                        hostname    text    not null,
                        message     text    not null,
                        date_insert text    not null
                    );
                EOF;
                $create_result = $db->exec($create_sql);
                if(!$create_result){
                    echo "<script>console.log(\""."表格创建失败。<br>"."\")</script>";
                    echo "<script>console.log(\"".$db->lastErrorMsg()."\")</script>";
                }else{
                    echo "<script>console.log(\""."表格创建成功。<br>"."\")</script>";
                }
            }
        }
        $hostname_str_sql = $hostname_str[$index];
        $insert_sql =<<<EOF
            insert into msg(hostname, message, date_insert)
            values('{$hostname_str_sql}', '{$message}', '{$date_insert}');
        EOF;

        $insert_result = $db->exec($insert_sql);
        if(!$insert_result){
            echo "<script>console.log(\""."插入数据失败。<br>"."\")</script>";
            echo "<script>console.log(\"".$db->lastErrorMsg()."\")</script>";
            $resp_index = 1;
        }else{
            echo "<script>console.log(\""."插入数据成功。<br>"."\")</script>";
            $resp_index = 0;
        }

        $db->close();

	echo "<script>alert(\"".$resp[$resp_index][$index]."\");location.replace(document.referrer);</script>";
    }

    function get_str($string)
    {
        if (!get_magic_quotes_gpc()) {
            return addslashes($string);
        }
        return $string;
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $lang[$index]; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <title><?php echo $love_menyi_forever[$index]; ?></title>
</head>
<body style="color: #F596AA; text-align: center;">
<?php
    echo "Menyi ist die Liebe von Meinem Leben.";
    echo "<br>";
    echo "<h1>".$menyi_is_the_love_of_my_life[$index]."</h1>";
    echo "<br>";
    echo "<table style=\"margin: 0 auto;\"><tr><td style=\"text-align: right;\">".$leave_a_message[$index].": </td><td style=\"text-align: left;\"><a href=\"javascript: void(0)\" onclick=\"msg_box();\" style=\"color: #F596AA;\">✉</a></td></tr>";
    echo "<tr><td style=\"text-align: right;\">".$the_other_link[$index].": </td><td style=\"text-align: left;\"><a href=\"http://".$remote_domain[$index]."\" style=\"color: #F596AA;\">".$remote_domain[$index]."</a></td></tr></table>";
?>
    <div id="container" style="display: flex; justify-content: center;">
        <div id="hidden" style="display: none; width: 100%; height: 100%; position: fixed; top: 0; left: 0;background-color: #000000; opacity: 0.3;"></div>
        <div id="box" style="display: none;width: 400px; height: 200px; background-color: #F596AA; align-items: center; justify-content: center; flex-direction: column; position: absolute; border-radius: 5px; box-sizing: border-box;">
            <form id="form" action="" method="post" style="margin-block-end: 0;">
            <table>
                <tr>
                    <td style="text-align: right;vertical-align: top; color: #fff"><?php echo $message_str[$index]; ?>:</td>
                    <td style="text-align: left;"><textarea name="msg" id="msg" rows="5" style="width: 200px; resize: none; border: 1px solid #FFF; outline: none; color: #FFF; font-family: Arial; background-color: #F596AA;"></textarea></td>
                </tr>
            </table>
            </form>
            <table style="table-layout: fixed; width: 100%;">
                <tr>
                    <td></td>
                    <td><a href="javascript:void(0)" onclick="close_msg_box()" style="color: #fff"><?php echo $cancel_str[$index]; ?></a></td>
                    <td></td>
                    <td><a href="javascript:void(0)" style="color: #fff" onclick="form_send()"><?php echo $send_str[$index]; ?></a></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
</body>
<script>
    var hidden = document.getElementById("hidden");
    var box = document.getElementById("box");
    function msg_box(){
        hidden.style.display = "block";
        box.style.display = "flex";
    }
    function close_msg_box(){
        hidden.style.display = "none";
        box.style.display = "none";
        form_reset();
    }
    function form_send(){
        document.getElementById("form").submit();
        form_reset();
    }
    function form_reset(){
        document.getElementById("form").reset();
    }
</script>
</html>
