<?PHP

//處理登入
$user = '';
session_start();
if (isset($_SESSION['user'])) {
    $user = $_SESSION["user"];
}
if (isset($_SESSION['prvilige'])) {
    $prvilige = $_SESSION["prvilige"];
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Lists</title>
    <!-- 將 CSS 文件連結到 HTML -->
    <link rel="stylesheet" href="list.css">
</head>

<body id="top">
    <div id="contener">
        <form method="post" enctype="multipart/form-data">
            <header class="col-pc-12 col-mobile-12">

                <nav id="tool">
                    <div id="home">
                        <a href="index.php"><img src="icon/house-solid.svg" alt="go to homepage" width="20px" height="20px"></a>
                    </div>

                    <div id="cart">
                        <?PHP
                        if (empty($user)) {
                            echo '<a href="check.php">請登入使用購物車</a>';
                        } else {
                            echo ' &nbsp; Hi~ &nbsp;' . $user;
                            echo '<input type="submit" name="logout" value="登出">';
                        }
                        if (isset($_POST['logout'])) {
                            session_destroy();
                            echo '<script>location.href = "index.php";</script>';
                        }
                        ?>
                    </div>
                </nav>

            </header>
            <main>
                <div id="outer">
                    <?PHP
                    $result_user;
                    $result_cart;
                    $mysqli = new mysqli("localhost", "root", "den959glow487", "test1");
                    $mysqli->query("SET NAMES 'UTF8' ");
                    $result_user = $mysqli->query('SELECT * FROM test1.user where account in 
                    (SELECT distinct user FROM test1.cart where buy_confirm = 1);'); //抓有訂單的人名的table

                    //如果沒有訂單則顯示
                    if (mysqli_num_rows($result_user) === 0) {
                        echo '
                            <div id="item">
                            <div id="row_no_list" class="row">
                                <div>目前尚無訂單需要處裡！</div>
                            ';
                        $result_user->close();
                        $mysqli ->close();   //關閉資料庫
                    } else {
                        //$row_user[0] user_id
                        //$row_user[1] account
                        //$row_user[2] password
                        //$row_user[3] prvilige
                        //$row_user[4] name
                        //$row_user[5] to_shop
                        //$row_user[6] to_address
                        //$row_user[7] bank_account
                        while ($row_user = mysqli_fetch_row($result_user)) {
                            $rows_user = $row_user[0];
                            $account = $row_user[1];
                            echo '
                                <div id="item">
                                <div id="row1" class="row">
                                    <div id="acount">acount :&nbsp;&nbsp;' . $account . '</div>
                                    <input type="submit" name="done_' . $rows_user . '" id="done" value="done1" title="完成此訂單，刪除。" style=" height:22px"></div>
                                <div id="row2" class="row">
                                    <div id="name">name :&nbsp;&nbsp;' . $row_user[4] . '</div>
                                    <div id="to_home">home :&nbsp;&nbsp;' . $row_user[6] . '</div>
                                </div>
                                <div id="row3" class="row">
                                    <div id="user_id">user id :&nbsp;&nbsp;' . $row_user[0] . '</div>
                                    <div id="to_shop">shop :&nbsp;&nbsp;' . $row_user[5] . '</div>
                                </div>
                                <div id="row4" class="row">
                                <div id="product">
                                    <div id="product_id">編號</div>
                                    <div id="product_name">商品</div>
                                    <div id="product_price">價錢</div>
                                    <div id="product_num">數量</div>
                                    <div id="product_sum">小計</div>
                                </div>
                            ';
                            $result_cart = $mysqli->query('SELECT * FROM test1.`cart` where `user` = "' . $account . '" and `buy_confirm` = "1" and `buy_bool` = "1";'); //抓table
                            //$row_cart[0] id
                            //$row_cart[1] user
                            //$row_cart[2] product_id
                            //$row_cart[3] product_name
                            //$row_cart[4] product_pic_dir
                            //$row_cart[5] price
                            //$row_cart[6] num
                            //$row_cart[7] buy_bool
                            //$row_cart[8] product_description
                            while ($row_cart = mysqli_fetch_row($result_cart)) {
                                echo '
                                    <div id="product">
                                        <div id="product_id">' . $row_cart[0] . '</div>
                                        <div id="product_name">' . $row_cart[3] . '</div>
                                        <div id="product_price">' . $row_cart[5] . '</div>
                                        <div id="product_num">' . $row_cart[6] . '</div>
                                        <div id="product_sum">' . $row_cart[5] * $row_cart[6] . '</div>
                                    </div>
                                ';
                            }
                            echo '
                                </div>
                                </div>
                            ';

                            //如果管理者已經處裡完訂單則需點擊 "done" 以消除該使用者的訂購項目
                            if (isset($_POST['done_' . $rows_user . ''])) {
                                echo "<script>alert('gonna delete the object! under  " . $account . "')</script>;";
                                $sqlj = 'DELETE FROM `test1`.`cart` where `user` = "' . $account . '" and `buy_confirm` = "1" and `buy_bool` = "1";';  //欲刪除的單項目
                                $mysqli->query($sqlj);
                                echo '<script>location.href = "list.php";</script>';
                                // echo '<script>location.href = "list.php";</script>';
                            }
                            $result_cart->close();   
                        }
                        $result_user->close();   
                        $mysqli ->close();   //關閉資料庫   
                    }
                    ?>

                </div>
            </main>
        </form>
    </div>
    <footer>
        <div id="editing_page"><a href="edit.php">go to Editing Page</a> </div>
        <div id="author">
            <p>本網站由德斯貿易公司(Desmo co.,lmt.)所有 Copy Right &copy; 2023</p>
        </div>
        <div id="cont"><a href="contact.php">Contact Us</a> </div>
    </footer>
</body>
<span id="toTop"> <a href="#top"><img src="icon/arrow-up.svg" alt="" title="to top" height="35px" width="35px"></a></span>

</html>