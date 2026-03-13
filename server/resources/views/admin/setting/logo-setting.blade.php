<div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
    <div class="card border">
        <div class="card-body">
            <form action="{{route('admin.logo-setting-update')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <img src="{{asset(@$logoSettings->logo)}}" width="150px" alt="">
                    <br>
                    <label>Лого</label>
                    <input type="file" class="form-control" name="logo" value="">
                    <input type="hidden" class="form-control" name="old_logo" value="{{@$logoSettings->logo}}">

                </div>

                <div class="form-group">
                    <img src="{{asset(@$logoSettings->favicon)}}" width="150px" alt="">
                    <br>
                    <label>Фавикон</label>
                    <input type="file" class="form-control" name="favicon" value="">
                    <input type="hidden" class="form-control" name="old_favicon" value="{{@$logoSettings->favicon}}">

                </div>



                <button type="submit" class="btn btn-primary">Запази</button>
            </form>
        </div>
    </div>
</div>
