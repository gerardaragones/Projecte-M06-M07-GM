<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form method="post" action="{{ route('files.update', $file->id) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="form-group">
    <table class="table">
        <thead>
            <tr>
                <td scope="col">ID</td>
                <td scope="col">Filepath</td>
                <td scope="col">Filesize</td>
                <td scope="col">Created</td>
                <td scope="col">Updated</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $file->id }}</td>
                <td><img class="img-fluid" src='{{ asset("storage/{$file->filepath}") }}' /></td>
                <td>{{ $file->filesize }}</td>
                <td>{{ $file->created_at }}</td>
                <td>{{ $file->updated_at }}</td>
            </tr>
        </tbody>
    </table>
    </div>
    <div class="form-group">
        <label for="upload">File:</label>
        <input type="file" class="form-control" name="upload"/>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <button type="reset" class="btn btn-secondary">Reset</button>
</form>
</body>
</html>