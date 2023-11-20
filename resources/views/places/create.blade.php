<!--
@if ($errors->any())
<div class="alert alert-danger">
   <ul>
       @foreach ($errors->all() as $error)
       <li>{{ $error }}</li>
       @endforeach
   </ul>
</div>
@endif
-->
<form method="post" action="{{ route('places.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" name="name"/>

        <label for="description">Description:</label>
        <input type="text" class="form-control" name="description"/>

        <label for="latitude">Latitude:</label>
        <input type="number" class="form-control" name="latitude"/>

        <label for="longitude">Longitude:</label>
        <input type="number" class="form-control" name="longitude"/>

        <label for="upload">File:</label>
        <input type="file" class="form-control" name="upload"/>
    </div>
    <button type="submit" class="btn btn-primary">Create</button>
    <button type="reset" class="btn btn-secondary">Reset</button>
    </form>