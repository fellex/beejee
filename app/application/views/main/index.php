<?php // отображаем блок "Скачать" ?>
<div class="text-right mt-3">
    <a href="<?= $this->app_root_path ?>create"><button class="btn btn-primary" type="submit">Новая задача</button></a>
</div><br>

<?php // отображаем список задач ?>
<div class="dataTables_wrapper" id="datatables_wrapper_id">
    <div class="row">
        <div class="col-sm-12 col-md-9">
            <?= $paginator ?>
        </div>
        <div class="col-sm-12 col-md-3 text-white">
            <div class="ttl-cnt-tsks">
                <label><b>Всего задач: <?= $total_count_tasks ?></b></label>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-sm-12">

            <table class="table table-striped table-light" id="tasks_table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" style="cursor: pointer;" onclick="sort('id','<?= $order_change['id'] ?>');">ID<?= $order_arrows['id'] ?></th>
                        <th scope="col" style="cursor: pointer;" onclick="sort('user_name','<?= $order_change['user_name'] ?>');">Имя пользователя<?= $order_arrows['user_name'] ?></th>
                        <th scope="col" style="cursor: pointer;" onclick="sort('user_email','<?= $order_change['user_email'] ?>');">Email<?= $order_arrows['user_email'] ?></th>
                        <th scope="col" style="cursor: pointer;" onclick="sort('task_text','<?= $order_change['task_text'] ?>');">Текст задачи<?= $order_arrows['task_text'] ?></th>
                        <th scope="col" style="cursor: pointer;" onclick="sort('status','<?= $order_change['status'] ?>');">Статус<?= $order_arrows['status'] ?></th>
                        <th scope="col">Изменено</th>
                        <th scope="col">Редактировать</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($tasks_list)) {
                    foreach($tasks_list as $row_num => $tasks_row) { ?>
                    <tr <?= ($tasks_row['id'] == $flash_created)?'class="table-success"':'' ?>>
                        <th scope="row"><?= $tasks_row['id'] ?></th>
                        <td><?= $tasks_row['user_name'] ?></td>
                        <td><?= $tasks_row['user_email'] ?></td>
                        <td><?= $tasks_row['task_text'] ?></td>
                        <td><?= ((isset($status_list[$tasks_row['status']]))?$status_list[$tasks_row['status']]:'') ?></td>
                        <td><?= !is_null($tasks_row['changed_by_user'])?$tasks_row['changed_by_user']:'&ndash;' ?></td>
                        <td><a href="<?= $this->app_root_path ?>edit?id=<?= $tasks_row['id'] ?>">Редактировать</a></td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="no-tsks">Нет задач</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>



    <div class="row">
        <div class="col-sm-12 col-md-9">
            <?= $paginator ?>
        </div>
        <div class="col-sm-12 col-md-3 text-white">
            <div class="ttl-cnt-tsks">
                <label><b>Всего задач: <?= $total_count_tasks ?></b></label>
            </div>
        </div>
    </div>
</div>
