<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
    <form method="post" action="{{ route('files.destroy', $file->id) }}">
        @method('DELETE')
        @csrf
        <button type="submit" class="btn btn-danger">Destroy</button>
        <button type="submit" class="btn btn-primary"><a href="{{ route('files.edit', $file->id) }}">Editar</a></button>
    </form>
</body>
</html>