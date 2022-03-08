<div class="bd-example">
    <div class="text-left"><h4><?= $title ?></h4></div>
    <form class="needs-validation <?= (!empty($errors)?'was-validated':'') ?>" method="POST" id="create_task_form" novalidate>
        <div class="form-row">
            <div class="col-sm-4 mb-3">
                <label for="validationCustom01">Имя пользователя</label>
                <input type="text" class="form-control form-control-sm" id="validationCustom01" placeholder="Иван" name="user_name" value="<?= isset($params['user_name'])?$params['user_name']:'' ?>" required>
                <div class="invalid-feedback" for="validationCustom01">Не может быть пустым</div>
            </div>
            <div class="col-sm-4 mb-3">
                <label for="validationCustom02">Email пользователя</label>
                <input type="email" class="form-control form-control-sm <?= (!empty($errors['user_email'])?'is-invalid':'') ?>" id="validationCustom02" placeholder="user_email@dot.com" name="user_email" value="<?= isset($params['user_email'])?$params['user_email']:'' ?>" required>
                <div class="invalid-feedback" for="validationCustom02">Не может быть пустым, соблюдайте фомрат email</div>
            </div>
            <div class="col-sm-4 mb-3">
                <label for="validationCustom03">Статус</label>
                <select class="form-control form-control-sm" style="height: calc(2.0rem + 1px);" id="validationCustom03" aria-describedby="basic-addon01" name="status" required>
                    <?php foreach($status_list as $status_k => $status_v) {
                        $selected = (isset($params['status']) && $params['status'] == $status_k)?'selected="selected"':'';
                    ?>
                    <option <?= $selected ?> value="<?= $status_k ?>"><?= $status_v ?></option>
                    <?php } ?>
                </select>
                <div class="invalid-feedback" for="validationCustom03">Не может быть пустым</div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationCustom04">Текст задачи</label>
                <textarea class="form-control form-control-sm" id="validationCustom04" name="task_text" rows="3" placeholder="Текст задачи" required data-default="<?= isset($params['task_text'])?$params['task_text']:'' ?>"><?= isset($params['task_text'])?$params['task_text']:'' ?></textarea>
                <div class="invalid-feedback" for="validationCustom04">Не может быть пустым</div>
            </div>
        </div>
        <div class="text-right mt-3">
            <a href="<?= $this->app_root_path ?>"><button class="btn btn-primary" type="button">Назад</button></a>
            <button class="btn btn-primary" type="submit"><?= $submit_title ?></button>
        </div>
    </form>
</div>

<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>