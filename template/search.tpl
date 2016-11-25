<html>
<head><title>検索</title></head>
<body>
<h2>社員検索</h2>
<form action="./search.php" method="POST">
<table>
<tr>
    <td>社員番号</td>
    <td><input type="text" name="number" value="<?php if (isset($view->input['number'])) { echo $view->input['number']; } ?>"/></td>
</tr>
<tr>
    <td>名前</td>
    <td><input type="text" name="name" value="<?php if (isset($view->input['name'])) { echo $view->input['name']; } ?>"/></td>
</tr>
<tr>
    <td>電話番号</td>
    <td><input type="text" name="tel" value="<?php if (isset($view->input['tel'])) { echo $view->input['tel']; } ?>"/></td>
</tr>
<tr>
    <td>住所</td>
    <td><input type="text" name="address" value="<?php if (isset($view->input['address'])) { echo $view->input['address']; } ?>"/></td>
</tr>
<tr>
    <td>所属部署</td>
    <td><input type="text" name="section" value="<?php if (isset($view->input['section'])) { echo $view->input['section']; } ?>"/></td>
</tr>
</table>
<input type="submit" value="検索">
<input type="hidden" name="action" value="search">
</form>
※検索は、AND検索および完全一致となります
<hr>
<table border=1>
<?php foreach ($view->lists as $list) { ?>
<tr>
    <td><?php echo $list['number'] ?></td>
    <td><?php echo $list['name'] ?></td>
    <td><?php echo $list['tel'] ?></td>
    <td><?php echo $list['address'] ?></td>
    <td><?php echo $list['section'] ?></td>
    <form action="./update.php" method="POST">
    <td>
        <input type="hidden" name="number" value="<?php echo $list['number'] ?>" />
        <input type="submit" value="更新" />
    </td>
    </form>
    <form action="./register.php" method="POST">
    <td>
        <input type="hidden" name="number" value="<?php echo $list['number'] ?>" />
        <input type="hidden" name="action" value="delete" />
        <input type="submit" value="削除" />
    </td>
    </form>
</tr>
<?php } ?>
</table>
</body>
</html>