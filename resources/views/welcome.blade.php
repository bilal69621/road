<form method="post" action="{{url('excel_import')}}" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit"></button>
</form>
