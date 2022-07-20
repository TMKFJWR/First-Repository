<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>

    <?php
    
    // 【サンプル】
    // ・データベース名：データベース名
    // ・ユーザー名：ユーザー名
    // ・パスワード：パスワード
    // の学生の場合：

    // DB接続設定
    $dsn = 'ʼデータベース名';//データベースに接続するために必要な情報
    $user = 'ユーザー名';//ユーザー名
    $password = 'パスワード';//パスワード
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    // 【！この SQLは tbtest テーブルを削除します！】
    //    $sql = 'DROP TABLE tbtest';
    //    $stmt = $pdo->query($sql);
    
    //テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest" //もしまだこのテーブルが存在しないなら」と
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY," //自動で登録されているナンバリング
    . "name char(32)," //名前
    . "comment TEXT," //コメント
    . "date TEXT," //日付・時間
    . "password TEXT" //パスワード
    .");";
    $stmt = $pdo->query($sql);


    
    //削除番号が送信された場合
    if(!empty($_POST["num"]) && !empty($_POST["delpass"])){
        
        $delete = $_POST["num"];
        $delpass = $_POST["delpass"];
        
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        foreach ($results as $row){
            
            if ($row["id"] == $delete && $delpass == $row["password"]){
                $id = $row["id"];
                $sql = 'delete from tbtest where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            
            }
        
        }    
    
    }
    
    //編集対象番号が送信された場合
    if(!empty($_POST["hiddennum"]) && !empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
        
        $editnum = $_POST["hiddennum"];
        $pass = $_POST["pass"];
        
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        foreach ($results as $row){
            
            if ($row["id"] == $editnum && $pass == $row["password"]){
                
                $id = $row["id"];
                $name = $_POST["name"];
                $comment = $_POST["str"];
                $date = date("Y年m月d日 H:i:s");
                $sql = 'UPDATE tbtest SET name=:name,comment=:comment ,date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
                
            }

        }
    }

    
    //名前とコメントが送信された場合
    elseif(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
        
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
        $name = $_POST["name"];
        $str = $_POST["str"];
        $date = date("Y年m月d日 H:i:s");
        $pass = $_POST["pass"];
        $sql -> execute();
        
    }    
    
    ?><!--php書き込み終わり-->
    

    <!--名前のフォーム-->
    <form action="" method="post" >
        <input type="text" name="name" placeholder="名前" value="<?php 
        if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
            
            $editnum = $_POST["editnum"];
            $editpass= $_POST["editpass"];
    
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){

                //編集番号と行番号が一致
                if ($row["id"]==$editnum && $row["password"]==$editpass){
                
                echo $row["name"] ;
                }
            }
        }?>"><br>
        
    <!--コメントのフォーム-->    
    <input type="text" name="str" placeholder="コメント" value="<?php 
        if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
            
            $editnum = $_POST["editnum"];
            $editpass= $_POST["editpass"];
    
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){

                //編集番号と行番号が一致
                if ($row["id"]==$editnum && $row["password"]==$editpass){
                
                echo $row["comment"] ;
                }
            }
        }?>"><br>
        
        <input type="text" name="pass" placeholder="パスワード" ><br>
        
        <!--見た目には見えていない編集番号のフォーム-->
        <input type="hidden" name="hiddennum" value="<?php
        if(!empty($_POST["editnum"]) && !empty($_POST["editpass"])){
            
            $editnum = $_POST["editnum"];
            $editpass= $_POST["editpass"];
    
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){

                //編集番号と行番号が一致
                if ($row["id"]==$editnum && $row["password"]==$editpass){
                
                echo $row["id"] ;
                }
            }
        }?>">
        
        <input type="submit" name="submit"><br><br>
    </form>
    
    <!--削除番号のフォーム-->
    <form action="" method="post" >
        <input type="number" name="num" placeholder="削除対象番号"><br>
        <input type="text" name="delpass"  placeholder="パスワード" ><br>
        <input type="submit" name="submit" value="削除"><br><br>
    </form>
    
    <!--編集番号のフォーム-->
    <form action="" method="post" >
        <input type="number" name="editnum" placeholder="編集対象番号"><br>
        <input type="text" name="editpass" placeholder="パスワード"><br>
        <input type="submit" name="submit" value="送信"><br>
    </form>
    
    <?php
    
        //表示機能
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row["id"]." ";
            echo $row["name"]." ";
            echo $row["comment"]." ";
            echo $row["date"]." ";
            echo "<br>";
        }
        
    ?>


</body>
</html>