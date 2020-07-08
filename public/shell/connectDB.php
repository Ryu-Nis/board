<?php
$mysqli = new mysqli( 'mysql', 'root', 'root', 'board');

// ↓もしDBを切り替えるときがあったら、これを使う
// $link->select_db("amazon");
// /* 現在のデフォルトデータベース名を返します */
// if ($result = $link->query("SELECT DATABASE()")) {
//     $row = $result->fetch_row();
//     printf("Default database is %s.\n", $row[0]);
//     $result->close();
// }

?>