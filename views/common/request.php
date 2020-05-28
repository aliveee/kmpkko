<div class="request">
    <div class="container">
        <a name="request_form"></a>
        <form class="js-ajax" action="/feedback/" method="post">
            <div class="h text-center mb-4">Запрос стоимости</div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" name="name" id="name2" class="form-control" placeholder="" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Телефон</label>
                        <input type="text" name="phone" id="phone2" class="form-control masked-input" placeholder="" required data-inputmask="'mask': '+7(999) 99-99-999'"/>
                    </div>
                    <div class="form-group">
                        <label for="company">Email</label>
                        <input type="text" name="email" id="email2" class="form-control" placeholder="" required>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="type">Тип оборудования/Комментарий</label>
                        <textarea name="message" id="comment2" class="form-control" style="height: 250px;" placeholder="" required></textarea>
                    </div>
                </div>
            </div>
            <div class="form-group text-center mt-3 mt-lg-5">
                <button class="btn btn-primary">Отправить запрос</button>
            </div>
        </form>
    </div>
</div>