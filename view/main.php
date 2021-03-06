<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="library/css/bootstrap.min.css">
    <link rel="stylesheet" href="library/css/style.css">
    <script src="library/js/JQuery.js"></script>
    <script src="library/js/bootstrap.min.js"></script>
    <title>TEST</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3 left-menu">
            <div class="top-left-menu">
                <select onChange="changeRole(this)">
                    <option value="0"> Выберите роль</option>
                    <? foreach ($this->data['roles'] as $role):?>
                    <option value="<?= $role['role_id'] ?>" <? if ($_GET['role']==$role['role_id']) {?> selected
                        <?}?>>
                        <?= $role['role_name'] ?>
                    </option>
                    <?endforeach?>
                </select>
                <h3>Меню</h3>
            </div>
                <div class="pages">
                    <ul>
                        <?if($this->data['accessPages']) foreach ($this->data['accessPages'] as $page):?>
                        <li><?= $page['page_name']; ?></li>
                        <?endforeach?>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 main-table">
                <table border="1" width="100%" class="table">
                    <thead class='thead-dark'>
                    <tr>
                        <th scope="col">Пункт меню</th>
                        <th scope="col">Привязанная роль</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <?  if ($_GET['role'] > 0)
                        $role = "&role=".$_GET['role'];
                    else
                        $role= '';?>
                    <?foreach ($this->data['allPages'] as $page):?>
                    <tr>
                        <td><?= $page['page_name'] ?></td>
                        <td><?= implode(", ", array_column($page['access'], 'role_name')) ?></td>
                        <td>
                            <form method="POST">
                                <input type='hidden' name="page_id" value="<?= $page['page_id'] ?>">
                                <button type="submit" name="action" value="deletePage" class="btn btn-danger">Удалить</button>
                            </form>
                            <a href="/?pageEdit=<?= $page['page_id'] . $role ?>" class="btn btn-primary">Редактировать</a>
                        </td>
                    </tr>
                    <?endforeach?>
                </table>
                <button class="btn btn-primary" data-toggle="modal" data-target="#ModalMenu">Создать пункт меню</button>
                <button class="btn btn-primary float-right" data-toggle="modal" data-target="#ModalRole">Создать новую роль</button>
            </div>
        </div>
        <? if ($_GET['pageEdit'] > 0):?>
        <div class="col-md-9 offset-md-3 edit-table">
            <form method="POST">
                <input type='hidden' name="page_id" , value="<?= $_GET['pageEdit'] ?>">
                <table border="1" width="100%" class="table">
                    <thead class='thead-dark'>
                    <tr>
                        <th>Доступные для ролей</th>
                    </tr>
                </thead>
                    <?if($this->data['accessRoles']) foreach( $this->data['accessRoles'] as $role):?>
                    <tr>
                        <td><label>
                                <input type='checkbox' name="roles[]" value="<?= $role['role_id'] ?>" <? if ($role['page']==$_GET['pageEdit']):?> checked
                                <? endif ?>
                                > <?= $role['role_name'] ?>
                            </label></td>
                    </tr>
                    <? endforeach ?>
                </table>
                <button type="submit" class="btn btn-primary float-right" name='action' value="editPage">Сохранить</button>
            </form>
        </div>
        <? endif ?>
    </div>

    </div>


    <div class="modal fade" id="ModalMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить пункт меню</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" name="name" placeholder="Введите название пункта меню"><div class="input-group-append">
                        <button class="btn btn-primary" type="submit" name="action" value="addMenu" >+</button></div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ModalRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить роль</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                    <div class="input-group">
                        <input type="text"  class="form-control" name="name" placeholder="Введите название роли">
                        <button type="submit" name="action" value="addRole" class="btn btn-primary">+</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeRole(value) {
            document.location = "?role=" + value.options.selectedIndex;
        }
    </script>


</body>

</html>