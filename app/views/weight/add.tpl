<div class="row-fluid">
    <div class="span12">
      <form action="" class="form-horizontal" method="post">
        <fieldset>
          <legend>Отметка о весе</legend>
          <div class="control-group">
            <label class="control-label" for="datetime">Дата отметки</label>
            <div class="controls">
              <input type="text" class="input-medium" value="{{ now|date('m/d/Y H:i') }}" id="datetime">
              <p class="help-block">Дата на которую вы хотите сделать отметку о весе. Эта может быть любая дата не больше текущей. Так же вы можете указывать время что бы делать <code>несколько отметок в день</code>.</p>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label" for="weight">Вес</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on"><i class="icon-camera"></i></span><input class="span2" id="weight" size="16" type="text">
              </div>
              <p class="help-block">Ваш вес на момент даты</p>
            </div>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-success">Добавить</button>
            <button class="btn">Отмена</button>
          </div>
        </fieldset>
      </form>
    </div>
</div>

<script>
    $('#datetime').datetimepicker({
        showTime: true
    });
</script>
