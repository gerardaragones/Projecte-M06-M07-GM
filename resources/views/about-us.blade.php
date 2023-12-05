<!DOCTYPE html>
<html>
<head>
    <title>About Us</title>
</head>
<body>
    <h1>About Us</h1>
    
    @foreach($teamMembers as $member)
        <div>
            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}">
            <h2>{{ $member['name'] }}</h2>
            <p>{{ $member['position'] }}</p>
        </div>
    @endforeach
</body>
</html>
