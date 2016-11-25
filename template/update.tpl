<html>
<head><title>登録/更新/削除</title></head>
<body>
<h2>社員更新</h2>
<?php if (isset($view->errorMsg) && strlen($view->errorMsg) > 0) { ?>
    <p style="color: #ff0000;"><?php echo $view->errorMsg ?></p>
<?php } ?>
<form action="./update.php" method="POST">
<table>
<tr>
    <td>社員番号<span style="font-size: 10px; color: #ff0000;">[必須]</span></td>
    <td><?php echo $view->number; ?></td>
    <input type="hidden" name="number" value="<?php echo $view->number; ?>" />
</tr>
<tr>
    <td>名前<span style="font-size: 10px; color: #ff0000;">[必須]</span></td>
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
<input type="submit" value="更新">
<input type="hidden" name="action" value="update">
</form>
</body>
</html>