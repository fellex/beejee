<form class="needs-validation <?= (!empty($errors['login']) || !empty($errors['password'])?'was-validated':'') ?>" action="<?= $this->app_root_path ?>account/login" method="POST" novalidate>
    <div class="form-row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <div class="bd-example">
                <div class="text-left"><h3>Вход</h3></div>
                <div class="form-row">
                    <div class="col-sm-12 mb-3 mt-2">
                    	<label for="form_elem1">Логин</label>
                    	<input class="form-control form-control-sm" type="text" tabindex="1" name="login" id="form_elem1" autofocus required>
                        <div class="invalid-feedback" for="form_elem1">Не может быть пустым</div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-sm-12">
                    	<label for="form_elem2">Пароль</label>
                    	<input class="form-control form-control-sm" type="password" tabindex="2" name="password" id="form_elem2" required>
                        <div class="invalid-feedback" for="form_elem2">Не может быть пустым</div>
                    </div>
                </div>

                <?php if(!empty($errors['fail'])) { ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $errors['fail'] ?>
                </div>
                <?php } ?>

                <div class="text-right mt-3">
                	<button class="btn btn-primary" type="submit" tabindex="3" name="enter">Войти</button>
                </div>
            </div>
        </div>
        <div class="col-sm-3"></div>
    </div>
</form>

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
