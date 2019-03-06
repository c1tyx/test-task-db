<?php
require_once("includes/db/connection.php");
include("includes/header.php");

if(isset($_POST["register"])){

    if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['country']) ) {
        $name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $country = htmlspecialchars($_POST['country']);
        $query = $link->query("SELECT * FROM users WHERE name ='".$name."'");
        $numrows = mysqli_num_rows($query);
        if($numrows==0) {
            $sql="INSERT INTO users (name, email, country) VALUES('$name','$email', '$country')";
            $result= $link-> query($sql);
            if($result){
                $message = "Account Successfully Created";
            } else {
                $message = "Failed to insert data information!";
            }
        } else {
            $message = "That username already exists! Please try another one!";
        }
    } else {
        $message = "All fields are required!";
    }
}
// Ругаемся, если соединение установить не удалось
if (!$link) {
    echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
    exit;
}

//Если переменная Name передана
if (isset($_POST["name"])) {
    //Если это запрос на обновление, то обновляем
    if (isset($_GET['red_id'])) {
        $sql = mysqli_query($link, "UPDATE `users` SET `name` = '{$_POST['name']}',`email` = '{$_POST['email']}' ,`country` = '{$_POST['country']}' WHERE `ID`={$_GET['red_id']}");
    } else {
        //Иначе вставляем данные, подставляя их в запрос
        $sql = mysqli_query($link, "INSERT INTO `users` (`name`, `email`, `country`) VALUES ('{$_POST['name']}', '{$_POST['email']}', '{$_POST['country']}')");
    }

    //Если вставка прошла успешно
    if ($sql) {
        echo '<div class="container"><div class="row"><p>Успішно!</p></div></div>';
    } else {
        echo '<div class="container"><div class="row"><p>Помилка: ' . mysqli_error($link) . '</p></div></div>';
    }
}

if (isset($_GET['del_id'])) { //проверяем, есть ли переменная
    //удаляем строку из таблицы
    $sql = mysqli_query($link, "DELETE FROM `users` WHERE `ID` = {$_GET['del_id']}");
    if ($sql) {
        echo '<div class="container"><div class="row"><p>Користувача видалено</p></div></div>';
    } else {
        echo '<div class="container"><div class="row"><p>Помилка: ' . mysqli_error($link) . '</p></div></div>';
    }
}

//Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
if (isset($_GET['red_id'])) {
    $sql = mysqli_query($link, "SELECT `id`, `name`, `email`, `country` FROM `users` WHERE `ID`={$_GET['red_id']}");
    $product = mysqli_fetch_array($sql);
}
?>
    <div class="container">
        <div class="row">
    <form action="" method="post" class="form_edit">
        <table>
            <tr>
                <td>Name:</td>
                <td><input type="text" name="name"  value="<?= isset($_GET['red_id']) ? $product['name'] : ''; ?>"></td>
                <td>Email:</td>
                <td><input type="text" name="email"  value="<?= isset($_GET['red_id']) ? $product['email'] : ''; ?>"></td>
                <td>Country:</td>
                <td><input type="text" name="country"  value="<?= isset($_GET['red_id']) ? $product['country'] : ''; ?>"> </td>
                <td><button type="submit" class="button">Підтвердження</button></td>
            </tr>
        </table>
    </form>

    <table class="table table-striped table-hover">
        <thead class="thead-dark">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Country</th>
            <th scope="col">Delete</th>
            <th scope="col">Edit</th>
        </tr>
        </thead>
        <?php
        $sql = mysqli_query($link, 'SELECT `id`, `name`, `email`, `country` FROM `users`');
        while ($result = mysqli_fetch_array($sql)) {
            echo '<tr>' .
                "<td>{$result['id']}</td>" .
                "<td><i class=\"fas fa-signature\"></i> {$result['name']}</td>" .
                "<td><i class=\"fas fa-envelope\"></i> {$result['email']}</td>" .
                "<td><i class=\"fas fa-globe\"></i> {$result['country']}</td>" .
                "<td><i class=\"fas fa-user-minus\"></i> <a class='delete' href='?del_id={$result['id']}'>Delete this user</a></td>" .
                "<td><i class=\"fas fa-user-edit\"></i> <a class='edit_u' href='?red_id={$result['id']}'>Edit this user</a></td>" .
                '</tr>';
        }
        ?>
    </table>
        </div>
    </div>

<?php include("includes/footer.php"); ?>