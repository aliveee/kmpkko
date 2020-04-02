<div class="request">
    <div class="container">
        <a name="request_form"></a>
        <form action="">
            <div class="h text-center mb-4">Запрос стоимости</div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Компания</label>
                        <input type="text" name="company" id="company" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="type">Тип котла</label>
                        <select class="custom-select" name="type" id="type" required>
                            <option selected>Выберите</option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fuel">Вид топлива</label>
                        <select class="custom-select" name="fuel" id="fuel" required>
                            <option selected>Выберите</option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="power">Мощность котла</label>
                        <select class="custom-select" name="power" id="power" required>
                            <option selected>Выберите</option>
                            <option value=""></option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group text-center mt-3 mt-lg-5">
                <button class="btn btn-primary">Отправить запрос</button>
            </div>
        </form>
    </div>
</div>