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
<form method="post" action="{{ route('posts.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="body">Body:</label>
        <textarea id="body" name="body"></textarea><br>
        <label for="latitude">latitude:</label>
        <input type="number" class="form-control" name="latitude"/>
        <label for="longitude">longitude:</label>
        <input type="number" class="form-control" name="longitude"/>

        <div class="form-group">
            <label for="upload">File:</label>
            <input type="file" class="form-control" name="upload"/>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">Create</button>
    <button type="reset" class="btn btn-secondary">Reset</button>
    </form>